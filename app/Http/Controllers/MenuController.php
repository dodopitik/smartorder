<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotificationMail;
use App\Models\AppSetting;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query->has('tenant')) {
            return redirect()->to($request->fullUrlWithoutQuery(['tenant']));
        }

        $tenant = $this->requireTenant();
        $roomNumber = $this->normalizeRoomNumber(
            $request->query('kamar', $request->query('meja'))
        );

        if ($roomNumber !== null) {
            Session::put($this->roomKey($tenant), $roomNumber);
        }

        $roomNumber ??= Session::get($this->roomKey($tenant));

        $items = Cache::remember($this->menuItemsCacheKey($tenant), now()->addMinutes(10), function () use ($tenant) {
            return Item::query()
                ->where('tenant_id', $tenant->id)
                ->where('is_available', 1)
                ->with('category')
                ->orderBy('name', 'asc')
                ->get();
        });

        return view('customer.menu', compact('items', 'roomNumber'));
    }

    public function cart()
    {
        $cart = Session::get($this->cartKey($this->requireTenant()), []);
        return view('customer.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $tenant = $this->requireTenant();
        $menuId = $request->input('id', $request->query('id'));

        if (! $menuId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Menu tidak ditemukan'], 404);
            }

            return redirect()->route('tenant.menu', ['tenant' => $tenant->slug])
                ->with('error', 'Menu tidak ditemukan.');
        }

        $menu = Item::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_available', 1)
            ->find($menuId);

        if (! $menu) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Menu tidak ditemukan'], 404);
            }

            return redirect()->route('tenant.menu', ['tenant' => $tenant->slug])
                ->with('error', 'Menu tidak ditemukan.');
        }

        $cart = Session::get($this->cartKey($tenant), []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => $menu->image,
                'qty' => 1,
            ];
        }

        Session::put($this->cartKey($tenant), $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => 'Berhasil ditambahkan ke keranjang!',
                'cart' => $cart,
            ]);
        }

        return redirect()->route('tenant.cart', ['tenant' => $tenant->slug])
            ->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function clearCart()
    {
        $tenant = $this->requireTenant();
        Session::forget($this->cartKey($tenant));
        return redirect()->route('tenant.cart', ['tenant' => $tenant->slug])->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function updateCart(Request $request)
    {
        $tenant = $this->requireTenant();
        $itemId = $request->input('id');
        $newQty = (int) $request->input('qty');

        if ($newQty <= 0) {
            return response()->json(['success' => false]);
        }

        $cart = Session::get($this->cartKey($tenant), []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $newQty;
            Session::put($this->cartKey($tenant), $cart);
            Session::flash('success', 'Keranjang berhasil diperbarui.');

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function removeCart(Request $request)
    {
        $tenant = $this->requireTenant();
        $itemId = $request->input('id');
        $cart = Session::get($this->cartKey($tenant), []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put($this->cartKey($tenant), $cart);
            Session::flash('success', 'Item berhasil dihapus dari keranjang.');
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function checkout()
    {
        $tenant = $this->requireTenant();
        $cart = Session::get($this->cartKey($tenant));

        if (empty($cart)) {
            return redirect()->route('tenant.cart', ['tenant' => $tenant->slug])->with('error', 'Keranjang kosong, silakan pilih menu terlebih dahulu.');
        }

        $roomNumber = Session::get($this->roomKey($tenant));

        if (! $roomNumber) {
            return redirect()->route('tenant.menu', ['tenant' => $tenant->slug])
                ->with('error', 'Nomor kamar belum terdeteksi. Silakan scan ulang QR dari kamar Anda.');
        }

        return view('customer.checkout', compact('cart', 'roomNumber'));
    }

    public function storeOrder(Request $request)
    {
        $tenant = $this->requireTenant();
        $cart = Session::get($this->cartKey($tenant));
        $roomNumber = Session::get($this->roomKey($tenant));

        if (empty($cart)) {
            return redirect()->route('tenant.cart', ['tenant' => $tenant->slug])->with('error', 'Keranjang kosong, silakan pilih menu terlebih dahulu.');
        }

        if (! $roomNumber) {
            return redirect()->route('tenant.menu', ['tenant' => $tenant->slug])
                ->with('error', 'Nomor kamar belum terdeteksi. Silakan scan ulang QR dari kamar Anda.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,qris',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tenant.checkout', ['tenant' => $tenant->slug])->withErrors($validator)->withInput();
        }

        $totalAmount = 0;
        $itemDetails = [];

        $cartItemIds = collect($cart)->pluck('id')->filter()->unique()->values();
        $dbItems = Item::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_available', 1)
            ->whereIn('id', $cartItemIds)
            ->get()
            ->keyBy('id');

        // Re-fetch harga dari database untuk memastikan harga terkini.
        foreach ($cart as $key => $cartItem) {
            $dbItem = $dbItems->get($cartItem['id']);
            if (! $dbItem) {
                return redirect()->route('tenant.cart', ['tenant' => $tenant->slug])
                    ->with('error', 'Menu "' . ($cartItem['name'] ?? '') . '" sudah tidak tersedia. Silakan hapus dari keranjang.');
            }
            // Update harga di cart dengan harga terkini dari DB
            $cart[$key]['price'] = $dbItem->price;
            $totalAmount += $dbItem->price * $cartItem['qty'];

            $itemDetails[] = [
                'id' => $dbItem->id,
                'price' => (int) round($dbItem->price + ($dbItem->price * 0.11)),
                'quantity' => $cartItem['qty'],
                'name' => substr($dbItem->name, 0, 50),
            ];
        }

        $customerRoleId = Role::query()->where('role_name', 'customer')->value('id')
            ?? abort(500, 'Role customer belum tersedia.');

        // Snapshot the platform fee at the moment of order creation so any
        // future change in the global setting does NOT alter historical orders.
        $platformFeeSnapshot = (int) AppSetting::getValue('monthly_fee_per_order', '1000');

        [$user, $order] = DB::transaction(function () use ($request, $tenant, $customerRoleId, $roomNumber, $totalAmount, $cart, $platformFeeSnapshot) {
            $user = User::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'fullname' => $request->input('fullname'),
                    'phone' => $request->input('phone'),
                ],
                [
                    'role_id' => $customerRoleId,
                    'email' => null,
                    'username' => null,
                    'password' => null,
                ]
            );

            $order = Order::create([
                'tenant_id' => $tenant->id,
                'order_code' => strtoupper($tenant->slug) . '-R' . ($roomNumber ?: 'NA') . '-' . now()->format('YmdHis') . substr(uniqid(), -4),
                'user_id' => $user->id,
                'subtotal' => $totalAmount,
                'tax' => (int) round(0.11 * $totalAmount),
                'grandtotal' => (int) round($totalAmount + ($totalAmount * 0.11)),
                'platform_fee' => $platformFeeSnapshot,
                'status' => 'pending',
                'table_number' => (int) ($roomNumber ?: 0),
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'] * $item['qty'],
                    'tax' => (int) round(0.11 * $item['price'] * $item['qty']),
                    'total_price' => (int) round(($item['price'] * $item['qty']) + ($item['price'] * 0.11 * $item['qty'])),
                ]);
            }

            return [$user, $order];
        });

        Session::put($this->lastOrderKey($tenant), $order->order_code);
        Session::forget($this->cartKey($tenant));
        Cache::forget($this->dashboardCacheKey($tenant));

        // Kirim email notifikasi jika diaktifkan
        $this->sendOrderNotificationEmail($tenant, $order);

        if ($request->payment_method === 'cash') {
            return redirect()->route('tenant.checkout.success', [
                'tenant' => $tenant->slug,
                'orderId' => $order->order_code,
            ])
                ->with('success', 'Pesanan berhasil dibuat! Silakan tunggu pesanan Anda.');
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $order->grandtotal,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $user->fullname ?? 'Guest',
                'phone' => $user->phone,
            ],
            'enabled_payments' => ['qris', 'gopay', 'shopeepay'],
        ];

        try {
            $snaptoken = \Midtrans\Snap::getSnapToken($params);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snaptoken,
                'order_code' => $order->order_code,
                'redirect_url' => route('tenant.checkout.success', [
                    'tenant' => $tenant->slug,
                    'orderId' => $order->order_code,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::warning('Midtrans token generation failed, order kept as pending.', [
                'tenant_id' => $tenant->id,
                'order_code' => $order->order_code,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan sudah tercatat, tetapi token pembayaran gagal dibuat. Silakan lanjutkan dari halaman nota.',
                'redirect_url' => route('tenant.checkout.success', [
                    'tenant' => $tenant->slug,
                    'orderId' => $order->order_code,
                ]),
            ]);
        }
    }

    public function checkoutSuccess($orderId)
    {
        $tenant = $this->requireTenant();
        $order = Order::query()
            ->where('tenant_id', $tenant->id)
            ->where('order_code', $orderId)
            ->first();

        if (! $order && Session::has($this->lastOrderKey($tenant))) {
            $order = Order::query()
                ->where('tenant_id', $tenant->id)
                ->where('order_code', Session::get($this->lastOrderKey($tenant)))
                ->first();
        }

        if (! $order) {
            return redirect()->route('tenant.menu', ['tenant' => $tenant->slug])->with('error', 'Pesanan tidak ditemukan.');
        }

        $order = $this->syncMidtransOrderStatus($order);

        $orderItems = OrderItem::query()
            ->with('item')
            ->where('order_id', $order->id)
            ->get();

        return view('customer.success', compact('order', 'orderItems'));
    }

    public function midtransNotification(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');

            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $notification = new \Midtrans\Notification();

            // Verifikasi signature dari Midtrans
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $signatureKey = $notification->signature_key ?? '';

            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $expectedSignature) {
                Log::warning('Midtrans notification signature mismatch.', [
                    'order_id' => $orderId,
                ]);
                return response()->json(['received' => false, 'message' => 'Invalid signature'], 403);
            }

            $order = Order::query()->where('order_code', $orderId)->firstOrFail();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $order->status = 'settlement';
            } elseif ($transactionStatus === 'settlement') {
                $order->status = 'settlement';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true)) {
                $order->status = 'pending';
            }

            $order->save();
            Cache::forget('tenant.' . $order->tenant_id . '.dashboard.stats');

            return response()->json(['received' => true]);
        } catch (\Throwable $throwable) {
            Log::error('Midtrans notification error.', ['message' => $throwable->getMessage()]);
            return response()->json(['received' => false], 500);
        }
    }

    private function syncMidtransOrderStatus(Order $order): Order
    {
        if ($order->payment_method !== 'qris' || in_array($order->status, ['settlement', 'cooked'], true)) {
            return $order;
        }

        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $transaction = \Midtrans\Transaction::status($order->order_code);
            $transactionStatus = (string) ($transaction->transaction_status ?? '');
            $fraudStatus = (string) ($transaction->fraud_status ?? '');

            if (
                $transactionStatus === 'settlement'
                || ($transactionStatus === 'capture' && ($fraudStatus === '' || $fraudStatus === 'accept'))
            ) {
                $order->status = 'settlement';
                $order->save();
            }
        } catch (\Throwable $throwable) {
            Log::info('Midtrans status sync skipped on success page.', [
                'order_code' => $order->order_code,
                'message' => $throwable->getMessage(),
            ]);
        }

        return $order->fresh() ?? $order;
    }

    private function cartKey(Tenant $tenant): string
    {
        return 'cart.tenant.' . $tenant->id;
    }

    private function roomKey(Tenant $tenant): string
    {
        return 'room_number.tenant.' . $tenant->id;
    }

    private function lastOrderKey(Tenant $tenant): string
    {
        return 'last_order_code.tenant.' . $tenant->id;
    }

    private function menuItemsCacheKey(Tenant $tenant): string
    {
        return 'tenant.' . $tenant->id . '.menu.items.available';
    }

    private function dashboardCacheKey(Tenant $tenant): string
    {
        return 'tenant.' . $tenant->id . '.dashboard.stats';
    }

    private function normalizeRoomNumber(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        $roomNumber = (int) $value;

        return $roomNumber > 0 ? $roomNumber : null;
    }

    private function sendOrderNotificationEmail(Tenant $tenant, Order $order): void
    {
        if (! $tenant->notify_on_new_order || empty($tenant->notification_emails)) {
            return;
        }

        $emails = array_filter(array_map('trim', explode(',', $tenant->notification_emails)));

        if (empty($emails)) {
            return;
        }

        $orderItems = OrderItem::query()
            ->with('item')
            ->where('order_id', $order->id)
            ->get();

        try {
            foreach ($emails as $email) {
                \Illuminate\Support\Facades\Mail::to($email)
                    ->send(new NewOrderNotificationMail($order, $orderItems));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email notifikasi pesanan.', [
                'tenant_id' => $tenant->id,
                'order_code' => $order->order_code,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $this->requireTenant()->id;
        $this->syncPendingDigitalOrders($tenantId);

        $orders = Order::query()
            ->where('tenant_id', $tenantId)
            ->with('user:id,fullname,phone,email')
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        $lastOrder = Order::query()
            ->where('tenant_id', $tenantId)
            ->latest('id')
            ->first(['id', 'status']);

        // AJAX request: return partial HTML tabel saja
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'html' => view('admin.order._table', [
                    'orders' => $orders,
                    'currentTenant' => $this->requireTenant(),
                ])->render(),
                'lastOrderId' => $lastOrder?->id ?? 0,
                'lastOrderStatus' => $lastOrder?->status ?? '',
                'pendingOrders' => $orders->where('status', 'pending')->count(),
                'settlementOrders' => $orders->where('status', 'settlement')->count(),
                'cookedOrders' => $orders->where('status', 'cooked')->count(),
                'totalOrders' => $orders->count(),
                'grossRevenue' => (int) $orders->sum('grandtotal'),
            ]);
        }

        return view('admin.order.index', [
            'orders' => $orders,
            'lastOrderId' => $lastOrder?->id ?? 0,
            'lastOrderStatus' => $lastOrder?->status ?? '',
        ]);
    }

    public function show(string $tenant, string $orderId)
    {
        $orders = Order::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->findOrFail($orderId);

        $orders = $this->syncMidtransOrderStatus($orders);

        $orderItems = OrderItem::with('item')
            ->where('order_id', $orders->id)
            ->get();

        return view('admin.order.show', compact('orders', 'orderItems'));
    }

    public function settlement(Request $request, string $tenant, string $orderId)
    {
        $tenantModel = $this->requireTenant();
        $order = Order::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($orderId);

        $order->status = 'settlement';
        $order->save();
        $this->forgetDashboardCache($tenantModel->id);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order telah diselesaikan.']);
        }

        return redirect()->route('orders.index', ['tenant' => $tenantModel->slug])->with('success', 'Order telah diselesaikan.');
    }

    public function cooked(Request $request, string $tenant, string $orderId)
    {
        $tenantModel = $this->requireTenant();
        $order = Order::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($orderId);

        $order->status = 'cooked';
        $order->save();
        $this->forgetDashboardCache($tenantModel->id);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order telah diselesaikan.']);
        }

        return redirect()->route('orders.index', ['tenant' => $tenantModel->slug])->with('success', 'Order telah diselesaikan.');
    }

    public function print(string $tenant, string $orderId)
    {
        $order = Order::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->findOrFail($orderId);

        $order->load(['user', 'orderItems.item']);

        return view('admin.order.print', [
            'order' => $order,
            'orderItems' => $order->orderItems,
        ]);
    }

    public function checkNew(Request $request)
    {
        $tenant = $this->currentTenant();

        if (! $tenant) {
            return response()->json(['has_new' => false, 'status_changed' => false, 'latest_id' => 0, 'latest_status' => '']);
        }

        $lastId = (int) $request->query('last_id', 0);
        $lastStatus = (string) $request->query('last_status', '');

        $latestOrder = Order::query()
            ->where('tenant_id', $tenant->id)
            ->latest('id')
            ->first(['id', 'status']);

        $latestId = $latestOrder?->id ?? 0;
        $latestStatus = $latestOrder?->status ?? '';

        return response()->json([
            'has_new' => $lastId > 0 && $latestId > $lastId,
            'status_changed' => $latestStatus !== '' && $lastStatus !== '' && $latestStatus !== $lastStatus,
            'latest_id' => $latestId,
            'latest_status' => $latestStatus,
        ]);
    }

    private function syncPendingDigitalOrders(int $tenantId): void
    {
        $cacheKey = 'tenant.' . $tenantId . '.orders.midtrans-sync.recent';

        if (! Cache::add($cacheKey, true, now()->addSeconds(30))) {
            return;
        }

        Order::query()
            ->where('tenant_id', $tenantId)
            ->where('payment_method', 'qris')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->each(function (Order $order) {
                $this->syncMidtransOrderStatus($order);
            });
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
                $this->forgetDashboardCache((int) $order->tenant_id);
            }
        } catch (\Throwable $throwable) {
            Log::info('Midtrans status sync skipped on order admin panel.', [
                'order_code' => $order->order_code,
                'message' => $throwable->getMessage(),
            ]);
        }

        return $order->fresh() ?? $order;
    }

    private function forgetDashboardCache(int $tenantId): void
    {
        Cache::forget('tenant.' . $tenantId . '.dashboard.stats');
    }
}

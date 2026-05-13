<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StressTestOrders extends Command
{
    protected $signature = 'stress:orders
        {--count=50 : Jumlah order yang akan dibuat}
        {--tenant= : Slug tenant target (default: tenant pertama)}
        {--concurrent=5 : Simulasi batch concurrent}';

    protected $description = 'Stress test: simulasi banyak order masuk secara cepat untuk mengukur performa';

    public function handle(): int
    {
        $count = (int) $this->option('count');
        $batchSize = (int) $this->option('concurrent');
        $tenantSlug = $this->option('tenant');

        $tenant = $tenantSlug
            ? Tenant::where('slug', $tenantSlug)->first()
            : Tenant::first();

        if (! $tenant) {
            $this->error('Tenant tidak ditemukan.');
            return 1;
        }

        $items = Item::where('tenant_id', $tenant->id)->where('is_available', 1)->get();

        if ($items->isEmpty()) {
            $this->error('Tidak ada menu aktif di tenant ' . $tenant->name);
            return 1;
        }

        $customerRoleId = Role::where('role_name', 'customer')->value('id');
        if (! $customerRoleId) {
            $this->error('Role customer belum ada.');
            return 1;
        }

        $this->info("🚀 Stress Test Dimulai");
        $this->info("   Tenant: {$tenant->name} ({$tenant->slug})");
        $this->info("   Target: {$count} orders");
        $this->info("   Batch size: {$batchSize}");
        $this->info("   Menu tersedia: {$items->count()} item");
        $this->newLine();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $startTime = microtime(true);
        $errors = 0;
        $totalDbTime = 0;

        for ($i = 0; $i < $count; $i += $batchSize) {
            $batchCount = min($batchSize, $count - $i);

            for ($j = 0; $j < $batchCount; $j++) {
                $orderNum = $i + $j + 1;
                $dbStart = microtime(true);

                try {
                    DB::transaction(function () use ($tenant, $items, $customerRoleId, $orderNum) {
                        $roomNumber = rand(1, 50);
                        $itemCount = rand(1, min(5, $items->count()));
                        $selectedItems = $items->random($itemCount);

                        $user = User::firstOrCreate(
                            [
                                'tenant_id' => $tenant->id,
                                'fullname' => 'Stress Tester ' . $orderNum,
                                'phone' => '08' . rand(1000000000, 9999999999),
                            ],
                            [
                                'role_id' => $customerRoleId,
                                'email' => null,
                                'username' => null,
                                'password' => null,
                            ]
                        );

                        $subtotal = 0;
                        $cartItems = [];

                        foreach ($selectedItems as $item) {
                            $qty = rand(1, 3);
                            $subtotal += $item->price * $qty;
                            $cartItems[] = ['item' => $item, 'qty' => $qty];
                        }

                        $tax = (int) round(0.11 * $subtotal);
                        $grandtotal = $subtotal + $tax;

                        $order = Order::create([
                            'tenant_id' => $tenant->id,
                            'order_code' => strtoupper($tenant->slug) . '-R' . $roomNumber . '-ST' . now()->format('His') . rand(100, 999),
                            'user_id' => $user->id,
                            'subtotal' => $subtotal,
                            'tax' => $tax,
                            'grandtotal' => $grandtotal,
                            'status' => collect(['pending', 'settlement', 'cooked'])->random(),
                            'table_number' => $roomNumber,
                            'payment_method' => collect(['cash', 'qris'])->random(),
                            'notes' => 'Stress test order #' . $orderNum,
                        ]);

                        foreach ($cartItems as $cartItem) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'item_id' => $cartItem['item']->id,
                                'quantity' => $cartItem['qty'],
                                'price' => $cartItem['item']->price * $cartItem['qty'],
                                'tax' => (int) round(0.11 * $cartItem['item']->price * $cartItem['qty']),
                                'total_price' => (int) round(($cartItem['item']->price * $cartItem['qty']) * 1.11),
                            ]);
                        }
                    });
                } catch (\Throwable $e) {
                    $errors++;
                    $this->newLine();
                    $this->warn("  Error order #{$orderNum}: " . $e->getMessage());
                }

                $totalDbTime += (microtime(true) - $dbStart);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        $elapsed = microtime(true) - $startTime;
        $successCount = $count - $errors;
        $avgTime = $successCount > 0 ? ($totalDbTime / $successCount) * 1000 : 0;
        $ordersPerSecond = $elapsed > 0 ? $successCount / $elapsed : 0;

        // Query performance test
        $this->info("📊 Menjalankan query performance test...");
        $this->newLine();

        $queryTests = [];

        // Test 1: Order list query (admin panel)
        $qStart = microtime(true);
        Order::where('tenant_id', $tenant->id)->with('user')->orderByDesc('created_at')->limit(100)->get();
        $queryTests['Order list (100 rows + user)'] = (microtime(true) - $qStart) * 1000;

        // Test 2: Check new order (polling)
        $qStart = microtime(true);
        Order::where('tenant_id', $tenant->id)->latest('id')->first();
        $queryTests['Check new order (polling)'] = (microtime(true) - $qStart) * 1000;

        // Test 3: Dashboard aggregates
        $qStart = microtime(true);
        Order::where('tenant_id', $tenant->id)->count();
        Order::where('tenant_id', $tenant->id)->sum('grandtotal');
        Order::where('tenant_id', $tenant->id)->where('status', 'pending')->count();
        $queryTests['Dashboard aggregates (3 queries)'] = (microtime(true) - $qStart) * 1000;

        // Test 4: Monthly report
        $qStart = microtime(true);
        Order::where('tenant_id', $tenant->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->with(['tenant', 'user'])
            ->get();
        $queryTests['Monthly report (all orders + relations)'] = (microtime(true) - $qStart) * 1000;

        // Test 5: Top menus
        $qStart = microtime(true);
        DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.tenant_id', $tenant->id)
            ->select('items.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('order_items.item_id', 'items.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
        $queryTests['Top 5 menus (join + aggregate)'] = (microtime(true) - $qStart) * 1000;

        // Results
        $this->info("═══════════════════════════════════════════════════");
        $this->info("  📋 STRESS TEST REPORT");
        $this->info("═══════════════════════════════════════════════════");
        $this->newLine();
        $this->info("  🏪 Tenant: {$tenant->name}");
        $this->info("  📦 Orders created: {$successCount}/{$count}");
        $this->info("  ❌ Errors: {$errors}");
        $this->info("  ⏱️  Total time: " . number_format($elapsed, 2) . "s");
        $this->info("  ⚡ Avg per order: " . number_format($avgTime, 1) . "ms");
        $this->info("  🚀 Throughput: " . number_format($ordersPerSecond, 1) . " orders/sec");
        $this->newLine();
        $this->info("  📊 QUERY PERFORMANCE (after {$successCount} orders):");
        $this->info("  ─────────────────────────────────────────────────");

        foreach ($queryTests as $label => $ms) {
            $status = $ms < 50 ? '✅' : ($ms < 200 ? '⚠️' : '🔴');
            $this->info("  {$status} {$label}: " . number_format($ms, 1) . "ms");
        }

        $this->newLine();

        $totalOrders = Order::where('tenant_id', $tenant->id)->count();
        $this->info("  📈 Total orders in tenant now: {$totalOrders}");
        $this->info("═══════════════════════════════════════════════════");

        return 0;
    }
}

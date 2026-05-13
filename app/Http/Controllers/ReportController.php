<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.report.index', $this->buildReportPayload($request));
    }

    public function print(Request $request)
    {
        return view('admin.report.print', $this->buildReportPayload($request));
    }

    private function buildReportPayload(Request $request): array
    {
        $tenant = $this->requireTenant();
        $tenantId = $tenant->id;
        $range = $request->string('range')->toString();
        $range = in_array($range, ['daily', 'weekly', 'monthly'], true) ? $range : 'daily';

        $anchorDate = $request->filled('date')
            ? Carbon::parse($request->string('date')->toString())
            : now();

        [$startDate, $endDate, $rangeLabel, $comparisonStart, $comparisonEnd] = match ($range) {
            'weekly' => [
                $anchorDate->copy()->startOfWeek(),
                $anchorDate->copy()->endOfWeek(),
                'Laporan Mingguan',
                $anchorDate->copy()->subWeek()->startOfWeek(),
                $anchorDate->copy()->subWeek()->endOfWeek(),
            ],
            'monthly' => [
                $anchorDate->copy()->startOfMonth(),
                $anchorDate->copy()->endOfMonth(),
                'Laporan Bulanan',
                $anchorDate->copy()->subMonth()->startOfMonth(),
                $anchorDate->copy()->subMonth()->endOfMonth(),
            ],
            default => [
                $anchorDate->copy()->startOfDay(),
                $anchorDate->copy()->endOfDay(),
                'Laporan Harian',
                $anchorDate->copy()->subDay()->startOfDay(),
                $anchorDate->copy()->subDay()->endOfDay(),
            ],
        };

        $ordersQuery = Order::query()
            ->where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $orders = (clone $ordersQuery)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $comparisonOrders = Order::query()
            ->where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$comparisonStart, $comparisonEnd]);

        $totalOrders = $orders->count();
        $grossRevenue = (int) $orders->sum('grandtotal');
        $settledOrders = $orders->where('status', 'settlement')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $cookedOrders = $orders->where('status', 'cooked')->count();
        $averageOrderValue = $totalOrders > 0 ? (int) round($grossRevenue / $totalOrders) : 0;
        $cashOrders = $orders->where('payment_method', 'cash')->count();
        $qrisOrders = $orders->where('payment_method', 'qris')->count();
        $comparisonRevenue = (int) $comparisonOrders->sum('grandtotal');
        $comparisonOrderCount = $comparisonOrders->count();
        $revenueDelta = $comparisonRevenue > 0 ? round((($grossRevenue - $comparisonRevenue) / $comparisonRevenue) * 100, 1) : null;
        $orderDelta = $comparisonOrderCount > 0 ? round((($totalOrders - $comparisonOrderCount) / $comparisonOrderCount) * 100, 1) : null;

        $hourlyData = [];
        if ($range === 'daily') {
            $rawHourly = Order::query()
                ->selectRaw('HOUR(created_at) as hour_slot, COUNT(*) as total_orders, COALESCE(SUM(grandtotal), 0) as revenue')
                ->where('tenant_id', $tenantId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('hour_slot')
                ->orderBy('hour_slot')
                ->get()
                ->keyBy('hour_slot');

            for ($hour = 0; $hour < 24; $hour++) {
                $record = $rawHourly->get($hour);
                $hourlyData[] = [
                    'label' => str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . '.00',
                    'orders' => (int) ($record->total_orders ?? 0),
                    'revenue' => (int) ($record->revenue ?? 0),
                ];
            }
        }

        $topMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->select('items.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.total_price) as total_revenue'))
            ->where('orders.tenant_id', $tenantId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $paymentBreakdown = [
            ['label' => 'Cash', 'count' => $cashOrders, 'color' => '#f59e0b'],
            ['label' => 'QRIS', 'count' => $qrisOrders, 'color' => '#3b82f6'],
        ];

        $statusBreakdown = [
            ['label' => 'Pending', 'count' => $pendingOrders, 'color' => '#f59e0b'],
            ['label' => 'Settlement', 'count' => $settledOrders, 'color' => '#22c55e'],
            ['label' => 'Cooked', 'count' => $cookedOrders, 'color' => '#8b5cf6'],
        ];

        return [
            'tenant' => $tenant,
            'range' => $range,
            'rangeLabel' => $rangeLabel,
            'anchorDate' => $anchorDate,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'grossRevenue' => $grossRevenue,
            'settledOrders' => $settledOrders,
            'pendingOrders' => $pendingOrders,
            'cookedOrders' => $cookedOrders,
            'averageOrderValue' => $averageOrderValue,
            'cashOrders' => $cashOrders,
            'qrisOrders' => $qrisOrders,
            'comparisonRevenue' => $comparisonRevenue,
            'comparisonOrderCount' => $comparisonOrderCount,
            'revenueDelta' => $revenueDelta,
            'orderDelta' => $orderDelta,
            'topMenus' => $topMenus,
            'paymentBreakdown' => $paymentBreakdown,
            'statusBreakdown' => $statusBreakdown,
            'hourlyData' => $hourlyData,
        ];
    }
}

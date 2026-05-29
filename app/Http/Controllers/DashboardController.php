<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = $this->requireTenant()->id;

        $stats = Cache::remember('tenant.' . $tenantId . '.dashboard.stats', now()->addSeconds(30), function () use ($tenantId) {
            // Total order + revenue dalam satu query
            $totals = Order::where('tenant_id', $tenantId)
                ->selectRaw('COUNT(*) as orders, COALESCE(SUM(grandtotal), 0) as revenue')
                ->first();
            $totalOrders = (int) $totals->orders;
            $totalRevenue = (int) $totals->revenue;

            $today = now()->toDateString();
            $todayStats = Order::where('tenant_id', $tenantId)
                ->whereDate('created_at', $today)
                ->selectRaw('COUNT(*) as orders, COALESCE(SUM(grandtotal), 0) as revenue')
                ->first();
            $todayOrders = (int) $todayStats->orders;
            $todayRevenue = (int) $todayStats->revenue;

            $year = now()->year;
            $month = now()->month;
            $lastMonthDate = now()->subMonth();
            $lastYear = $lastMonthDate->year;
            $lastMonth = $lastMonthDate->month;

            $monthlyStats = Order::where('tenant_id', $tenantId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->selectRaw('COUNT(*) as orders, COALESCE(SUM(grandtotal), 0) as revenue')
                ->first();
            $monthlyOrders = (int) $monthlyStats->orders;
            $monthlyRevenue = (int) $monthlyStats->revenue;
            $monthlyFee = $monthlyOrders * 1000;

            $lastMonthlyStats = Order::where('tenant_id', $tenantId)
                ->whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $lastMonth)
                ->selectRaw('COUNT(*) as orders, COALESCE(SUM(grandtotal), 0) as revenue')
                ->first();
            $lastMonthlyOrders = (int) $lastMonthlyStats->orders;
            $lastMonthlyRevenue = (int) $lastMonthlyStats->revenue;
            $lastMonthlyFee = $lastMonthlyOrders * 1000;

            // Hitung semua status sekaligus dalam satu query grouped
            $statusCounts = Order::where('tenant_id', $tenantId)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $pendingCount = (int) ($statusCounts['pending'] ?? 0);
            $settlementCount = (int) ($statusCounts['settlement'] ?? 0);
            $cookedCount = (int) ($statusCounts['cooked'] ?? 0);

            $topMenus = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('items', 'order_items.item_id', '=', 'items.id')
                ->select('items.name as menu_name', DB::raw('SUM(order_items.quantity) as total_qty'))
                ->where('orders.tenant_id', $tenantId)
                ->groupBy('order_items.item_id', 'items.name')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            $topMenuNames = $topMenus->pluck('menu_name')->toArray();
            $topMenuQty = $topMenus->pluck('total_qty')->toArray();

            $trendLabels = [];
            $trendData = [];
            $revenueLabels = [];
            $revenueData = [];

            // Tren 7 hari: count + revenue digabung dalam satu query GROUP BY
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $dailyStats = Order::where('tenant_id', $tenantId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(grandtotal), 0) as revenue')
                ->groupByRaw('DATE(created_at)')
                ->get()
                ->keyBy('date');

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->toDateString();
                $row = $dailyStats[$dateStr] ?? null;
                $trendLabels[] = $date->format('d M');
                $trendData[] = (int) ($row->count ?? 0);
                $revenueLabels[] = $date->format('d M');
                $revenueData[] = (int) ($row->revenue ?? 0);
            }

            return compact(
                'totalOrders',
                'totalRevenue',
                'todayOrders',
                'todayRevenue',
                'monthlyOrders',
                'monthlyRevenue',
                'monthlyFee',
                'pendingCount',
                'settlementCount',
                'cookedCount',
                'lastMonthlyOrders',
                'lastMonthlyRevenue',
                'lastMonthlyFee',
                'topMenuNames',
                'topMenuQty',
                'trendLabels',
                'trendData',
                'revenueLabels',
                'revenueData'
            );
        });

        return view('admin.dashboard', $stats);
    }
}

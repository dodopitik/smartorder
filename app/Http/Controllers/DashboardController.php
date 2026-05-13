<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = $this->requireTenant()->id;

        $totalOrders = Order::where('tenant_id', $tenantId)->count();
        $totalRevenue = Order::where('tenant_id', $tenantId)->sum('grandtotal');

        $today = now()->toDateString();
        $todayOrders = Order::where('tenant_id', $tenantId)->whereDate('created_at', $today)->count();
        $todayRevenue = Order::where('tenant_id', $tenantId)->whereDate('created_at', $today)->sum('grandtotal');

        $year = now()->year;
        $month = now()->month;
        $lastMonthDate = now()->subMonth();
        $lastYear = $lastMonthDate->year;
        $lastMonth = $lastMonthDate->month;

        $monthlyOrders = Order::where('tenant_id', $tenantId)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $monthlyRevenue = Order::where('tenant_id', $tenantId)->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('grandtotal');
        $monthlyFee = $monthlyOrders * 1000;

        $lastMonthlyOrders = Order::where('tenant_id', $tenantId)->whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->count();
        $lastMonthlyRevenue = Order::where('tenant_id', $tenantId)->whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->sum('grandtotal');
        $lastMonthlyFee = $lastMonthlyOrders * 1000;

        $pendingCount = Order::where('tenant_id', $tenantId)->where('status', 'pending')->count();
        $settlementCount = Order::where('tenant_id', $tenantId)->where('status', 'settlement')->count();
        $cookedCount = Order::where('tenant_id', $tenantId)->where('status', 'cooked')->count();

        $topMenus = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->select('items.name as menu_name', \DB::raw('SUM(order_items.quantity) as total_qty'))
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

        // Optimized: single query for 7-day trend instead of 14 individual queries
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dailyStats = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(grandtotal), 0) as revenue')
            ->groupByRaw('DATE(created_at)')
            ->pluck('revenue', 'date')
            ->toArray();

        $dailyCounts = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->pluck('count', 'date')
            ->toArray();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->toDateString();
            $trendLabels[] = $date->format('d M');
            $trendData[] = $dailyCounts[$dateStr] ?? 0;
            $revenueLabels[] = $date->format('d M');
            $revenueData[] = (int) ($dailyStats[$dateStr] ?? 0);
        }

        return view('admin.dashboard', compact(
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
        ));
    }
}

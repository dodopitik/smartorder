<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\View\View;

class PlatformDashboardController extends Controller
{
    public function index(): View
    {
        $activeTenants = Tenant::query()->where('is_active', true)->count();
        $inactiveTenants = Tenant::query()->where('is_active', false)->count();
        $tenantAdmins = User::query()
            ->whereHas('role', fn ($query) => $query->where('role_name', 'admin'))
            ->count();
        $totalOrders = Order::query()->count();
        $totalRevenue = (int) Order::query()->sum('grandtotal');
        $todayRevenue = (int) Order::query()->whereDate('created_at', now()->toDateString())->sum('grandtotal');
        $monthlyGrossRevenue = (int) Order::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', 'settlement')
            ->sum('grandtotal');
        $platformRevenueBase = (int) AppSetting::getValue('monthly_fee_per_order', '1000');
        // Sum the snapshotted fee per order so historical orders keep their
        // own fee even after the global setting changes.
        $estimatedMonthlyFee = (int) Order::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', 'settlement')
            ->sum('platform_fee');
        $averageOrderValue = $totalOrders > 0 ? (int) round($totalRevenue / $totalOrders) : 0;

        $tenantSnapshots = Tenant::query()
            ->withCount([
                'orders',
                'items',
                'users as staff_count' => fn ($query) => $query->whereHas('role', fn ($roleQuery) => $roleQuery->whereIn('role_name', ['admin', 'cashier', 'chef'])),
            ])
            ->withSum('orders', 'grandtotal')
            ->orderByDesc('orders_count')
            ->limit(6)
            ->get();

        $recentTenants = Tenant::query()
            ->latest('created_at')
            ->limit(5)
            ->get();

        $topRevenueTenants = Tenant::query()
            ->withSum('orders', 'grandtotal')
            ->orderByDesc('orders_sum_grandtotal')
            ->limit(5)
            ->get();

        return view('platform.dashboard', [
            'activeTenants' => $activeTenants,
            'inactiveTenants' => $inactiveTenants,
            'tenantAdmins' => $tenantAdmins,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'monthlyGrossRevenue' => $monthlyGrossRevenue,
            'estimatedMonthlyFee' => $estimatedMonthlyFee,
            'averageOrderValue' => $averageOrderValue,
            'platformName' => AppSetting::getValue('platform_name', 'Archana Order'),
            'heroMessage' => AppSetting::getValue('hero_message', 'Kelola banyak tenant dari satu panel.'),
            'tenantSnapshots' => $tenantSnapshots,
            'recentTenants' => $recentTenants,
            'topRevenueTenants' => $topRevenueTenants,
        ]);
    }
}

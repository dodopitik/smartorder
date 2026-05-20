<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlatformBillingController extends Controller
{
    public function index(): View
    {
        $feePerOrder = (int) AppSetting::getValue('monthly_fee_per_order', '1000');
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $billings = Tenant::query()
            ->leftJoin('orders', function ($join) use ($currentYear, $currentMonth): void {
                $join->on('tenants.id', '=', 'orders.tenant_id')
                    ->whereYear('orders.created_at', $currentYear)
                    ->whereMonth('orders.created_at', $currentMonth)
                    ->whereNull('orders.deleted_at')
                    ->where('orders.status', 'settlement');
            })
            ->groupBy('tenants.id', 'tenants.name', 'tenants.slug')
            ->orderBy('tenants.name')
            ->get([
                'tenants.id',
                'tenants.name',
                'tenants.slug',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('COALESCE(SUM(orders.grandtotal), 0) as gross_revenue'),
                DB::raw('COALESCE(SUM(orders.platform_fee), 0) as platform_fee'),
            ]);

        $grossRevenue = (int) $billings->sum('gross_revenue');
        $platformFeeTotal = (int) $billings->sum('platform_fee');
        $orderCountTotal = (int) $billings->sum('order_count');

        return view('platform.billing.index', [
            'billings' => $billings,
            'feePerOrder' => $feePerOrder,
            'billingNote' => AppSetting::getValue('billing_cycle_note', ''),
            'grossRevenue' => $grossRevenue,
            'platformFeeTotal' => $platformFeeTotal,
            'orderCountTotal' => $orderCountTotal,
        ]);
    }
}

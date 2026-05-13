<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformDisbursementController extends Controller
{
    public function index(Request $request): View
    {
        $feePerOrder = (int) AppSetting::getValue('monthly_fee_per_order', '1000');

        // Ambil semua order QRIS settlement, grouped per tenant
        $tenants = Tenant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($tenant) use ($feePerOrder) {
                $orders = Order::query()
                    ->where('tenant_id', $tenant->id)
                    ->where('payment_method', 'qris')
                    ->where('status', 'settlement')
                    ->with('user')
                    ->orderByDesc('created_at')
                    ->get();

                $pendingOrders = $orders->where('is_disbursed', false);
                $disbursedOrders = $orders->where('is_disbursed', true);

                return (object) [
                    'tenant' => $tenant,
                    'pending_orders' => $pendingOrders,
                    'disbursed_orders' => $disbursedOrders,
                    'pending_total' => (int) $pendingOrders->sum('grandtotal'),
                    'pending_fee' => $pendingOrders->count() * $feePerOrder,
                    'pending_to_disburse' => max(0, (int) $pendingOrders->sum('grandtotal') - ($pendingOrders->count() * $feePerOrder)),
                    'disbursed_total' => (int) $disbursedOrders->sum('grandtotal'),
                ];
            })
            ->filter(fn ($t) => $t->pending_orders->count() > 0 || $t->disbursed_orders->count() > 0)
            ->values();

        $grandPendingTotal = $tenants->sum('pending_to_disburse');
        $grandDisbursedTotal = $tenants->sum('disbursed_total');
        $totalPendingOrders = $tenants->sum(fn ($t) => $t->pending_orders->count());

        return view('platform.billing.disbursement', [
            'tenants' => $tenants,
            'feePerOrder' => $feePerOrder,
            'grandPendingTotal' => $grandPendingTotal,
            'grandDisbursedTotal' => $grandDisbursedTotal,
            'totalPendingOrders' => $totalPendingOrders,
        ]);
    }

    public function markDisbursed(Request $request): RedirectResponse
    {
        $tenantId = $request->input('tenant_id');

        $updated = Order::query()
            ->where('tenant_id', $tenantId)
            ->where('payment_method', 'qris')
            ->where('status', 'settlement')
            ->where('is_disbursed', false)
            ->update([
                'is_disbursed' => true,
                'disbursed_at' => now(),
            ]);

        $tenant = Tenant::find($tenantId);

        return redirect()->back()->with('success', $updated . ' order dari ' . ($tenant->name ?? 'tenant') . ' berhasil ditandai sudah disetor.');
    }

    public function undoDisbursed(Request $request): RedirectResponse
    {
        $tenantId = $request->input('tenant_id');

        Order::query()
            ->where('tenant_id', $tenantId)
            ->where('payment_method', 'qris')
            ->where('is_disbursed', true)
            ->update([
                'is_disbursed' => false,
                'disbursed_at' => null,
            ]);

        return redirect()->back()->with('success', 'Status penyetoran dikembalikan ke belum disetor.');
    }
}

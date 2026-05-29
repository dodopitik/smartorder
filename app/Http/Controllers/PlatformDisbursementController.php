<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlatformDisbursementController extends Controller
{
    public function index(Request $request): View
    {
        $feePerOrder = (int) AppSetting::getValue('monthly_fee_per_order', '1000');

        // Ambil semua tenant aktif + semua order QRIS settlement dalam 2 query saja,
        // lalu kelompokkan di memori (hindari N+1: 1 query per tenant).
        $activeTenants = Tenant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $ordersByTenant = Order::query()
            ->whereIn('tenant_id', $activeTenants->pluck('id'))
            ->where('payment_method', 'qris')
            ->where('status', 'settlement')
            ->with('user')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('tenant_id');

        $tenants = $activeTenants
            ->map(function ($tenant) use ($ordersByTenant) {
                $orders = $ordersByTenant->get($tenant->id) ?? collect();

                $pendingOrders = $orders->where('is_disbursed', false);
                $disbursedOrders = $orders->where('is_disbursed', true);

                $pendingTotal = (int) $pendingOrders->sum('grandtotal');
                $pendingFee = (int) $pendingOrders->sum('platform_fee');

                // Cari batch terbaru yang sudah disetor agar tombol "Reset"
                // hanya membatalkan batch tersebut, bukan semua histori.
                $latestBatch = $disbursedOrders
                    ->whereNotNull('disbursement_batch_id')
                    ->sortByDesc('disbursed_at')
                    ->first();

                $latestBatchOrders = $latestBatch
                    ? $disbursedOrders->where('disbursement_batch_id', $latestBatch->disbursement_batch_id)
                    : collect();

                return (object) [
                    'tenant' => $tenant,
                    'pending_orders' => $pendingOrders,
                    'disbursed_orders' => $disbursedOrders,
                    'pending_total' => $pendingTotal,
                    'pending_fee' => $pendingFee,
                    'pending_to_disburse' => max(0, $pendingTotal - $pendingFee),
                    'disbursed_total' => (int) $disbursedOrders->sum('grandtotal'),
                    'latest_batch_id' => $latestBatch?->disbursement_batch_id,
                    'latest_batch_count' => $latestBatchOrders->count(),
                    'latest_batch_total' => (int) $latestBatchOrders->sum('grandtotal'),
                    'latest_batch_at' => $latestBatch?->disbursed_at,
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

        // Identifier unik per batch agar bisa di-undo per kelompok.
        $batchId = (string) Str::ulid();

        $updated = Order::query()
            ->where('tenant_id', $tenantId)
            ->where('payment_method', 'qris')
            ->where('status', 'settlement')
            ->where('is_disbursed', false)
            ->update([
                'is_disbursed' => true,
                'disbursed_at' => now(),
                'disbursement_batch_id' => $batchId,
            ]);

        $tenant = Tenant::find($tenantId);

        return redirect()->back()->with('success', $updated . ' order dari ' . ($tenant->name ?? 'tenant') . ' berhasil ditandai sudah disetor.');
    }

    public function undoDisbursed(Request $request): RedirectResponse
    {
        $tenantId = $request->input('tenant_id');

        // Cari batch paling akhir untuk tenant ini, lalu rollback hanya batch
        // tersebut. Histori batch sebelumnya tetap aman.
        $latest = Order::query()
            ->where('tenant_id', $tenantId)
            ->where('payment_method', 'qris')
            ->where('is_disbursed', true)
            ->whereNotNull('disbursement_batch_id')
            ->orderByDesc('disbursed_at')
            ->first();

        if (! $latest) {
            return redirect()->back()->with('error', 'Tidak ada batch penyetoran yang bisa direset.');
        }

        $reverted = Order::query()
            ->where('tenant_id', $tenantId)
            ->where('disbursement_batch_id', $latest->disbursement_batch_id)
            ->update([
                'is_disbursed' => false,
                'disbursed_at' => null,
                'disbursement_batch_id' => null,
            ]);

        return redirect()->back()->with('success', $reverted . ' order pada batch terakhir dikembalikan ke status belum disetor.');
    }
}

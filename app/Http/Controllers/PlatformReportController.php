<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PlatformReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->query('period', 'daily');
        $tenantId = $request->query('tenant_id');
        $date = $request->query('date', now()->toDateString());

        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($period, $date);

        $query = Order::query()
            ->with(['tenant', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $orders = $query->orderByDesc('created_at')->get();

        // Summary stats
        $totalOrders = $orders->count();
        $totalRevenue = (int) $orders->sum('grandtotal');
        $totalTax = (int) $orders->sum('tax');
        $totalPlatformFee = (int) $orders->where('status', 'settlement')->sum('platform_fee');
        $avgOrderValue = $totalOrders > 0 ? (int) round($totalRevenue / $totalOrders) : 0;
        $cashOrders = $orders->where('payment_method', 'cash')->count();
        $qrisOrders = $orders->where('payment_method', 'qris')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $settlementOrders = $orders->where('status', 'settlement')->count();
        $cookedOrders = $orders->where('status', 'cooked')->count();

        // Per-tenant breakdown
        $tenantBreakdown = $orders->groupBy('tenant_id')->map(function ($group) {
            return [
                'tenant_name' => $group->first()->tenant?->name ?? 'Unknown',
                'order_count' => $group->count(),
                'revenue' => (int) $group->sum('grandtotal'),
                'tax' => (int) $group->sum('tax'),
                'platform_fee' => (int) $group->where('status', 'settlement')->sum('platform_fee'),
            ];
        })->sortByDesc('revenue')->values();

        $tenants = Tenant::query()->where('is_active', true)->orderBy('name')->get();

        return view('platform.reports.index', [
            'orders' => $orders,
            'period' => $period,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date' => $date,
            'tenantId' => $tenantId,
            'tenants' => $tenants,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalTax' => $totalTax,
            'totalPlatformFee' => $totalPlatformFee,
            'avgOrderValue' => $avgOrderValue,
            'cashOrders' => $cashOrders,
            'qrisOrders' => $qrisOrders,
            'pendingOrders' => $pendingOrders,
            'settlementOrders' => $settlementOrders,
            'cookedOrders' => $cookedOrders,
            'tenantBreakdown' => $tenantBreakdown,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $period = $request->query('period', 'daily');
        $tenantId = $request->query('tenant_id');
        $date = $request->query('date', now()->toDateString());

        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($period, $date);

        $query = Order::query()
            ->with(['tenant', 'user', 'orderItems.item'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $orders = $query->orderByDesc('created_at')->get();

        $filename = 'laporan-archana-order-' . $period . '-' . $date . '.csv';

        return response()->streamDownload(function () use ($orders, $periodLabel, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Use semicolon delimiter for better Excel compatibility
            $sep = ';';
            $blank = ['']; // PHP 8.4 deprecates empty array on fputcsv

            // === HEADER INFO ===
            fputcsv($handle, ['LAPORAN ARCHANA ORDER'], $sep);
            fputcsv($handle, ['Periode', $periodLabel], $sep);
            fputcsv($handle, ['Tanggal Mulai', Carbon::parse($startDate)->format('d/m/Y')], $sep);
            fputcsv($handle, ['Tanggal Akhir', Carbon::parse($endDate)->format('d/m/Y')], $sep);
            fputcsv($handle, ['Diekspor Pada', now()->format('d/m/Y H:i:s')], $sep);
            fputcsv($handle, $blank, $sep);

            // === RINGKASAN ===
            $settlementOrders = $orders->where('status', 'settlement');
            fputcsv($handle, ['RINGKASAN', 'Nilai'], $sep);
            fputcsv($handle, ['Total Pesanan', $orders->count()], $sep);
            fputcsv($handle, ['Total Revenue (Rp)', (int) $orders->sum('grandtotal')], $sep);
            fputcsv($handle, ['Total Subtotal (Rp)', (int) $orders->sum('subtotal')], $sep);
            fputcsv($handle, ['Total Pajak (Rp)', (int) $orders->sum('tax')], $sep);
            fputcsv($handle, ['Total Platform Fee (Rp)', (int) $settlementOrders->sum('platform_fee')], $sep);
            fputcsv($handle, ['Pesanan Cash', $orders->where('payment_method', 'cash')->count()], $sep);
            fputcsv($handle, ['Pesanan QRIS', $orders->where('payment_method', 'qris')->count()], $sep);
            fputcsv($handle, ['Status Pending', $orders->where('status', 'pending')->count()], $sep);
            fputcsv($handle, ['Status Settlement', $settlementOrders->count()], $sep);
            fputcsv($handle, ['Status Cooked', $orders->where('status', 'cooked')->count()], $sep);
            fputcsv($handle, $blank, $sep);

            // === BREAKDOWN PER TENANT ===
            $tenantGroups = $orders->groupBy(fn ($o) => $o->tenant?->name ?? 'Unknown');
            if ($tenantGroups->count() > 1) {
                fputcsv($handle, ['BREAKDOWN PER TENANT', 'Jumlah Order', 'Revenue (Rp)', 'Pajak (Rp)', 'Platform Fee (Rp)'], $sep);
                foreach ($tenantGroups as $tenantName => $group) {
                    fputcsv($handle, [
                        $tenantName,
                        $group->count(),
                        (int) $group->sum('grandtotal'),
                        (int) $group->sum('tax'),
                        (int) $group->where('status', 'settlement')->sum('platform_fee'),
                    ], $sep);
                }
                fputcsv($handle, $blank, $sep);
            }

            // === DETAIL PESANAN ===
            fputcsv($handle, [
                'No',
                'Kode Pesanan',
                'Tanggal',
                'Jam',
                'Tenant',
                'Pelanggan',
                'No. Kamar',
                'Subtotal (Rp)',
                'Pajak (Rp)',
                'Grand Total (Rp)',
                'Platform Fee (Rp)',
                'Metode Bayar',
                'Status',
                'Item Dipesan',
                'Catatan',
            ], $sep);

            foreach ($orders as $i => $order) {
                // Gabungkan item pesanan jadi satu string
                $itemList = $order->orderItems->map(function ($oi) {
                    $name = $oi->item?->name ?? 'Item';
                    return $name . ' x' . $oi->quantity;
                })->implode(', ');

                fputcsv($handle, [
                    $i + 1,
                    $order->order_code,
                    $order->created_at->format('d/m/Y'),
                    $order->created_at->format('H:i'),
                    $order->tenant?->name ?? '-',
                    $order->user?->fullname ?? '-',
                    $order->table_number ?: '-',
                    $order->subtotal,
                    $order->tax,
                    $order->grandtotal,
                    $order->status === 'settlement' ? (int) $order->platform_fee : 0,
                    strtoupper($order->payment_method),
                    ucfirst($order->status),
                    $itemList,
                    $order->notes ?? '-',
                ], $sep);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function resolvePeriod(string $period, string $date): array
    {
        $baseDate = Carbon::parse($date);

        return match ($period) {
            'weekly' => [
                $baseDate->copy()->startOfWeek(),
                $baseDate->copy()->endOfWeek(),
                'Mingguan (' . $baseDate->copy()->startOfWeek()->format('d M') . ' - ' . $baseDate->copy()->endOfWeek()->format('d M Y') . ')',
            ],
            'monthly' => [
                $baseDate->copy()->startOfMonth(),
                $baseDate->copy()->endOfMonth(),
                'Bulanan (' . $baseDate->copy()->format('F Y') . ')',
            ],
            default => [
                $baseDate->copy()->startOfDay(),
                $baseDate->copy()->endOfDay(),
                'Harian (' . $baseDate->copy()->format('d M Y') . ')',
            ],
        };
    }
}

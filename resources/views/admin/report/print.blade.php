<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rangeLabel }} - {{ $tenant->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            color: #111827;
            font-size: 13px;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #4b5563;
            line-height: 1.6;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin: 18px 0 22px;
        }

        .kpi-card {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 12px;
        }

        .kpi-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 20px;
            font-weight: 700;
        }

        h2 {
            font-size: 16px;
            margin: 22px 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-size: 12px;
        }

        .list {
            margin: 0;
            padding-left: 18px;
        }

        .print-note {
            margin-top: 18px;
            color: #6b7280;
            font-size: 12px;
        }

        @media print {
            body {
                margin: 12px;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <div class="title">{{ $rangeLabel }}</div>
        <div class="subtitle">
            Tenant: <strong>{{ $tenant->name }}</strong><br>
            Periode: {{ $startDate->translatedFormat('d M Y') }} - {{ $endDate->translatedFormat('d M Y') }}<br>
            Dicetak pada: {{ now()->translatedFormat('d M Y H:i') }}
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Total Order</div>
            <div class="kpi-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Gross Revenue</div>
            <div class="kpi-value">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Order Settlement</div>
            <div class="kpi-value">{{ number_format($settledOrders, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Rata-rata Order</div>
            <div class="kpi-value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>Ringkasan Status</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($statusBreakdown as $status)
                <tr>
                    <td>{{ $status['label'] }}</td>
                    <td>{{ number_format($status['count'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Metode Pembayaran</h2>
    <table>
        <thead>
            <tr>
                <th>Metode</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paymentBreakdown as $payment)
                <tr>
                    <td>{{ $payment['label'] }}</td>
                    <td>{{ number_format($payment['count'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Menu Terlaris</h2>
    @if ($topMenus->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Porsi</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topMenus as $menu)
                    <tr>
                        <td>{{ $menu->name }}</td>
                        <td>{{ number_format($menu->total_qty, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div>Tidak ada data menu terlaris pada periode ini.</div>
    @endif

    <h2>Detail Order</h2>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Waktu</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->user?->fullname ?? '-' }}</td>
                    <td>{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="print-note">Dokumen ini dibuat otomatis dari modul laporan tenant.</div>
</body>

</html>

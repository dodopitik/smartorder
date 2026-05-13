@extends('admin.layout.master')
@section('title', 'Laporan Tenant')

@section('css')
    <style>
        .report-shell {
            display: grid;
            gap: 1.2rem;
        }

        .report-hero,
        .report-card {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .report-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.16), transparent 24%),
                linear-gradient(135deg, rgba(255, 247, 237, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(239, 246, 255, 0.92) 100%);
        }

        .report-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            color: #334155;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 800;
        }

        .report-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .report-copy {
            color: #64748b;
            max-width: 760px;
            line-height: 1.7;
            margin: 0;
        }

        .report-filter-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 1.2rem;
        }

        .report-tabs {
            display: inline-flex;
            gap: .55rem;
            padding: .35rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
        }

        .report-tab {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .75rem 1rem;
            border-radius: 999px;
            color: #475569;
            text-decoration: none;
            font-weight: 700;
        }

        .report-tab.active {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.18);
        }

        .report-date-form {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            align-items: center;
        }

        .report-date-form .form-control {
            min-width: 180px;
            border-radius: 16px;
        }

        .report-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem;
        }

        .report-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.2rem;
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .report-kpi::after {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 999px;
            background: var(--glow, rgba(249, 115, 22, 0.12));
        }

        .report-kpi-label {
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .4rem;
            position: relative;
            z-index: 1;
        }

        .report-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .report-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            margin: .45rem 0 0;
            font-size: .82rem;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 1rem;
        }

        .report-card {
            padding: 1.2rem;
        }

        .report-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .report-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .report-card-copy {
            color: #64748b;
            margin: .25rem 0 0;
            line-height: 1.65;
            font-size: .9rem;
        }

        .report-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .38rem .7rem;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.12);
            color: #c2410c;
            font-size: .74rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .report-list {
            display: grid;
            gap: .85rem;
        }

        .report-row {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: .8rem;
            align-items: center;
        }

        .report-dot {
            width: 11px;
            height: 11px;
            border-radius: 999px;
        }

        .report-row strong {
            display: block;
        }

        .report-row span {
            display: block;
            color: #64748b;
            font-size: .82rem;
        }

        .report-count {
            font-weight: 800;
        }

        .report-track {
            grid-column: 2 / 4;
            height: 10px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.14);
            overflow: hidden;
        }

        .report-fill {
            height: 100%;
            border-radius: inherit;
        }

        .report-table-wrap {
            overflow: auto;
        }

        .report-table {
            width: 100%;
            min-width: 760px;
        }

        .report-table th {
            color: #64748b;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .report-table td,
        .report-table th {
            padding: .9rem .8rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .report-table tbody tr:last-child td {
            border-bottom: none;
        }

        .report-status {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .65rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
        }

        .report-status.pending {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .report-status.settlement {
            background: rgba(34, 197, 94, 0.14);
            color: #15803d;
        }

        .report-status.cooked {
            background: rgba(139, 92, 246, 0.14);
            color: #6d28d9;
        }

        .report-top-list {
            display: grid;
            gap: .8rem;
        }

        .report-top-item {
            padding: .95rem 1rem;
            border-radius: 18px;
            background: rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .report-top-item strong {
            display: block;
        }

        .report-top-meta {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: .35rem;
            color: #64748b;
            font-size: .84rem;
        }

        .report-hour-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: .75rem;
        }

        .report-hour-card {
            border-radius: 18px;
            background: rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.1);
            padding: .9rem;
        }

        .report-hour-card .label {
            color: #64748b;
            font-size: .82rem;
        }

        .report-hour-card .value {
            font-weight: 800;
            margin: .3rem 0 0;
        }

        html[data-bs-theme="dark"] .report-hero,
        html[data-bs-theme="dark"] .report-card,
        html[data-bs-theme="dark"] .report-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .report-hero {
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .report-eyebrow,
        html[data-bs-theme="dark"] .report-tabs {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .report-title,
        html[data-bs-theme="dark"] .report-kpi-value,
        html[data-bs-theme="dark"] .report-card-title,
        html[data-bs-theme="dark"] .report-row strong,
        html[data-bs-theme="dark"] .report-top-item strong,
        html[data-bs-theme="dark"] .report-hour-card .value {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .report-copy,
        html[data-bs-theme="dark"] .report-kpi-label,
        html[data-bs-theme="dark"] .report-kpi-note,
        html[data-bs-theme="dark"] .report-card-copy,
        html[data-bs-theme="dark"] .report-row span,
        html[data-bs-theme="dark"] .report-top-meta,
        html[data-bs-theme="dark"] .report-hour-card .label,
        html[data-bs-theme="dark"] .report-table th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .report-top-item,
        html[data-bs-theme="dark"] .report-hour-card {
            background: rgba(148, 163, 184, 0.08);
            border-color: rgba(148, 163, 184, 0.12);
        }

        html[data-bs-theme="dark"] .report-date-form .form-control {
            background: #111c34 !important;
            color: #e2e8f0 !important;
            border-color: rgba(148, 163, 184, 0.18) !important;
        }

        @media (max-width: 991.98px) {
            .report-grid {
                grid-template-columns: 1fr;
            }

            .report-filter-bar {
                align-items: stretch;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $statusTotal = max($pendingOrders + $settledOrders + $cookedOrders, 1);
        $paymentTotal = max($cashOrders + $qrisOrders, 1);
    @endphp

    <div class="report-shell">
        <section class="report-hero">
            <span class="report-eyebrow"><i class="bi bi-journal-text"></i> Reporting Center</span>
            <h1 class="report-title">{{ $rangeLabel }} - {{ $tenant->name }}</h1>
            <p class="report-copy">
                Ringkasan laporan tenant untuk periode {{ $startDate->translatedFormat('d M Y') }}
                sampai {{ $endDate->translatedFormat('d M Y') }}. Gunakan filter di bawah untuk
                beralih dari laporan harian, mingguan, sampai bulanan lalu cetak hasilnya.
            </p>

            <div class="report-filter-bar">
                <div class="report-tabs">
                    <a href="{{ route('reports.index', ['tenant' => $tenant->slug, 'range' => 'daily', 'date' => $anchorDate->toDateString()]) }}"
                        class="report-tab {{ $range === 'daily' ? 'active' : '' }}">
                        <i class="bi bi-sunrise"></i> Harian
                    </a>
                    <a href="{{ route('reports.index', ['tenant' => $tenant->slug, 'range' => 'weekly', 'date' => $anchorDate->toDateString()]) }}"
                        class="report-tab {{ $range === 'weekly' ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i> Mingguan
                    </a>
                    <a href="{{ route('reports.index', ['tenant' => $tenant->slug, 'range' => 'monthly', 'date' => $anchorDate->toDateString()]) }}"
                        class="report-tab {{ $range === 'monthly' ? 'active' : '' }}">
                        <i class="bi bi-calendar-month"></i> Bulanan
                    </a>
                </div>

                <form method="GET" action="{{ route('reports.index', ['tenant' => $tenant->slug]) }}" class="report-date-form">
                    <input type="hidden" name="range" value="{{ $range }}">
                    <input type="date" name="date" value="{{ $anchorDate->toDateString() }}" class="form-control">
                    <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Terapkan</button>
                    <a href="{{ route('reports.print', ['tenant' => $tenant->slug, 'range' => $range, 'date' => $anchorDate->toDateString()]) }}"
                        target="_blank" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-printer me-2"></i> Print Laporan
                    </a>
                </form>
            </div>
        </section>

        <section class="report-kpi-grid">
            <article class="report-kpi" style="--glow: rgba(249, 115, 22, 0.12);">
                <div class="report-kpi-label">Total Order</div>
                <p class="report-kpi-value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
                <p class="report-kpi-note">
                    {{ $orderDelta === null ? 'Belum ada pembanding periode sebelumnya.' : ($orderDelta >= 0 ? '+' : '') . $orderDelta . '% dibanding periode sebelumnya.' }}
                </p>
            </article>
            <article class="report-kpi" style="--glow: rgba(59, 130, 246, 0.12);">
                <div class="report-kpi-label">Gross Revenue</div>
                <p class="report-kpi-value">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</p>
                <p class="report-kpi-note">
                    {{ $revenueDelta === null ? 'Belum ada pembanding revenue sebelumnya.' : ($revenueDelta >= 0 ? '+' : '') . $revenueDelta . '% dibanding periode sebelumnya.' }}
                </p>
            </article>
            <article class="report-kpi" style="--glow: rgba(34, 197, 94, 0.12);">
                <div class="report-kpi-label">Order Terselesaikan</div>
                <p class="report-kpi-value">{{ number_format($settledOrders, 0, ',', '.') }}</p>
                <p class="report-kpi-note">Status settlement selama periode laporan.</p>
            </article>
            <article class="report-kpi" style="--glow: rgba(168, 85, 247, 0.12);">
                <div class="report-kpi-label">Rata-rata Nilai Order</div>
                <p class="report-kpi-value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                <p class="report-kpi-note">Rata-rata nilai transaksi tiap order.</p>
            </article>
        </section>

        <section class="report-grid">
            <article class="report-card">
                <div class="report-card-header">
                    <div>
                        <h3 class="report-card-title">Komposisi Status Pesanan</h3>
                        <p class="report-card-copy">Lihat pembagian status order untuk membaca ritme operasional tenant.</p>
                    </div>
                    <span class="report-chip"><i class="bi bi-pie-chart-fill"></i> Status</span>
                </div>
                <div class="report-list">
                    @foreach ($statusBreakdown as $status)
                        <div class="report-row">
                            <span class="report-dot" style="background: {{ $status['color'] }};"></span>
                            <div>
                                <strong>{{ $status['label'] }}</strong>
                                <span>{{ round(($status['count'] / $statusTotal) * 100) }}% dari total laporan</span>
                            </div>
                            <span class="report-count">{{ number_format($status['count'], 0, ',', '.') }}</span>
                            <div class="report-track">
                                <div class="report-fill"
                                    style="width: {{ round(($status['count'] / $statusTotal) * 100) }}%; background: {{ $status['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="report-card">
                <div class="report-card-header">
                    <div>
                        <h3 class="report-card-title">Metode Pembayaran</h3>
                        <p class="report-card-copy">Melihat dominasi cash atau QRIS selama periode yang dipilih.</p>
                    </div>
                    <span class="report-chip"><i class="bi bi-wallet2"></i> Payment mix</span>
                </div>
                <div class="report-list">
                    @foreach ($paymentBreakdown as $payment)
                        <div class="report-row">
                            <span class="report-dot" style="background: {{ $payment['color'] }};"></span>
                            <div>
                                <strong>{{ $payment['label'] }}</strong>
                                <span>{{ round(($payment['count'] / $paymentTotal) * 100) }}% dari pembayaran</span>
                            </div>
                            <span class="report-count">{{ number_format($payment['count'], 0, ',', '.') }}</span>
                            <div class="report-track">
                                <div class="report-fill"
                                    style="width: {{ round(($payment['count'] / $paymentTotal) * 100) }}%; background: {{ $payment['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        @if ($range === 'daily')
            <section class="report-card">
                <div class="report-card-header">
                    <div>
                        <h3 class="report-card-title">Snapshot Per Jam</h3>
                        <p class="report-card-copy">Cocok untuk laporan harian agar tim bisa membaca jam-jam sibuk tenant.</p>
                    </div>
                    <span class="report-chip"><i class="bi bi-clock-history"></i> Harian</span>
                </div>
                <div class="report-hour-grid">
                    @foreach ($hourlyData as $hour)
                        <div class="report-hour-card">
                            <div class="label">{{ $hour['label'] }}</div>
                            <p class="value">{{ $hour['orders'] }} order</p>
                            <div class="label">Rp {{ number_format($hour['revenue'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="report-grid">
            <article class="report-card">
                <div class="report-card-header">
                    <div>
                        <h3 class="report-card-title">Order Detail</h3>
                        <p class="report-card-copy">Daftar transaksi selama periode laporan untuk keperluan audit cepat.</p>
                    </div>
                    <span class="report-chip"><i class="bi bi-receipt"></i> {{ $orders->count() }} transaksi</span>
                </div>
                <div class="report-table-wrap">
                    <table class="report-table">
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
                                    <td><strong>{{ $order->order_code }}</strong></td>
                                    <td>{{ $order->user?->fullname ?? '-' }}</td>
                                    <td>{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                                    <td>{{ strtoupper($order->payment_method) }}</td>
                                    <td>
                                        <span class="report-status {{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="report-card">
                <div class="report-card-header">
                    <div>
                        <h3 class="report-card-title">Menu Terlaris</h3>
                        <p class="report-card-copy">Lima menu dengan volume penjualan tertinggi pada periode terpilih.</p>
                    </div>
                    <span class="report-chip"><i class="bi bi-fire"></i> Top 5</span>
                </div>
                <div class="report-top-list">
                    @forelse ($topMenus as $menu)
                        <div class="report-top-item">
                            <strong>{{ $menu->name }}</strong>
                            <div class="report-top-meta">
                                <span>{{ number_format($menu->total_qty, 0, ',', '.') }} porsi</span>
                                <span>Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada data menu terlaris untuk periode ini.</div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection

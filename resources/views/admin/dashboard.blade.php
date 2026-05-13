@extends('admin.layout.master')
@section('title', 'Dashboard Tenant')

@section('css')
    <style>
        .tenant-dashboard-shell {
            display: grid;
            gap: 1.25rem;
        }

        .tenant-dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: 1.75rem;
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.18), transparent 25%),
                linear-gradient(135deg, rgba(255, 247, 237, 0.92) 0%, rgba(255, 255, 255, 0.95) 58%, rgba(239, 246, 255, 0.92) 100%);
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.08);
        }

        .tenant-dashboard-hero::after {
            content: "";
            position: absolute;
            inset: auto -80px -90px auto;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.18), transparent 70%);
            pointer-events: none;
        }

        .tenant-dashboard-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            font-size: .78rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 800;
            background: rgba(15, 23, 42, 0.06);
            color: #334155;
        }

        .tenant-dashboard-headline {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .tenant-dashboard-title {
            margin: .85rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .tenant-dashboard-subtitle {
            margin: 0;
            max-width: 720px;
            color: #64748b;
            line-height: 1.7;
        }

        .tenant-dashboard-hero-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .85rem;
            min-width: min(100%, 450px);
        }

        .hero-metric {
            padding: 1rem 1.05rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .hero-metric .label {
            color: #64748b;
            font-size: .85rem;
            margin-bottom: .45rem;
        }

        .hero-metric .value {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
        }

        .tenant-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .tenant-kpi-card {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
            padding: 1.2rem;
        }

        .tenant-kpi-card::after {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--kpi-glow, rgba(249, 115, 22, 0.14));
            opacity: .8;
        }

        .tenant-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .85rem;
            position: relative;
            z-index: 1;
        }

        .tenant-kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--kpi-soft, rgba(249, 115, 22, 0.12));
            color: var(--kpi-color, #c2410c);
            font-size: 1.15rem;
        }

        .tenant-kpi-chip {
            padding: .34rem .65rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            color: #475569;
            font-size: .74rem;
            font-weight: 700;
        }

        .tenant-kpi-label {
            color: #64748b;
            font-size: .9rem;
            margin: 1rem 0 .4rem;
            position: relative;
            z-index: 1;
        }

        .tenant-kpi-value {
            font-size: clamp(1.4rem, 2vw, 2rem);
            font-weight: 900;
            letter-spacing: -.03em;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .tenant-kpi-note {
            position: relative;
            z-index: 1;
            margin: .4rem 0 0;
            color: #64748b;
            font-size: .84rem;
        }

        .tenant-status-band {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 1rem;
        }

        .tenant-panel-card {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
            padding: 1.25rem;
        }

        .tenant-panel-card.chart-panel {
            padding-bottom: 1rem;
        }

        .tenant-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .tenant-panel-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .tenant-panel-copy {
            margin: .25rem 0 0;
            color: #64748b;
            font-size: .9rem;
            line-height: 1.65;
        }

        .tenant-mini-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.12);
            color: #c2410c;
            padding: .38rem .75rem;
            font-size: .74rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .tenant-status-list {
            display: grid;
            gap: .9rem;
        }

        .status-row {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: .8rem;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
        }

        .status-meta strong {
            display: block;
            font-size: .95rem;
        }

        .status-meta span {
            display: block;
            color: #64748b;
            font-size: .82rem;
        }

        .status-count {
            font-weight: 800;
            font-size: 1rem;
        }

        .status-track {
            grid-column: 2 / 4;
            height: 10px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.14);
            overflow: hidden;
        }

        .status-fill {
            height: 100%;
            border-radius: inherit;
        }

        .tenant-chart-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .chart-wrap {
            position: relative;
            min-height: 300px;
        }

        .chart-empty {
            min-height: 300px;
            display: grid;
            place-items: center;
            border-radius: 20px;
            border: 1px dashed rgba(148, 163, 184, 0.22);
            color: #64748b;
            background: rgba(248, 250, 252, 0.7);
            text-align: center;
            padding: 1rem;
        }

        .tenant-billing-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .tenant-fee-card {
            border-radius: 24px;
            padding: 1.2rem;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .tenant-fee-card .label {
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .45rem;
        }

        .tenant-fee-card .value {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .tenant-fee-card .note {
            color: #64748b;
            margin: .45rem 0 0;
            font-size: .84rem;
        }

        html[data-bs-theme="dark"] .tenant-dashboard-hero {
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 58%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 24px 48px rgba(2, 6, 23, 0.4);
        }

        html[data-bs-theme="dark"] .tenant-dashboard-badge,
        html[data-bs-theme="dark"] .tenant-kpi-chip {
            background: rgba(148, 163, 184, 0.14);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .tenant-dashboard-title,
        html[data-bs-theme="dark"] .hero-metric .value,
        html[data-bs-theme="dark"] .tenant-kpi-value,
        html[data-bs-theme="dark"] .tenant-panel-title,
        html[data-bs-theme="dark"] .status-meta strong,
        html[data-bs-theme="dark"] .tenant-fee-card .value {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .tenant-dashboard-subtitle,
        html[data-bs-theme="dark"] .hero-metric .label,
        html[data-bs-theme="dark"] .tenant-kpi-label,
        html[data-bs-theme="dark"] .tenant-kpi-note,
        html[data-bs-theme="dark"] .tenant-panel-copy,
        html[data-bs-theme="dark"] .status-meta span,
        html[data-bs-theme="dark"] .tenant-fee-card .label,
        html[data-bs-theme="dark"] .tenant-fee-card .note {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .hero-metric,
        html[data-bs-theme="dark"] .tenant-kpi-card,
        html[data-bs-theme="dark"] .tenant-panel-card,
        html[data-bs-theme="dark"] .tenant-fee-card {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .tenant-mini-badge {
            background: rgba(251, 146, 60, 0.14);
            color: #fdba74;
        }

        html[data-bs-theme="dark"] .chart-empty {
            background: rgba(15, 23, 42, 0.72);
            color: #94a3b8;
            border-color: rgba(148, 163, 184, 0.16);
        }

        @media (max-width: 991.98px) {
            .tenant-status-band,
            .tenant-chart-grid,
            .tenant-billing-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $statusTotal = max($pendingCount + $settlementCount + $cookedCount, 1);
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $progressToday = $totalOrders > 0 ? round(($todayOrders / max($totalOrders, 1)) * 100) : 0;
        $trendDirection = count($trendData) > 1 ? end($trendData) - $trendData[count($trendData) - 2] : 0;
        $revenueDirection = count($revenueData) > 1 ? end($revenueData) - $revenueData[count($revenueData) - 2] : 0;
        $statusCards = [
            [
                'label' => 'Pending',
                'count' => $pendingCount,
                'color' => '#f59e0b',
                'description' => 'Perlu ditindaklanjuti tim operasional.',
            ],
            [
                'label' => 'Settlement',
                'count' => $settlementCount,
                'color' => '#22c55e',
                'description' => 'Pembayaran sudah diterima.',
            ],
            [
                'label' => 'Cooked',
                'count' => $cookedCount,
                'color' => '#3b82f6',
                'description' => 'Pesanan sudah selesai dimasak.',
            ],
        ];
    @endphp

    <div class="tenant-dashboard-shell">
        <section class="tenant-dashboard-hero">
            <div class="tenant-dashboard-headline">
                <div>
                    <span class="tenant-dashboard-badge">
                        <i class="bi bi-stars"></i>
                        Tenant Performance Hub
                    </span>
                    <h1 class="tenant-dashboard-title">{{ $currentTenant?->name ?? 'Tenant Aktif' }}</h1>
                    <p class="tenant-dashboard-subtitle">
                        Ringkasan operasional untuk tenant aktif. Pantau order yang masuk, pembagian status,
                        performa penjualan 7 hari terakhir, dan biaya layanan tanpa perlu berpindah halaman.
                    </p>
                </div>

                <div class="tenant-dashboard-hero-metrics">
                    <div class="hero-metric">
                        <div class="label">Admin Aktif</div>
                        <p class="value">{{ Auth::user()->fullname }}</p>
                    </div>
                    <div class="hero-metric">
                        <div class="label">Nilai Rata-rata Order</div>
                        <p class="value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                    </div>
                    <div class="hero-metric">
                        <div class="label">Kontribusi Order Hari Ini</div>
                        <p class="value">{{ $progressToday }}%</p>
                    </div>
                    <div class="hero-metric">
                        <div class="label">Revenue Bulan Ini</div>
                        <p class="value">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="tenant-kpi-grid">
            <article class="tenant-kpi-card" style="--kpi-soft: rgba(249, 115, 22, 0.14); --kpi-color: #c2410c; --kpi-glow: rgba(249, 115, 22, 0.12);">
                <div class="tenant-kpi-top">
                    <span class="tenant-kpi-icon"><i class="bi bi-basket2-fill"></i></span>
                    <span class="tenant-kpi-chip">Hari Ini</span>
                </div>
                <div class="tenant-kpi-label">Pesanan Hari Ini</div>
                <p class="tenant-kpi-value">{{ number_format($todayOrders, 0, ',', '.') }}</p>
                <p class="tenant-kpi-note">
                    {{ $trendDirection >= 0 ? '+' . $trendDirection : $trendDirection }} dibanding 1 hari sebelumnya.
                </p>
            </article>

            <article class="tenant-kpi-card" style="--kpi-soft: rgba(59, 130, 246, 0.14); --kpi-color: #2563eb; --kpi-glow: rgba(59, 130, 246, 0.12);">
                <div class="tenant-kpi-top">
                    <span class="tenant-kpi-icon"><i class="bi bi-cash-coin"></i></span>
                    <span class="tenant-kpi-chip">Revenue</span>
                </div>
                <div class="tenant-kpi-label">Pendapatan Hari Ini</div>
                <p class="tenant-kpi-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                <p class="tenant-kpi-note">
                    {{ $revenueDirection >= 0 ? 'Naik' : 'Turun' }} dibanding kemarin.
                </p>
            </article>

            <article class="tenant-kpi-card" style="--kpi-soft: rgba(34, 197, 94, 0.14); --kpi-color: #15803d; --kpi-glow: rgba(34, 197, 94, 0.12);">
                <div class="tenant-kpi-top">
                    <span class="tenant-kpi-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <span class="tenant-kpi-chip">Akumulasi</span>
                </div>
                <div class="tenant-kpi-label">Total Pendapatan</div>
                <p class="tenant-kpi-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="tenant-kpi-note">Dari {{ number_format($totalOrders, 0, ',', '.') }} pesanan keseluruhan.</p>
            </article>

            <article class="tenant-kpi-card" style="--kpi-soft: rgba(168, 85, 247, 0.14); --kpi-color: #7c3aed; --kpi-glow: rgba(168, 85, 247, 0.12);">
                <div class="tenant-kpi-top">
                    <span class="tenant-kpi-icon"><i class="bi bi-calendar2-week-fill"></i></span>
                    <span class="tenant-kpi-chip">Bulan Ini</span>
                </div>
                <div class="tenant-kpi-label">Pesanan Bulan Ini</div>
                <p class="tenant-kpi-value">{{ number_format($monthlyOrders, 0, ',', '.') }}</p>
                <p class="tenant-kpi-note">Fee platform saat ini Rp {{ number_format($monthlyFee, 0, ',', '.') }}.</p>
            </article>
        </section>

        <section class="tenant-status-band">
            <article class="tenant-panel-card chart-panel">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Komposisi Status Pesanan</h3>
                        <p class="tenant-panel-copy">Lihat distribusi order berdasarkan tahap operasional paling penting.</p>
                    </div>
                    <span class="tenant-mini-badge">
                        <i class="bi bi-pie-chart-fill"></i>
                        {{ number_format($pendingCount + $settlementCount + $cookedCount, 0, ',', '.') }} order
                    </span>
                </div>
                <div class="chart-wrap">
                    <canvas id="statusChart"></canvas>
                </div>
            </article>

            <article class="tenant-panel-card">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Status Tracker</h3>
                        <p class="tenant-panel-copy">Bandingkan tahap mana yang paling dominan di tenant aktif.</p>
                    </div>
                </div>
                <div class="tenant-status-list">
                    @foreach ($statusCards as $statusCard)
                        <div class="status-row">
                            <span class="status-dot" style="background: {{ $statusCard['color'] }};"></span>
                            <div class="status-meta">
                                <strong>{{ $statusCard['label'] }}</strong>
                                <span>{{ $statusCard['description'] }}</span>
                            </div>
                            <span class="status-count">{{ number_format($statusCard['count'], 0, ',', '.') }}</span>
                            <div class="status-track">
                                <div class="status-fill"
                                    style="width: {{ round(($statusCard['count'] / $statusTotal) * 100) }}%; background: {{ $statusCard['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="tenant-chart-grid">
            <article class="tenant-panel-card chart-panel">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Trend Order 7 Hari Terakhir</h3>
                        <p class="tenant-panel-copy">Melihat ritme order harian agar lebih mudah membaca momentum tenant.</p>
                    </div>
                    <span class="tenant-mini-badge"><i class="bi bi-activity"></i> Update harian</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="trendChart"></canvas>
                </div>
            </article>

            <article class="tenant-panel-card chart-panel">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Revenue 7 Hari Terakhir</h3>
                        <p class="tenant-panel-copy">Membandingkan pendapatan per hari dengan gaya visual yang lebih bersih.</p>
                    </div>
                    <span class="tenant-mini-badge"><i class="bi bi-currency-dollar"></i> Revenue</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="revenueChart"></canvas>
                </div>
            </article>

            <article class="tenant-panel-card chart-panel">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Menu Terlaris</h3>
                        <p class="tenant-panel-copy">Top 5 menu yang paling sering dipesan customer tenant ini.</p>
                    </div>
                    <span class="tenant-mini-badge"><i class="bi bi-fire"></i> Top 5</span>
                </div>
                @if (count($topMenuNames) > 0)
                    <div class="chart-wrap">
                        <canvas id="topMenuChart"></canvas>
                    </div>
                @else
                    <div class="chart-empty">
                        Belum ada data menu terlaris yang bisa ditampilkan.
                    </div>
                @endif
            </article>

            <article class="tenant-panel-card">
                <div class="tenant-panel-header">
                    <div>
                        <h3 class="tenant-panel-title">Ringkasan Billing</h3>
                        <p class="tenant-panel-copy">Dua blok ini membantu tenant memahami fee layanan bulan lalu dan bulan berjalan.</p>
                    </div>
                    <span class="tenant-mini-badge"><i class="bi bi-receipt-cutoff"></i> Fee platform</span>
                </div>
                <div class="tenant-billing-grid">
                    <div class="tenant-fee-card">
                        <div class="label">Fee Bulan Lalu</div>
                        <p class="value">Rp {{ number_format($lastMonthlyFee, 0, ',', '.') }}</p>
                        <p class="note">{{ number_format($lastMonthlyOrders, 0, ',', '.') }} order x Rp 1.000</p>
                    </div>
                    <div class="tenant-fee-card">
                        <div class="label">Fee Bulan Ini</div>
                        <p class="value">Rp {{ number_format($monthlyFee, 0, ',', '.') }}</p>
                        <p class="note">{{ number_format($monthlyOrders, 0, ',', '.') }} order x Rp 1.000</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.pay.qris', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">
                        Bayar Fee Sekarang
                    </a>
                </div>
            </article>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        const chartTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
        const axisColor = chartTheme === 'dark' ? '#94a3b8' : '#64748b';
        const gridColor = chartTheme === 'dark' ? 'rgba(148, 163, 184, 0.12)' : 'rgba(148, 163, 184, 0.16)';
        const tickFont = {
            family: 'system-ui, sans-serif',
            size: 11
        };

        function money(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value || 0);
        }

        const defaultPlugins = {
            legend: {
                labels: {
                    color: axisColor,
                    boxWidth: 10,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: chartTheme === 'dark' ? '#0f172a' : '#0f172a',
                titleColor: '#f8fafc',
                bodyColor: '#e2e8f0',
                padding: 12,
                cornerRadius: 14,
                displayColors: false
            }
        };

        const trendLabels = @json($trendLabels);
        const trendData = @json($trendData);
        const revenueLabels = @json($revenueLabels);
        const revenueData = @json($revenueData);
        const topMenuLabels = @json($topMenuNames ?? []);
        const topMenuData = @json($topMenuQty ?? []);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    data: trendData,
                    label: 'Order',
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.16)',
                    fill: true,
                    tension: 0.38,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: chartTheme === 'dark' ? '#111827' : '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    ...defaultPlugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: axisColor,
                            font: tickFont
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            precision: 0,
                            color: axisColor,
                            font: tickFont
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    data: revenueData,
                    borderRadius: 14,
                    borderSkipped: false,
                    backgroundColor: [
                        '#fb923c',
                        '#fdba74',
                        '#f97316',
                        '#fb923c',
                        '#fdba74',
                        '#f97316',
                        '#ea580c'
                    ],
                    maxBarThickness: 34
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    ...defaultPlugins,
                    legend: {
                        display: false
                    },
                    tooltip: {
                        ...defaultPlugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                return money(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: axisColor,
                            font: tickFont
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: axisColor,
                            font: tickFont,
                            callback: function(value) {
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Settlement', 'Cooked'],
                datasets: [{
                    data: [
                        {{ $pendingCount }},
                        {{ $settlementCount }},
                        {{ $cookedCount }}
                    ],
                    backgroundColor: ['#f59e0b', '#22c55e', '#3b82f6'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    ...defaultPlugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: axisColor,
                            boxWidth: 10,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        ...defaultPlugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} pesanan`;
                            }
                        }
                    }
                }
            }
        });

        if (topMenuLabels.length > 0) {
            new Chart(document.getElementById('topMenuChart'), {
                type: 'bar',
                data: {
                    labels: topMenuLabels,
                    datasets: [{
                        data: topMenuData,
                        borderRadius: 14,
                        borderSkipped: false,
                        backgroundColor: ['#f97316', '#fb923c', '#fdba74', '#fed7aa', '#ffedd5'],
                        maxBarThickness: 24
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: {
                        ...defaultPlugins,
                        legend: {
                            display: false
                        },
                        tooltip: {
                            ...defaultPlugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    return `${context.raw} porsi terjual`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                precision: 0,
                                color: axisColor,
                                font: tickFont
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: axisColor,
                                font: tickFont
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection

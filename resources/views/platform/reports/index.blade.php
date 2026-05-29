@extends('admin.layout.master')
@section('title', 'Laporan Platform')

@section('css')
    @include('platform.partials.styles')
    <style>
        .report-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: flex-end;
        }

        .report-filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .report-filter-group label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--admin-muted);
        }

        .report-filter-group select,
        .report-filter-group input {
            padding: 0.6rem 0.9rem;
            border-radius: 12px;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            color: var(--admin-text);
            font-size: 0.9rem;
            min-width: 160px;
        }

        .report-period-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .report-period-tabs .period-btn {
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            color: var(--admin-text);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .report-period-tabs .period-btn:hover {
            border-color: var(--admin-accent);
            color: var(--admin-accent);
        }

        .report-period-tabs .period-btn.active {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.25);
        }

        .report-kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
        }

        .report-kpi {
            padding: 1.1rem;
            border-radius: 20px;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface);
            backdrop-filter: blur(14px);
        }

        .report-kpi .label {
            font-size: 0.82rem;
            color: var(--admin-muted);
            margin-bottom: 0.3rem;
        }

        .report-kpi .value {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--admin-text);
            margin: 0;
        }

        .report-table-wrap {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            min-width: 900px;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .report-table thead th {
            color: var(--admin-muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0 0.85rem 0.4rem;
            white-space: nowrap;
        }

        .report-table tbody tr {
            background: var(--admin-surface);
            border-radius: 14px;
        }

        .report-table tbody td {
            padding: 0.85rem;
            border-top: 1px solid var(--admin-border);
            border-bottom: 1px solid var(--admin-border);
            vertical-align: middle;
        }

        .report-table tbody td:first-child {
            border-left: 1px solid var(--admin-border);
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
        }

        .report-table tbody td:last-child {
            border-right: 1px solid var(--admin-border);
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
        }

        .breakdown-card {
            border-radius: 20px;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface);
            backdrop-filter: blur(14px);
            padding: 1.25rem;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--admin-border);
        }

        .breakdown-item:last-child {
            border-bottom: none;
        }

        /* ===== Report Responsive: Tablet ===== */
        @media (max-width: 991.98px) {
            .report-filters {
                flex-direction: column;
                align-items: stretch;
            }

            .report-filter-group {
                width: 100%;
            }

            .report-filter-group select,
            .report-filter-group input {
                width: 100%;
                min-width: unset;
            }

            .report-period-tabs {
                width: 100%;
            }

            .report-period-tabs .period-btn {
                flex: 1;
                text-align: center;
            }

            .report-kpis {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        /* ===== Report Responsive: Mobile ===== */
        @media (max-width: 767.98px) {
            .report-kpis {
                grid-template-columns: repeat(2, 1fr);
                gap: .65rem;
            }

            .report-kpi {
                padding: .85rem;
                border-radius: 16px;
            }

            .report-kpi .label {
                font-size: .76rem;
            }

            .report-kpi .value {
                font-size: 1.1rem;
            }

            .report-period-tabs .period-btn {
                padding: .45rem .8rem;
                font-size: .78rem;
            }

            .report-table-wrap {
                margin: 0 -.5rem;
                padding: 0 .5rem;
                -webkit-overflow-scrolling: touch;
            }

            .report-table {
                min-width: 700px;
            }

            .report-table thead th {
                font-size: .7rem;
                padding: 0 .6rem .3rem;
            }

            .report-table tbody td {
                padding: .7rem .6rem;
                font-size: .82rem;
            }

            .breakdown-card {
                padding: 1rem;
                border-radius: 16px;
            }

            .breakdown-item {
                flex-direction: column;
                align-items: flex-start;
                gap: .35rem;
                padding: .65rem 0;
            }
        }

        /* ===== Report Responsive: Small Mobile ===== */
        @media (max-width: 479.98px) {
            .report-kpis {
                grid-template-columns: 1fr;
            }

            .report-period-tabs {
                flex-direction: column;
            }

            .report-period-tabs .period-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="platform-shell">
        {{-- Header --}}
        <div class="platform-hero">
            <div class="d-flex flex-column flex-lg-row align-items-start justify-content-between gap-4">
                <div>
                    <span class="badge bg-dark-subtle text-dark text-uppercase mb-3">Platform Reports</span>
                    <h2 class="mb-2">Laporan Platform</h2>
                    <p class="text-muted mb-0">Lihat ringkasan pesanan, revenue, dan performa seluruh tenant. Export ke CSV untuk keperluan accounting.</p>
                </div>
                <a href="{{ route('platform.reports.export', ['period' => $period, 'date' => $date, 'tenant_id' => $tenantId]) }}"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="platform-card" style="padding: 1.25rem;">
            <form method="GET" action="{{ route('platform.reports.index') }}">
                <div class="report-filters">
                    <div class="report-filter-group">
                        <label>Periode</label>
                        <div class="report-period-tabs">
                            <a href="{{ route('platform.reports.index', ['period' => 'daily', 'date' => $date, 'tenant_id' => $tenantId]) }}"
                                class="period-btn {{ $period === 'daily' ? 'active' : '' }}">Harian</a>
                            <a href="{{ route('platform.reports.index', ['period' => 'weekly', 'date' => $date, 'tenant_id' => $tenantId]) }}"
                                class="period-btn {{ $period === 'weekly' ? 'active' : '' }}">Mingguan</a>
                            <a href="{{ route('platform.reports.index', ['period' => 'monthly', 'date' => $date, 'tenant_id' => $tenantId]) }}"
                                class="period-btn {{ $period === 'monthly' ? 'active' : '' }}">Bulanan</a>
                        </div>
                    </div>
                    <div class="report-filter-group">
                        <label>Tanggal</label>
                        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
                        <input type="hidden" name="period" value="{{ $period }}">
                        <input type="hidden" name="tenant_id" value="{{ $tenantId }}">
                    </div>
                    <div class="report-filter-group">
                        <label>Tenant</label>
                        <select name="tenant_id" onchange="this.form.submit()">
                            <option value="">Semua Tenant</option>
                            @foreach ($tenants as $t)
                                <option value="{{ $t->id }}" {{ (string) $tenantId === (string) $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- KPI --}}
        <div class="report-kpis">
            <div class="report-kpi">
                <div class="label">Total Pesanan</div>
                <p class="value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Total Revenue</div>
                <p class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Total Pajak</div>
                <p class="value">Rp {{ number_format($totalTax, 0, ',', '.') }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Platform Fee (settlement)</div>
                <p class="value" style="color:#16a34a;">Rp {{ number_format($totalPlatformFee, 0, ',', '.') }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Rata-rata / Order</div>
                <p class="value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Cash</div>
                <p class="value">{{ $cashOrders }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">QRIS</div>
                <p class="value">{{ $qrisOrders }}</p>
            </div>
        </div>

        {{-- Status breakdown --}}
        <div class="report-kpis" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
            <div class="report-kpi">
                <div class="label">Pending</div>
                <p class="value" style="color: #d97706;">{{ $pendingOrders }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Settlement</div>
                <p class="value" style="color: #16a34a;">{{ $settlementOrders }}</p>
            </div>
            <div class="report-kpi">
                <div class="label">Cooked</div>
                <p class="value" style="color: #2563eb;">{{ $cookedOrders }}</p>
            </div>
        </div>

        {{-- Tenant Breakdown --}}
        @if ($tenantBreakdown->count() > 1)
        <div class="breakdown-card">
            <h5 class="fw-bold mb-3">Breakdown per Tenant</h5>
            @foreach ($tenantBreakdown as $tb)
                <div class="breakdown-item">
                    <div>
                        <strong>{{ $tb['tenant_name'] }}</strong>
                        <div class="small text-muted">{{ $tb['order_count'] }} pesanan · Platform fee Rp {{ number_format($tb['platform_fee'], 0, ',', '.') }}</div>
                    </div>
                    <strong>Rp {{ number_format($tb['revenue'], 0, ',', '.') }}</strong>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Detail Table --}}
        <div class="platform-card">
            <div class="card-head">
                <h4>Detail Pesanan — {{ $periodLabel }}</h4>
                <p class="text-muted mb-0">{{ $totalOrders }} pesanan ditemukan dalam periode ini.</p>
            </div>
            <div class="card-body">
                @if ($orders->isEmpty())
                    <div class="platform-empty">Tidak ada pesanan dalam periode ini.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Tenant</th>
                                    <th>Pelanggan</th>
                                    <th>Kamar</th>
                                    <th>Grand Total</th>
                                    <th>Platform Fee</th>
                                    <th>Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $order->order_code }}</strong></td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->tenant?->name ?? '-' }}</td>
                                        <td>{{ $order->user?->fullname ?? '-' }}</td>
                                        <td>{{ $order->table_number ?: '-' }}</td>
                                        <td><strong>Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</strong></td>
                                        <td class="small">
                                            @if ($order->status === 'settlement')
                                                Rp {{ number_format((int) $order->platform_fee, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="platform-badge {{ $order->payment_method === 'qris' ? 'active' : '' }}" style="{{ $order->payment_method === 'cash' ? 'background:rgba(245,158,11,0.12);color:#b45309;' : '' }}">
                                                {{ strtoupper($order->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="platform-badge {{ $order->status === 'settlement' ? 'active' : ($order->status === 'cooked' ? 'active' : 'inactive') }}"
                                                style="{{ $order->status === 'pending' ? 'background:rgba(245,158,11,0.12);color:#b45309;' : ($order->status === 'cooked' ? 'background:rgba(59,130,246,0.12);color:#1d4ed8;' : '') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

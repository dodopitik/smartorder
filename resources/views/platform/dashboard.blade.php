@extends('admin.layout.master')
@section('title', 'Dashboard Platform')

@section('css')
    @include('platform.partials.styles')
@endsection

@section('content')
    <div class="page-heading">
        <h3>Super Admin App</h3>
        <p class="text-muted mb-0">{{ $platformName }}</p>
    </div>

    <div class="page-content">
        <section class="platform-shell">
            <div class="platform-hero">
                <div class="d-flex flex-column flex-lg-row align-items-start justify-content-between gap-3">
                    <div>
                        <span class="badge text-uppercase mb-3">Platform Control Center</span>
                        <h2 class="mb-3">Satu panel untuk tenant, owner, billing, dan health bisnis.</h2>
                        <p class="text-muted mb-0" style="max-width: 720px;">{{ $heroMessage }}</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap flex-column flex-sm-row w-100 w-lg-auto">
                        <a href="{{ route('platform.tenants.create') }}" class="btn btn-primary">Tambah Tenant</a>
                        <a href="{{ route('platform.billing.index') }}" class="btn btn-outline-primary">Lihat Billing</a>
                    </div>
                </div>
            </div>

            <div class="platform-kpis">
                <div class="platform-kpi">
                    <div class="label">Tenant Aktif</div>
                    <p class="value">{{ $activeTenants }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Tenant Nonaktif</div>
                    <p class="value">{{ $inactiveTenants }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Owner / Admin Tenant</div>
                    <p class="value">{{ $tenantAdmins }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Total Pesanan Platform</div>
                    <p class="value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Estimasi Fee Bulan Ini</div>
                    <p class="value">Rp {{ number_format($estimatedMonthlyFee, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Gross Revenue Bulan Ini</div>
                    <p class="value">Rp {{ number_format($monthlyGrossRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Revenue Hari Ini</div>
                    <p class="value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Total Revenue Platform</div>
                    <p class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Rata-rata per Order</div>
                    <p class="value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="platform-grid">
                <article class="platform-card span-7">
                    <div class="card-head">
                        <h4>Snapshot Tenant</h4>
                        <p class="text-muted mb-0">Ringkasan tenant dengan aktivitas paling terasa di platform.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse ($tenantSnapshots as $tenant)
                                <div class="col-12 col-sm-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                            <div>
                                                <h5 class="mb-1">{{ $tenant->name }}</h5>
                                                <div class="text-muted small">/{{ $tenant->slug }}</div>
                                            </div>
                                            <span class="platform-badge {{ $tenant->is_active ? 'active' : 'inactive' }}">
                                                {{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                        <div class="small text-muted mb-3">{{ $tenant->tagline ?: 'Tenant tanpa tagline tambahan.' }}</div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span>Pesanan</span>
                                            <strong>{{ $tenant->orders_count }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span>Menu</span>
                                            <strong>{{ $tenant->items_count }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span>Staff</span>
                                            <strong>{{ $tenant->staff_count }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span>Revenue</span>
                                            <strong>Rp {{ number_format((int) ($tenant->orders_sum_grandtotal ?? 0), 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="platform-empty">Belum ada tenant untuk ditampilkan.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </article>

                <article class="platform-card span-5">
                    <div class="card-head">
                        <h4>Tenant Terbaru</h4>
                        <p class="text-muted mb-0">Pantau tenant yang baru masuk ke platform.</p>
                    </div>
                    <div class="card-body">
                        <div class="platform-stat-list">
                            @forelse ($recentTenants as $tenant)
                                <div class="platform-stat-item">
                                    <div>
                                        <strong>{{ $tenant->name }}</strong>
                                        <div class="text-muted small">/{{ $tenant->slug }}</div>
                                    </div>
                                    <span class="platform-badge {{ $tenant->is_active ? 'active' : 'inactive' }}">
                                        {{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            @empty
                                <div class="platform-empty">Belum ada tenant terbaru.</div>
                            @endforelse
                        </div>
                    </div>
                </article>

                <article class="platform-card span-7">
                    <div class="card-head">
                        <h4>Top Revenue Tenant</h4>
                        <p class="text-muted mb-0">Tenant dengan kontribusi pendapatan paling besar secara kumulatif.</p>
                    </div>
                    <div class="card-body">
                        <div class="platform-stat-list">
                            @forelse ($topRevenueTenants as $tenant)
                                <div class="platform-stat-item">
                                    <div>
                                        <strong>{{ $tenant->name }}</strong>
                                        <div class="text-muted small">/{{ $tenant->slug }}</div>
                                    </div>
                                    <strong>Rp {{ number_format((int) ($tenant->orders_sum_grandtotal ?? 0), 0, ',', '.') }}</strong>
                                </div>
                            @empty
                                <div class="platform-empty">Belum ada data revenue tenant.</div>
                            @endforelse
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection

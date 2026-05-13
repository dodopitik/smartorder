@extends('admin.layout.master')
@section('title', 'Admin Tenant Panel')

@section('css')
    <style>
        .tenant-panel-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            padding: 1.75rem;
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 55%, #eff6ff 100%);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        }

        .tenant-panel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .tenant-panel-stat {
            border-radius: 22px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #fff;
            padding: 1.2rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
        }

        .tenant-panel-stat .label {
            color: #64748b;
            font-size: .9rem;
            margin-bottom: .35rem;
        }

        .tenant-panel-stat .value {
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0;
        }

        .tenant-panel-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1rem;
        }

        .tenant-link-card {
            border-radius: 24px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #fff;
            padding: 1.25rem;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.05);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .tenant-link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        }

        .tenant-link-card .icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            background: rgba(249, 115, 22, 0.12);
            color: #c2410c;
        }

        html[data-bs-theme="dark"] .tenant-panel-hero {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.96) 55%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 24px 48px rgba(2, 6, 23, 0.38);
        }

        html[data-bs-theme="dark"] .tenant-panel-stat,
        html[data-bs-theme="dark"] .tenant-link-card {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 38px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .tenant-panel-stat .label,
        html[data-bs-theme="dark"] .tenant-link-card p,
        html[data-bs-theme="dark"] .tenant-panel-hero .text-muted {
            color: #94a3b8 !important;
        }

        html[data-bs-theme="dark"] .tenant-panel-stat .value,
        html[data-bs-theme="dark"] .tenant-link-card h5,
        html[data-bs-theme="dark"] .tenant-panel-hero h2 {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .tenant-link-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 42px rgba(2, 6, 23, 0.42);
            border-color: rgba(251, 146, 60, 0.2);
        }

        html[data-bs-theme="dark"] .tenant-link-card .icon {
            background: rgba(251, 146, 60, 0.14);
            color: #fdba74;
        }

        html[data-bs-theme="dark"] .badge.bg-dark-subtle {
            background: rgba(251, 146, 60, 0.14) !important;
            color: #fdba74 !important;
            border: 1px solid rgba(251, 146, 60, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="page-heading">
        <h3>Admin Tenant Panel</h3>
        <p class="text-muted mb-0">Kelola operasional tenant <strong>{{ $tenant->name }}</strong> dari satu pintu masuk yang lebih jelas.</p>
    </div>

    <div class="page-content">
        <section class="row gy-4">
            <div class="col-12">
                <div class="tenant-panel-hero">
                    <div class="d-flex flex-column flex-lg-row align-items-start justify-content-between gap-3">
                        <div>
                            <span class="badge bg-dark-subtle text-dark text-uppercase mb-3">Tenant Admin Workspace</span>
                            <h2 class="mb-2">{{ $tenant->name }}</h2>
                            <p class="text-muted mb-0" style="max-width: 720px;">
                                Buka pesanan masuk, atur kategori dan menu, kelola user tenant, lalu pantau dashboard performa tanpa harus menebak-nebak menu mana yang dipakai.
                            </p>
                        </div>
                        <a href="{{ route('tenant.menu', ['tenant' => $tenant->slug]) }}" target="_blank" class="btn btn-outline-primary">Lihat Menu Customer</a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="tenant-panel-grid">
                    <div class="tenant-panel-stat">
                        <div class="label">Total Pesanan</div>
                        <p class="value">{{ number_format($orderCount, 0, ',', '.') }}</p>
                    </div>
                    <div class="tenant-panel-stat">
                        <div class="label">Pesanan Pending</div>
                        <p class="value">{{ number_format($pendingOrders, 0, ',', '.') }}</p>
                    </div>
                    <div class="tenant-panel-stat">
                        <div class="label">Jumlah Menu</div>
                        <p class="value">{{ number_format($menuCount, 0, ',', '.') }}</p>
                    </div>
                    <div class="tenant-panel-stat">
                        <div class="label">Kategori</div>
                        <p class="value">{{ number_format($categoryCount, 0, ',', '.') }}</p>
                    </div>
                    <div class="tenant-panel-stat">
                        <div class="label">Staff Tenant</div>
                        <p class="value">{{ number_format($staffCount, 0, ',', '.') }}</p>
                    </div>
                    <div class="tenant-panel-stat">
                        <div class="label">Revenue Hari Ini</div>
                        <p class="value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="tenant-panel-links">
                    <a href="{{ route('orders.index', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                        <div class="icon"><i class="bi bi-cart-fill"></i></div>
                        <h5>Kelola Pesanan</h5>
                        <p class="text-muted mb-0">Lihat order masuk, ubah status, dan buka detail pesanan tenant.</p>
                    </a>
                    <a href="{{ route('items.index', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                        <div class="icon"><i class="bi bi-card-list"></i></div>
                        <h5>Kelola Menu</h5>
                        <p class="text-muted mb-0">Tambah, edit, dan atur ketersediaan menu untuk outlet ini.</p>
                    </a>
                    <a href="{{ route('categories.index', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                        <div class="icon"><i class="bi bi-tags-fill"></i></div>
                        <h5>Kelola Kategori</h5>
                        <p class="text-muted mb-0">Rapikan struktur kategori supaya menu customer lebih mudah dijelajahi.</p>
                    </a>
                    @if (Auth::user()->role->role_name === 'admin')
                        <a href="{{ route('users.index', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                            <div class="icon"><i class="bi bi-people-fill"></i></div>
                            <h5>Kelola User Tenant</h5>
                            <p class="text-muted mb-0">Atur akun admin, kasir, dan chef yang bekerja di tenant ini.</p>
                        </a>
                        <a href="{{ route('roles.index', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                            <div class="icon"><i class="bi bi-shield-lock-fill"></i></div>
                            <h5>Kelola Role</h5>
                            <p class="text-muted mb-0">Lihat role yang dipakai untuk membagi akses kerja tenant.</p>
                        </a>
                    @endif
                    <a href="{{ route('dashboard', ['tenant' => $tenant->slug]) }}" class="tenant-link-card">
                        <div class="icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <h5>Dashboard Analitik</h5>
                        <p class="text-muted mb-0">Buka dashboard tenant untuk trend penjualan, revenue, dan menu terlaris.</p>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection

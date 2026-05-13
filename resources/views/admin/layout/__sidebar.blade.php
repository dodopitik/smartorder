@php
    $user = Auth::user();
    $role = $user->role->role_name;
    $isSuperAdmin = $role === 'super_admin';
    $tenantName = $currentTenant?->name ?? 'Tenant Workspace';
@endphp

<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ $isSuperAdmin ? route('platform.dashboard') : route('tenant-admin.panel', ['tenant' => $currentTenant->slug]) }}">
                        <div class="sidebar-brand-card">
                            <div class="sidebar-brand-shell">
                                <span class="sidebar-brand-title">{{ $isSuperAdmin ? 'Platform Console' : ($currentTenant?->name ?? 'Tenant Dashboard') }}</span>
                                <span class="sidebar-brand-subtitle">{{ $isSuperAdmin ? 'Super Admin App' : ($currentTenant?->tagline ?? 'Admin Tenant') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @if ($isSuperAdmin)
                    <li class="sidebar-section-label">Platform Core</li>
                    <li class="sidebar-item {{ request()->routeIs('platform.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('platform.dashboard') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-grid-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Dashboard Platform</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-section-label">Operasional</li>
                    <li class="sidebar-item {{ request()->routeIs('platform.tenants.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.tenants.index') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-buildings-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Kelola Tenant</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('platform.owners.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.owners.index') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-person-badge-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Owner Tenant</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('platform.billing.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.billing.index') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-receipt-cutoff"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Billing</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('platform.disbursement.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.disbursement.index') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-cash-stack"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Penyetoran QRIS</span>
                            </span>
                        </a>
                    </li>
                   
                    <li class="sidebar-item {{ request()->routeIs('platform.reports.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.reports.index') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Laporan</span>
                            </span>
                        </a>
                    </li>
                     <li class="sidebar-section-label">Konfigurasi</li>
                    <li class="sidebar-item {{ request()->routeIs('platform.settings.*') ? 'active' : '' }}">
                        <a href="{{ route('platform.settings.edit') }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-sliders"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Global Setting</span>
                            </span>
                        </a>
                    </li>
                @else
                    <li class="sidebar-section-label">Workspace</li>
                    <li class="sidebar-item {{ request()->routeIs('tenant-admin.panel') ? 'active' : '' }}">
                        <a href="{{ route('tenant-admin.panel', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-house-door-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Admin Panel</span>
                            </span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-grid-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Dashboard Tenant</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <a href="{{ route('reports.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-journal-richtext"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Laporan</span>
                            </span>
                        </a>
                    </li>

                    <li class="sidebar-section-label">Operasional</li>
                    <li class="sidebar-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <a href="{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                            <span class="sidebar-link-icon"><i class="bi bi-cart-fill"></i></span>
                            <span class="sidebar-link-copy">
                                <span class="sidebar-link-title">Kelola Pesanan</span>
                            </span>
                        </a>
                    </li>

                    @if ($role === 'admin' || $role === 'cashier')
                        <li class="sidebar-section-label">Katalog Menu</li>
                        <li class="sidebar-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <a href="{{ route('categories.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                                <span class="sidebar-link-icon"><i class="bi bi-tags-fill"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Manajemen Kategori</span>
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('items.*') ? 'active' : '' }}">
                            <a href="{{ route('items.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                                <span class="sidebar-link-icon"><i class="bi bi-card-list"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Daftar Menu</span>
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($role === 'admin')
                        <li class="sidebar-section-label">Tim & Akses</li>
                        <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <a href="{{ route('roles.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                                <span class="sidebar-link-icon"><i class="bi bi-shield-lock-fill"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Manajemen Role</span>
                                </span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a href="{{ route('users.index', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                                <span class="sidebar-link-icon"><i class="bi bi-people-fill"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Manajemen Karyawan</span>
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-section-label">Konfigurasi</li>
                        <li class="sidebar-item {{ request()->routeIs('notification.*') ? 'active' : '' }}">
                            <a href="{{ route('notification.settings', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link'>
                                <span class="sidebar-link-icon"><i class="bi bi-bell-fill"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Notifikasi</span>
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($currentTenant)
                        <li class="sidebar-section-label">Akses Cepat</li>
                        <li class="sidebar-item">
                            <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}" class='sidebar-link' target="_blank">
                                <span class="sidebar-link-icon"><i class="bi bi-box-arrow-up-right"></i></span>
                                <span class="sidebar-link-copy">
                                    <span class="sidebar-link-title">Lihat Menu Tenant</span>
                                </span>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="sidebar-section-label">Sesi</li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <span class="sidebar-link-icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span class="sidebar-link-copy">
                            <span class="sidebar-link-title">Logout</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer-card">
            <div class="sidebar-footer-eyebrow">{{ $isSuperAdmin ? 'Platform Snapshot' : 'Tenant Snapshot' }}</div>
            <p class="sidebar-footer-title">{{ $isSuperAdmin ? 'Semua tenant dalam satu panel.' : $tenantName }}</p>
            <p class="sidebar-footer-copy">
                {{ $isSuperAdmin ? 'Fokuskan navigasi ke tenant, billing, dan setting agar operasional platform lebih cepat.' : 'Sidebar ini dirapikan supaya alur kerja admin hotel lebih cepat dibaca dan lebih tenang dipakai.' }}
            </p>
            <span class="sidebar-footer-meta">
                <i class="bi {{ $isSuperAdmin ? 'bi-command' : 'bi-stars' }}"></i>
                {{ $isSuperAdmin ? 'Super admin workspace' : 'Tenant admin workspace' }}
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow">
            <div class="modal-header bg-danger text-white rounded-top">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="bi bi-box-arrow-right me-1"></i> Konfirmasi Logout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-0">Apakah kamu yakin ingin keluar dari sistem?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    @if (! $isSuperAdmin && $currentTenant)
                        <input type="hidden" name="redirect_to" value="{{ route('tenant.menu', ['tenant' => $currentTenant->slug], false) }}">
                    @endif
                    <button type="submit" class="btn btn-danger">Ya, Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

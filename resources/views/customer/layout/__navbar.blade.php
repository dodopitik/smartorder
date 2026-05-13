@php
    $cart = session('cart.tenant.' . ($currentTenant?->id ?? 'x'), []);
    $count = $cart ? array_sum(array_column($cart, 'qty')) : 0;
@endphp

<div class="container-fluid fixed-top py-3">
    <div class="container">
        <nav class="navbar navbar-expand-xl tenant-card tenant-navbar px-3 px-lg-4 py-3">
            <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}" class="navbar-brand">
                @if ($currentTenant->logo)
                    <img src="{{ asset('img_tenant_logo/' . $currentTenant->logo) }}" alt="{{ $currentTenant->name }}"
                        style="height: 70px; width: auto; object-fit: contain;">
                @else
                    <img src="{{ asset('assets/logo/archana1.png') }}" alt="{{ $currentTenant->name }}"
                        style="height: 70px; width: auto; object-fit: contain;">
                @endif
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-happy"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto gap-xl-3">
                    <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}"
                        class="nav-item nav-link {{ request()->routeIs('tenant.menu', 'tenant.home') ? 'active' : '' }}">Menu</a>
                    <a href="{{ route('tenant.cart', ['tenant' => $currentTenant->slug]) }}"
                        class="nav-item nav-link {{ request()->routeIs('tenant.cart') ? 'active' : '' }}">Keranjang</a>
                    <a href="https://wa.me/{{ $currentTenant?->contact_phone ?? '62895363076706' }}"
                        class="nav-item nav-link">Kontak</a>
                </div>
                <div class="d-flex flex-column flex-xl-row gap-2 mt-3 mt-xl-0">
                    <a href="{{ route('tenant.auth.login', ['tenant' => $currentTenant->slug]) }}" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="fa fa-user-shield me-2"></i>Login
                    </a>

                </div>
            </div>
        </nav>
    </div>
</div>

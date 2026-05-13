@extends('customer.layout.master')

@section('content')
    <section class="tenant-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="tenant-pill">
                        <i class="fa fa-store"></i>
                        {{ $currentTenant?->tagline ?? 'Tenant aktif' }}
                    </span>
                    <h1 class="fw-bold mt-3 mb-3 tenant-hero-title">{{ $currentTenant?->hero_title ?? 'Menu outlet siap dipesan.' }}
                    </h1>
                    <p class="fs-5 mb-4 tenant-hero-copy">
                        {{ $currentTenant?->hero_subtitle ?? 'Scan QR di kamar untuk mulai pesan, lalu order akan tercatat ke tenant hotel ini.' }}
                    </p>
                    <div class="tenant-hero-actions">
                        <a href="{{ route('tenant.cart', ['tenant' => $currentTenant->slug]) }}" class="btn btn-light rounded-pill px-4 py-3 fw-semibold tenant-hero-btn">Lihat
                            Keranjang</a>
                        <span class="tenant-pill tenant-hero-btn">
                            <i class="fa fa-bed"></i>
                            Kamar {{ $roomNumber ?? 'belum terdeteksi' }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="tenant-card tenant-summary-card">
                        <div class="small text-uppercase text-muted fw-bold mb-2">Ringkas</div>
                        <div class="tenant-summary-grid">
                            <div class="tenant-summary-item">
                                <div class="label">Menu Aktif</div>
                                <div class="value">{{ $items->count() }}</div>
                            </div>
                            <div class="tenant-summary-item">
                                <div class="label">Nomor Kamar</div>
                                <div class="value">{{ $roomNumber ?? '-' }}</div>
                            </div>
                        </div>
                        <p class="tenant-summary-copy">Nomor kamar akan disimpan selama sesi pemesanan agar tim hotel tahu
                            pesanan ini datang dari kamar mana.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        @if (session('error'))
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        @if (!$roomNumber)
            <div class="tenant-card p-4 p-lg-5 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <div class="small text-uppercase text-muted fw-bold mb-2">QR Kamar Belum Terdeteksi</div>
                        <h3 class="mb-2">Silakan scan QR dari kamar hotel terlebih dahulu.</h3>
                        <p class="text-muted mb-0">Contoh akses: <code>{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}?kamar=101</code>. Setelah
                            kamar terbaca, data ini akan ikut sampai checkout.</p>
                    </div>
                    <a href="{{ route('tenant.auth.login', ['tenant' => $currentTenant->slug]) }}" class="btn btn-outline-dark rounded-pill px-4 py-3">Masuk
                        Admin Hotel</a>
                </div>
            </div>
        @endif

        <div class="tenant-card p-3 p-lg-4 mb-4">
            <div class="d-flex flex-wrap gap-2" id="categoryFilters">
                <button class="btn btn-primary rounded-pill filter-btn active" data-category="all">Semua</button>
                @foreach ($items->pluck('category.category_name')->filter()->unique() as $category)
                    <button class="btn btn-light rounded-pill filter-btn"
                        data-category="{{ $category }}">{{ $category }}</button>
                @endforeach
            </div>
        </div>

        <div class="row menu-grid">
            @forelse ($items as $item)
                <div class="col-6 col-xl-4 menu-card mb-2 mb-lg-3"
                    data-category="{{ $item->category?->category_name }}">
                    @php
                        $imagePath = $item->image;
                        $imageSrc = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
                            ? $imagePath
                            : asset('img_item_upload/' . ltrim((string) $imagePath, '/'));
                    @endphp
                    <article class="tenant-card menu-entry">
                        <div class="menu-entry-media">
                            <img src="{{ $imageSrc }}" alt="{{ $item->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/logo/archana1.png') }}';">
                            <span class="badge rounded-pill position-absolute top-0 start-0 m-3 px-3 py-2 menu-category-badge">
                                {{ $item->category?->category_name ?? 'Menu' }}
                            </span>
                        </div>
                        <div class="p-4 d-flex flex-column menu-entry-body">
                            <h4 class="mb-2 menu-entry-title">{{ $item->name }}</h4>
                            @php
                                $menuDescription = trim((string) $item->description);
                                $fallbackDescription = $item->category?->category_name
                                    ? 'Pilihan ' . strtolower($item->category->category_name) . ' favorit yang cocok untuk dinikmati di kamar.'
                                    : 'Menu favorit yang disiapkan hangat untuk pesanan Anda.';
                            @endphp
                            <p class="text-muted flex-grow-1 mb-4 menu-entry-desc">
                                {{ $menuDescription !== '' ? $menuDescription : $fallbackDescription }}</p>
                            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                <span class="menu-entry-price">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                <button type="button" onclick="addToCart({{ $item->id }})"
                                    class="btn btn-primary rounded-pill fw-semibold menu-add-btn">
                                    <i class="fa fa-plus me-2"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="tenant-card p-5 text-center">
                        <h3 class="mb-2">Menu tenant belum tersedia</h3>
                        <p class="text-muted mb-0">Tambahkan item untuk tenant ini dari dashboard admin.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @php
            $cart = session('cart.tenant.' . ($currentTenant?->id ?? 'x'), []);
            $count = $cart ? array_sum(array_column($cart, 'qty')) : 0;
        @endphp

        <div id="stickyCartBar" class="sticky-cart-bar" style="{{ $count > 0 ? '' : 'display:none;' }}">
            <div class="sticky-cart-inner">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-shopping-cart text-happy" style="font-size: 1.8rem;"></i>
                    <div>
                        <div class="fw-semibold">Keranjang tenant ini</div>
                        <div class="small text-white-50"><span id="stickyCartCount">{{ $count }}</span> item siap
                            checkout</div>
                    </div>
                </div>
                <a href="{{ route('tenant.cart', ['tenant' => $currentTenant->slug]) }}" class="sticky-cart-btn">Lanjut ke Keranjang</a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1600,
            timerProgressBar: true
        });

        function addToCart(menuId) {
            fetch("{{ route('tenant.cart.add', ['tenant' => $currentTenant->slug]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: menuId
                    })
                })
                .then(async (response) => {
                    const data = await response.json();

                    if (!response.ok) {
                        Toast.fire({
                            icon: 'error',
                            title: data.success || 'Gagal menambahkan.'
                        });
                        return;
                    }

                    const cart = data.cart || {};
                    const count = Object.values(cart).reduce((sum, item) => sum + (item.qty || 0), 0);
                    const stickyBar = document.getElementById("stickyCartBar");
                    const stickyCount = document.getElementById("stickyCartCount");

                    if (stickyBar && stickyCount) {
                        stickyCount.textContent = count;
                        stickyBar.style.display = count > 0 ? "block" : "none";
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.success || 'Berhasil ditambahkan ke keranjang!'
                    });
                })
                .catch((err) => {
                    Toast.fire({
                        icon: 'error',
                        title: err.message || 'Terjadi kesalahan.'
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.menu-card');

            filterButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const category = btn.getAttribute('data-category');
                    filterButtons.forEach((button) => button.classList.remove('active',
                        'btn-primary'));
                    filterButtons.forEach((button) => button.classList.add('btn-light'));
                    btn.classList.add('active', 'btn-primary');
                    btn.classList.remove('btn-light');

                    cards.forEach((card) => {
                        const cardCat = card.getAttribute('data-category');
                        card.style.display = category === 'all' || cardCat === category ?
                            '' : 'none';
                    });
                });
            });
        });
    </script>
@endsection

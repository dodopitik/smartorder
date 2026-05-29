@extends('customer.layout.master')

@section('css')
    <style>
        .cart-list {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .85rem;
            border-radius: 20px;
            border: 1px solid rgba(15, 23, 42, 0.07);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 250, 244, 0.96) 100%);
            box-shadow: 0 8px 22px rgba(124, 58, 12, 0.05);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .cart-item:hover {
            box-shadow: 0 14px 30px rgba(124, 58, 12, 0.1);
        }

        .cart-item-img {
            width: 76px;
            height: 76px;
            border-radius: 16px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid rgba(251, 146, 60, 0.18);
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        .cart-item-name {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: -.01em;
            color: #1f2937;
            line-height: 1.3;
        }

        .cart-item-price {
            color: #92400e;
            font-size: .9rem;
            font-weight: 700;
        }

        .cart-item-price-sep {
            color: #b08968;
            font-weight: 500;
            font-size: .8rem;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            flex-shrink: 0;
        }

        /* ===== Quantity Stepper ===== */
        .cart-qty {
            display: inline-flex;
            align-items: center;
            background: #fff;
            border: 1px solid rgba(251, 146, 60, 0.28);
            border-radius: 999px;
            padding: 3px;
            box-shadow: inset 0 1px 3px rgba(124, 58, 12, 0.05);
        }

        .cart-qty-btn {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            color: #c2410c;
            font-size: .8rem;
            cursor: pointer;
            transition: transform .15s ease, background .2s ease, color .2s ease;
        }

        .cart-qty-btn:hover {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
        }

        .cart-qty-btn:active {
            transform: scale(.9);
        }

        .cart-qty-input {
            width: 40px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 800;
            font-size: 1rem;
            color: #1f2937;
            padding: 0;
            outline: none;
            -moz-appearance: textfield;
        }

        .cart-qty-input::-webkit-outer-spin-button,
        .cart-qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .cart-item-total {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            min-width: 96px;
        }

        .cart-item-total-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #b08968;
            font-weight: 700;
        }

        .cart-item-total strong {
            font-size: 1.02rem;
            font-weight: 900;
            color: #7c2d12;
            letter-spacing: -.02em;
        }

        .cart-remove-btn {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 12px;
            border: 1px solid rgba(220, 38, 38, 0.2);
            background: rgba(254, 226, 226, 0.6);
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, transform .15s ease;
        }

        .cart-remove-btn:hover {
            background: #dc2626;
            color: #fff;
        }

        .cart-remove-btn:active {
            transform: scale(.92);
        }

        /* ===== Mobile layout ===== */
        @media (max-width: 575.98px) {
            .cart-item {
                flex-wrap: wrap;
                gap: .75rem;
                padding: .75rem;
                border-radius: 18px;
            }

            .cart-item-img {
                width: 56px;
                height: 56px;
                border-radius: 14px;
            }

            .cart-item-info {
                /* nama + harga ambil sisa ruang di samping gambar */
                flex: 1 1 calc(100% - 72px);
            }

            .cart-item-name {
                font-size: .92rem;
            }

            .cart-item-price {
                font-size: .82rem;
            }

            .cart-item-controls {
                /* baris kedua: kontrol melebar penuh di bawah gambar+nama */
                width: 100%;
                justify-content: space-between;
                gap: .6rem;
                padding-top: .65rem;
                border-top: 1px dashed rgba(15, 23, 42, 0.08);
            }

            .cart-item-total {
                min-width: 0;
                align-items: flex-start;
            }

            .cart-item-total-label {
                font-size: .66rem;
            }

            .cart-item-total strong {
                font-size: .95rem;
            }

            .cart-qty-btn {
                width: 36px;
                height: 36px;
            }

            .cart-qty-input {
                width: 36px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="tenant-hero">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
                <div>
                    <span class="tenant-pill"><i class="fa fa-shopping-cart"></i> Keranjang {{ $currentTenant?->name }}</span>
                    <h1 class="display-5 fw-bold mt-3 mb-2">Cek ulang item sebelum checkout.</h1>
                    <p class="text-muted fs-5 mb-0">Semua item di bawah ini adalah daftar pesanan anda yang nanti akan di kirim ke kamar anda.</p>
                </div>
                <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}" class="btn btn-light rounded-pill px-4 py-3 fw-semibold">Tambah Menu Lagi</a>
            </div>
        </div>
    </section>

    <section class="container py-5">
        @if (session('success'))
            <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        @if (empty($cart))
            <div class="tenant-card p-5 text-center">
                <h3 class="mb-2">Keranjang masih kosong</h3>
                <p class="text-muted mb-4">Pilih menu dulu dari tenant ini sebelum lanjut ke checkout.</p>
                <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">Kembali ke Menu</a>
            </div>
        @else
            <div class="row g-4 align-items-start">
                <div class="col-xl-8">
                    <div class="tenant-card p-3 p-lg-4">
                        @php
                            $subtotal = 0;
                        @endphp
                        <ul class="cart-list list-unstyled mb-0">
                            @foreach ($cart as $item)
                                @php
                                    $itemTotal = $item['price'] * $item['qty'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <li class="cart-item">
                                    <img src="{{ asset('img_item_upload/' . $item['image']) }}" class="cart-item-img"
                                        alt="{{ $item['name'] }}"
                                        onerror="this.onerror=null;this.src='{{ $item['image'] }}';">

                                    <div class="cart-item-info">
                                        <strong class="cart-item-name">{{ $item['name'] }}</strong>
                                        <span class="cart-item-price">Rp{{ number_format($item['price'], 0, ',', '.') }} <span class="cart-item-price-sep">/ item</span></span>
                                    </div>

                                    <div class="cart-item-controls">
                                        <div class="cart-qty">
                                            <button type="button" class="cart-qty-btn" aria-label="Kurangi jumlah" onclick="updateQuantity({{ $item['id'] }}, -1)">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input id="qty-{{ $item['id'] }}" type="text" class="cart-qty-input"
                                                value="{{ $item['qty'] }}" readonly>
                                            <button type="button" class="cart-qty-btn" aria-label="Tambah jumlah" onclick="updateQuantity({{ $item['id'] }}, 1)">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="cart-item-total">
                                            <span class="cart-item-total-label">Total</span>
                                            <strong>Rp{{ number_format($itemTotal, 0, ',', '.') }}</strong>
                                        </div>

                                        <button type="button" class="cart-remove-btn js-remove-cart-item"
                                            data-item-id="{{ $item['id'] }}" aria-label="Hapus item">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @php
                    $tax = $subtotal * 0.11;
                    $total = $subtotal + $tax;
                @endphp
                <div class="col-xl-4">
                    <div class="tenant-card p-4 position-sticky" style="top: 108px;">
                        <h3 class="mb-4">Ringkasan Pesanan</h3>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Kamar</span>
                            <strong>{{ session('room_number.tenant.' . ($currentTenant?->id ?? 'x')) ?? '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">PPN 11%</span>
                            <strong>Rp{{ number_format($tax, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-4">
                            <span class="fs-5 fw-semibold">Total</span>
                            <strong class="fs-4 text-happy">Rp{{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <a href="{{ route('tenant.checkout', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill w-100 py-3 mb-3">Lanjut ke Checkout</a>
                        <a href="{{ route('tenant.cart.clear', ['tenant' => $currentTenant->slug]) }}"
                            class="btn btn-light rounded-pill w-100 py-3 js-clear-cart">Kosongkan Keranjang</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

@endsection

@section('scripts')
    <script>
        const CartAlert = Swal.mixin({
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#334155',
            reverseButtons: true
        });

        const CartToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true
        });

        function updateQuantity(itemId, change) {
            const qtyInput = document.getElementById('qty-' + itemId);
            const currentQty = parseInt(qtyInput.value);
            const newQty = currentQty + change;

            if (newQty <= 0) {
                confirmRemoveItem(itemId);
                return;
            }

            fetch("{{ route('tenant.cart.update', ['tenant' => $currentTenant->slug]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: itemId,
                        qty: newQty
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        CartToast.fire({
                            icon: 'error',
                            title: 'Gagal memperbarui jumlah item.'
                        });
                    }
                })
                .catch(() => {
                    CartToast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengupdate item.'
                    });
                });
        }

        function removeItemFromCart(itemId) {
            fetch("{{ route('tenant.cart.remove', ['tenant' => $currentTenant->slug]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: itemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        CartToast.fire({
                            icon: 'error',
                            title: 'Gagal menghapus item dari keranjang.'
                        });
                    }
                })
                .catch(() => {
                    CartToast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat menghapus item.'
                    });
                });
        }

        function confirmRemoveItem(itemId) {
            CartAlert.fire({
                title: 'Hapus item ini?',
                text: 'Item akan dikeluarkan dari keranjang pesanan Anda.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeItemFromCart(itemId);
                }
            });
        }

        document.querySelectorAll('.js-remove-cart-item').forEach((button) => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                confirmRemoveItem(itemId);
            });
        });

        document.querySelector('.js-clear-cart')?.addEventListener('click', function(event) {
            event.preventDefault();
            const targetUrl = this.getAttribute('href');

            CartAlert.fire({
                title: 'Kosongkan keranjang?',
                text: 'Semua item yang sudah dipilih akan dihapus dari pesanan ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kosongkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = targetUrl;
                }
            });
        });
    </script>
@endsection

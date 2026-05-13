@extends('customer.layout.master')

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
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    @foreach ($cart as $item)
                                        @php
                                            $itemTotal = $item['price'] * $item['qty'];
                                            $subtotal += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('img_item_upload/' . $item['image']) }}" class="rounded-4"
                                                        style="width: 72px; height: 72px; object-fit: cover;"
                                                        alt="{{ $item['name'] }}"
                                                        onerror="this.onerror=null;this.src='{{ $item['image'] }}';">
                                                    <div>
                                                        <strong>{{ $item['name'] }}</strong>
                                                        <div class="small text-muted">Tenant: {{ $currentTenant?->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td>
                                                <div class="input-group" style="max-width: 126px;">
                                                    <button class="btn btn-light border" onclick="updateQuantity({{ $item['id'] }}, -1)">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <input id="qty-{{ $item['id'] }}" type="text" class="form-control text-center border"
                                                        value="{{ $item['qty'] }}" readonly>
                                                    <button class="btn btn-light border" onclick="updateQuantity({{ $item['id'] }}, 1)">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($itemTotal, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-outline-danger rounded-pill js-remove-cart-item"
                                                    data-item-id="{{ $item['id'] }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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

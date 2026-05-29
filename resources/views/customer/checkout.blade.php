@extends('customer.layout.master')

@section('content')
    <section class="tenant-hero">
        <div class="container">
            <div class="row g-4 align-items-end">
                <div class="col-lg-8">
                    <span class="tenant-pill"><i class="fa fa-credit-card"></i> Checkout {{ $currentTenant?->name }}</span>
                    <h1 class="display-5 fw-bold mt-3 mb-3">Konfirmasi pesanan dan pilih metode pembayaran.</h1>
                    <p class="tenant-hero-copy fs-5 mb-0">Flow checkout sekarang lebih jelas: customer checkout per tenant, admin tenant menerima order tenant itu saja.</p>
                </div>
                <div class="col-lg-4">
                    <div class="tenant-card p-4">
                        <div class="small text-uppercase text-muted fw-bold">Nomor Kamar</div>
                        <div class="display-6 fw-bold text-happy">{{ $roomNumber ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                <div class="fw-semibold mb-2">Checkout belum bisa diproses.</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        <form id="checkout-form" action="{{ route('tenant.checkout.store', ['tenant' => $currentTenant->slug]) }}" method="POST">
            @csrf
            <div class="row g-4 align-items-start">
                <div class="col-xl-7">
                    <div class="tenant-card p-4 p-lg-5">
                        <h3 class="mb-4">Data Customer</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control form-control-lg" value="{{ old('fullname') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control form-control-lg" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan Pesanan</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="Opsional">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h4 class="mb-3">Detail Item</h4>
                            <div class="d-flex flex-column gap-3">
                                @php
                                    $subTotal = 0;
                                @endphp
                                @foreach ($cart as $item)
                                    @php
                                        $itemTotal = $item['price'] * $item['qty'];
                                        $subTotal += $itemTotal;
                                        $imagePath = $item['image'] ?? null;
                                        $imageSrc = \Illuminate\Support\Str::startsWith((string) $imagePath, ['http://', 'https://'])
                                            ? $imagePath
                                            : asset('img_item_upload/' . ltrim((string) $imagePath, '/'));
                                    @endphp
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-3 rounded-4"
                                        style="background: rgba(15, 23, 42, 0.04);">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $imageSrc }}" class="rounded-4"
                                                style="width: 72px; height: 72px; object-fit: cover;" alt="{{ $item['name'] }}"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/logo/archana1.png') }}';">
                                            <div>
                                                <strong>{{ $item['name'] }}</strong>
                                                <div class="small text-muted">{{ $item['qty'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <strong class="text-happy">Rp{{ number_format($itemTotal, 0, ',', '.') }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $tax = $subTotal * 0.11;
                    $total = $subTotal + $tax;
                @endphp

                <div class="col-xl-5">
                    <div class="tenant-card p-4 position-sticky" style="top: 108px;">
                        <h3 class="mb-4">Ringkasan Checkout</h3>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Kamar</span>
                            <strong>{{ $roomNumber ?? '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <strong>Rp{{ number_format($subTotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Pajak 11%</span>
                            <strong>Rp{{ number_format($tax, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top mb-4">
                            <span class="fs-5 fw-semibold">Grand Total</span>
                            <strong class="fs-4 text-happy">Rp{{ number_format($total, 0, ',', '.') }}</strong>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <div class="d-grid gap-3">
                                <label class="tenant-card p-3 d-flex align-items-center justify-content-between">
                                    <span>
                                        <strong>Tunai</strong>
                                        <div class="small text-muted">Kasir menyelesaikan order saat pembayaran diterima.</div>
                                    </span>
                                    <input type="radio" class="form-check-input" name="payment_method" value="cash">
                                </label>
                                <label class="tenant-card p-3 d-flex align-items-center justify-content-between">
                                    <span>
                                        <strong>QRIS / E-wallet</strong>
                                        <div class="small text-muted">Menunggu callback Midtrans untuk settlement otomatis.</div>
                                    </span>
                                    <input type="radio" class="form-check-input" name="payment_method" value="qris">
                                </label>
                            </div>
                        </div>

                        <button id="pay-button" type="button" class="btn btn-primary rounded-pill w-100 py-3 fw-semibold">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    @if (config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const payButton = document.getElementById("pay-button");
            const form = document.getElementById("checkout-form");

            payButton.addEventListener("click", function() {
                let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

                if (!paymentMethod) {
                    alert("Pilih metode pembayaran terlebih dahulu.");
                    return;
                }

                paymentMethod = paymentMethod.value;
                const formData = new FormData(form);

                if (paymentMethod === "cash") {
                    form.submit();
                    return;
                }

                fetch("{{ route('tenant.checkout.store', ['tenant' => $currentTenant->slug]) }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.snap_token) {
                            if (data.redirect_url) {
                                alert(data.message || "Pesanan sudah tercatat. Anda akan diarahkan ke halaman nota.");
                                window.location.href = data.redirect_url;
                                return;
                            }

                            alert(data.message || "Terjadi kesalahan saat memproses pembayaran.");
                            return;
                        }

                        snap.pay(data.snap_token, {
                            onSuccess: function() {
                                window.location.href = data.redirect_url;
                            },
                            onPending: function() {
                                window.location.href = data.redirect_url;
                            },
                            onError: function() {
                                alert("Pembayaran gagal.");
                            }
                        });
                    })
                    .catch(error => {
                        console.error("error", error);
                        alert("Terjadi kesalahan, silakan coba lagi.");
                    });
            });
        });
    </script>
@endsection

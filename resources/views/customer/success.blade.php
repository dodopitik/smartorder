@extends('customer.layout.master')

@section('content')
    <section class="tenant-hero">
        <div class="container text-center">
            <span class="tenant-pill"><i class="fa fa-receipt"></i> Receipt Tenant</span>
            <h1 class="display-5 fw-bold mt-3 mb-3">Pesanan tercatat untuk {{ $currentTenant?->name }}.</h1>
            <p class="tenant-hero-copy fs-5 mb-0 mx-auto">
                @if ($order->payment_method === 'cash')
                    Tunjukkan kode ini ke kasir tenant untuk menyelesaikan pembayaran tunai.
                @elseif ($order->status === 'settlement')
                    Pembayaran sudah diterima dan pesanan sedang diproses.
                @else
                    Pembayaran digital sedang menunggu verifikasi gateway.
                @endif
            </p>
        </div>
    </section>

    <section class="container py-5">
        @if (session('success'))
            <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="tenant-card p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="small text-uppercase text-muted fw-bold">Kode Pesanan</div>
                        <div class="display-6 fw-bold text-happy">{{ $order->order_code }}</div>
                        <div class="mt-3 text-muted">Kamar {{ $order->table_number ?: '-' }}</div>
                        <div class="mt-3">
                            @if ($order->payment_method === 'cash' && $order->status === 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu makanan disajikan
                                    lalu bayar cash</span>
                            @elseif ($order->status === 'settlement')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Pembayaran sudah diterima</span>
                            @elseif ($order->status === 'cooked')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Pesanan selesai dimasak</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">Menunggu verifikasi
                                    pembayaran</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">
                        @foreach ($orderItems as $orderItem)
                            @php
                                $unitPrice =
                                    $orderItem->quantity > 0
                                        ? (int) round($orderItem->price / $orderItem->quantity)
                                        : $orderItem->price;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center gap-3 p-3 rounded-4"
                                style="background: rgba(15, 23, 42, 0.04);">
                                <div>
                                    <strong>{{ \Illuminate\Support\Str::limit($orderItem->item->name, 30) }}</strong>
                                    <div class="small text-muted">{{ $orderItem->quantity }} x
                                        Rp{{ number_format($unitPrice, 0, ',', '.') }}</div>
                                </div>
                                <strong>Rp{{ number_format($orderItem->total_price, 0, ',', '.') }}</strong>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <strong>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax 11%</span>
                            <strong>Rp{{ number_format($order->tax, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <span class="fs-5 fw-semibold">Total</span>
                            <strong class="fs-4 text-happy">Rp{{ number_format($order->grandtotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('tenant.menu', ['tenant' => $currentTenant->slug]) }}?kamar={{ $order->table_number }}"
                            class="btn btn-primary rounded-pill px-5 py-3">
                            Kembali ke Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('admin.layout.master')
@section('title', 'Detail Pesanan')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Pesanan</h3>
                    <p class="text-subtitle text-muted">Lihat rincian transaksi, status pesanan, dan item yang dipesan customer.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}">Pesanan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h1 class="">Kode Pesanan : {{ $orders->order_code }}</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Nama Pelanggan : {{ $orders->user->fullname }}</p>
                            <p>Total : Rp. {{ number_format($orders->grandtotal, 0, ',', '.') }}</p>
                            <p>No. Kamar : {{ $orders->table_number }}</p>
                            <p>Status :
                                @if ($orders->status === 'settlement')
                                    <span class="badge bg-success">Settlement</span>
                                @elseif ($orders->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($orders->status === 'cooked')
                                    <span class="badge bg-info text-dark">Cooked</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($orders->status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>Metode Pembayaran : {{ $orders->payment_method }}</p>
                            <p>Catatan : {{ $orders->notes }}</p>
                            <p>Dibuat pada : {{ $orders->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-container">
                            <table class="table table-striped" id="table1">

                                <thead class="">
                                    <tr class="">
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Menu</th>
                                        <th>Quantity</th>
                                        <th>Harga</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $menu)
                                        <tr>
                                            <td> {{ $loop->iteration }}</td>

                                            <td>
                                                <img src="{{ asset('img_item_upload/' . $menu->item->image) }}"
                                                    width="60" class="img-fluid rounded-circle"
                                                    style="width: 80px; height: 80px;" alt="{{ $menu->item->name }}"
                                                    onerror="this.onerror=null;this.src='{{ $menu->item->image }}';">
                                            </td>
                                            <td> {{ $menu->item->name }}</td>
                                            <td> {{ $menu->quantity }}</td>
                                            <td>Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tr>
                                    <th colspan="4" class="text-end">Total :</th>
                                    <th>Rp. {{ number_format($orders->subtotal, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Tax :</th>
                                    <th>Rp. {{ number_format($orders->tax, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end ">Total Pesanan :</th>
                                    <th>Rp. {{ number_format($orders->grandtotal, 0, ',', '.') }}</th>
                                </tr>

                                <tr>
                                    <th colspan="5" class="text-end ">
                                       <a href="{{ route('orders.print', ['tenant' => $currentTenant->slug, 'orderId' => $orders->id]) }}" target="_blank" class="btn btn-primary mt-3">
                                            <i class="bi bi-printer fs-5"></i> Print Struk
                                        </a>
                                         
                                        <a href="{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}" class="btn btn-secondary mt-3 ">
                                            <i class="bi bi-arrow-left fs-5"></i> Kembali ke Daftar Pesanan
                                        </a>
                                        
                                    </th>
                                      
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

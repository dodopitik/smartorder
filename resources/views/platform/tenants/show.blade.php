@extends('admin.layout.master')
@section('title', 'Detail Tenant')

@section('css')
    @include('platform.partials.styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="page-heading d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <h3>{{ $tenant->name }}</h3>
            <p class="text-muted mb-0">Detail tenant, owner utama, aktivitas, dan performa bisnis.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('platform.tenants.edit', $tenant) }}" class="btn btn-primary">Edit Tenant</a>
            <a href="{{ route('tenant.menu', ['tenant' => $tenant->slug]) }}" target="_blank" class="btn btn-outline-primary">Buka Menu Customer</a>
        </div>
    </div>

    <div class="page-content">
        <section class="platform-shell">
            <div class="platform-kpis">
                <div class="platform-kpi">
                    <div class="label">Status</div>
                    <p class="value">{{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Total Pesanan</div>
                    <p class="value">{{ number_format($tenant->orders_count, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Revenue Tenant</div>
                    <p class="value">Rp {{ number_format((int) ($tenant->orders_sum_grandtotal ?? 0), 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Revenue Bulan Ini</div>
                    <p class="value">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Order Bulan Ini</div>
                    <p class="value">{{ number_format($monthlyOrders, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Menu Aktif</div>
                    <p class="value">{{ number_format($tenant->items_count, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="platform-grid">
                <article class="platform-card span-5">
                    <div class="card-head">
                        <h4>Profil Tenant</h4>
                        <p class="text-muted mb-0">Identitas tenant yang dilihat oleh customer dan owner.</p>
                    </div>
                    <div class="card-body">
                        <div class="platform-stat-list">
                            <div class="platform-stat-item"><span>Slug</span><strong>/hotel/{{ $tenant->slug }}</strong></div>
                            <div class="platform-stat-item"><span>Tagline</span><strong>{{ $tenant->tagline ?: '-' }}</strong></div>
                            <div class="platform-stat-item"><span>Email</span><strong>{{ $tenant->contact_email ?: '-' }}</strong></div>
                            <div class="platform-stat-item"><span>Telepon</span><strong>{{ $tenant->contact_phone ?: '-' }}</strong></div>
                            <div class="platform-stat-item"><span>Alamat</span><strong>{{ $tenant->address ?: '-' }}</strong></div>
                            <div class="platform-stat-item"><span>Owner Utama</span><strong>{{ $owner?->fullname ?? 'Belum ada owner' }}</strong></div>
                        </div>
                    </div>
                </article>

                <article class="platform-card span-7">
                    <div class="card-head">
                        <h4>Tim Tenant</h4>
                        <p class="text-muted mb-0">Akun yang terhubung ke tenant ini.</p>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="platform-table table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenant->users as $user)
                                    <tr>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email ?: '-' }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->role?->role_name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="platform-empty">Belum ada user tenant.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="platform-card span-7">
                    <div class="card-head">
                        <h4>Order Terbaru</h4>
                        <p class="text-muted mb-0">Pantau aktivitas transaksi tenant terbaru.</p>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="platform-table table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenant->orders as $order)
                                    <tr>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->user?->fullname ?? '-' }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>Rp {{ number_format((int) $order->grandtotal, 0, ',', '.') }}</td>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="platform-empty">Belum ada order tenant.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="platform-card span-5">
                    <div class="card-head">
                        <h4>Aksi Cepat</h4>
                        <p class="text-muted mb-0">Kelola status tenant dari halaman ini.</p>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form action="{{ route('platform.tenants.toggle-status', $tenant) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    {{ $tenant->is_active ? 'Nonaktifkan Tenant' : 'Aktifkan Tenant' }}
                                </button>
                            </form>
                            <a href="{{ route('platform.tenants.edit', $tenant) }}" class="btn btn-outline-primary w-100">Edit Identitas Tenant</a>
                            <form action="{{ route('platform.tenants.destroy', $tenant) }}" method="POST"
                                class="js-delete-tenant" data-tenant-name="{{ $tenant->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    Hapus Tenant
                                </button>
                            </form>
                        </div>
                        <div class="platform-empty mt-3">
                            Tip: untuk tenant yang sudah punya order, lebih aman gunakan tombol nonaktifkan daripada hapus permanen.
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-delete-tenant').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const tenantName = form.dataset.tenantName || 'tenant ini';

                    Swal.fire({
                        title: 'Hapus tenant permanen?',
                        html: `Tenant <b>${tenantName}</b> akan dihapus jika belum punya data terkait.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus tenant',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#334155',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });
        });
    </script>
@endsection

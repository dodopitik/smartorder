@extends('admin.layout.master')
@section('title', 'Kelola Tenant')

@section('css')
    @include('platform.partials.styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="page-heading d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <h3>Kelola Tenant</h3>
            <p class="text-muted mb-0">Buat, edit, dan monitor tenant beserta owner utamanya.</p>
        </div>
        <a href="{{ route('platform.tenants.create') }}" class="btn btn-primary">Tambah Tenant Baru</a>
    </div>

    <div class="page-content">
        <section class="platform-card">
            <div class="card-body table-responsive">
                <table class="platform-table table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Owner</th>
                            <th>Kontak</th>
                            <th>Stat</th>
                            <th>Status</th>
                            <th>Revenue</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                            @php $owner = $tenant->users->first(); @endphp
                            <tr>
                                <td>
                                    <strong>{{ $tenant->name }}</strong>
                                    <div class="text-muted small">/{{ $tenant->slug }}</div>
                                </td>
                                <td>
                                    <div>{{ $owner?->fullname ?? 'Belum ada owner' }}</div>
                                    <div class="text-muted small">{{ $owner?->email ?? 'Tambahkan dari edit tenant' }}</div>
                                </td>
                                <td>
                                    <div>{{ $tenant->contact_phone ?: '-' }}</div>
                                    <div class="text-muted small">{{ $tenant->contact_email ?: '-' }}</div>
                                </td>
                                <td>
                                    <div class="small">Order: <strong>{{ $tenant->orders_count }}</strong></div>
                                    <div class="small">Menu: <strong>{{ $tenant->items_count }}</strong></div>
                                    <div class="small">User: <strong>{{ $tenant->users_count }}</strong></div>
                                </td>
                                <td>
                                    <span class="platform-badge {{ $tenant->is_active ? 'active' : 'inactive' }}">
                                        {{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format((int) ($tenant->orders_sum_grandtotal ?? 0), 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('platform.tenants.show', $tenant) }}" class="btn btn-dark btn-sm">Detail</a>
                                        <a href="{{ route('tenant.menu', ['tenant' => $tenant->slug]) }}" class="btn btn-light btn-sm" target="_blank">Menu</a>
                                        <a href="{{ route('platform.tenants.edit', $tenant) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        <form action="{{ route('platform.tenants.toggle-status', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                {{ $tenant->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('platform.tenants.destroy', $tenant) }}" method="POST"
                                            class="d-inline js-delete-tenant" data-tenant-name="{{ $tenant->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="platform-empty">Belum ada tenant. Tambahkan tenant pertama dari tombol di kanan atas.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                        title: 'Hapus tenant?',
                        html: `<div class="text-start">
                            <p>Tenant <b>${tenantName}</b> akan dihapus.</p>
                            <p class="text-muted small">Jika tenant masih punya data (order, menu, user), pilih "Hapus Paksa" untuk menghapus semua data sekaligus.</p>
                        </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: 'Hapus (jika kosong)',
                        denyButtonText: 'Hapus Paksa (semua data)',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        denyButtonColor: '#7c2d12',
                        cancelButtonColor: '#334155',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        } else if (result.isDenied) {
                            // Tambah hidden input force=1
                            const forceInput = document.createElement('input');
                            forceInput.type = 'hidden';
                            forceInput.name = 'force';
                            forceInput.value = '1';
                            form.appendChild(forceInput);
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });
        });
    </script>
@endsection

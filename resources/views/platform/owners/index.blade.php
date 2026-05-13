@extends('admin.layout.master')
@section('title', 'Owner Tenant')

@section('css')
    @include('platform.partials.styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="page-heading">
        <h3>Owner Tenant</h3>
        <p class="text-muted mb-0">Daftar admin utama yang mengelola operasional tenant masing-masing.</p>
    </div>

    <div class="page-content">
        <section class="platform-card">
            <div class="card-body table-responsive">
                <table class="platform-table table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Owner</th>
                            <th>Tenant</th>
                            <th>Kontak</th>
                            <th>Status Tenant</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($owners as $owner)
                            <tr>
                                <td>
                                    <strong>{{ $owner->fullname }}</strong>
                                    <div class="text-muted small">{{ $owner->username ?: '-' }}</div>
                                </td>
                                <td>
                                    <div>{{ $owner->tenant?->name ?? 'Belum terhubung tenant' }}</div>
                                    <div class="text-muted small">/{{ $owner->tenant?->slug ?? '-' }}</div>
                                </td>
                                <td>
                                    <div>{{ $owner->email }}</div>
                                    <div class="text-muted small">{{ $owner->phone }}</div>
                                </td>
                                <td>
                                    @if ($owner->tenant)
                                        <span class="platform-badge {{ $owner->tenant->is_active ? 'active' : 'inactive' }}">
                                            {{ $owner->tenant->is_active ? 'Tenant Aktif' : 'Tenant Nonaktif' }}
                                        </span>
                                    @else
                                        <span class="platform-badge inactive">Tanpa Tenant</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('platform.owners.edit', $owner) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        <form action="{{ route('platform.owners.destroy', $owner) }}" method="POST"
                                            class="d-inline js-delete-owner" data-owner-name="{{ $owner->fullname }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="platform-empty">Belum ada owner tenant. Tambahkan dari menu kelola tenant.</div>
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
            document.querySelectorAll('.js-delete-owner').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const ownerName = form.dataset.ownerName || 'owner ini';

                    Swal.fire({
                        title: 'Hapus owner?',
                        html: 'Owner <b>' + ownerName + '</b> akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#334155',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });
        });
    </script>
@endsection

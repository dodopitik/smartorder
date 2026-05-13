@extends('admin.layout.master')
@section('title', 'Manajemen Karyawan')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .staff-shell {
            display: grid;
            gap: 1.2rem;
        }

        .staff-hero,
        .staff-card,
        .staff-kpi {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .staff-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 24%),
                linear-gradient(135deg, rgba(239, 246, 255, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(245, 243, 255, 0.92) 100%);
        }

        .staff-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            color: #334155;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 800;
        }

        .staff-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .staff-copy {
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 760px;
        }

        .staff-hero-bar {
            margin-top: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .staff-quick {
            display: inline-flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .staff-quick-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .65rem .9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.12);
            color: #475569;
            font-weight: 700;
            font-size: .84rem;
        }

        .staff-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .staff-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.15rem;
        }

        .staff-kpi::after {
            content: "";
            position: absolute;
            top: -26px;
            right: -26px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--staff-glow, rgba(59, 130, 246, 0.12));
        }

        .staff-kpi-label {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .35rem;
        }

        .staff-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .staff-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .82rem;
            margin: .35rem 0 0;
        }

        .staff-card {
            padding: 1.2rem;
        }

        .staff-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .staff-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .staff-card-copy {
            margin: .25rem 0 0;
            color: #64748b;
            line-height: 1.65;
            font-size: .9rem;
        }

        .staff-table-wrap {
            overflow: auto;
        }

        .staff-table {
            width: 100%;
            min-width: 920px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .staff-table thead th {
            color: #64748b;
            font-size: .79rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0 .95rem .2rem;
            white-space: nowrap;
        }

        .staff-table tbody tr {
            background: rgba(15, 23, 42, 0.04);
        }

        .staff-table tbody td {
            padding: 1rem .95rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .staff-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, 0.12);
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .staff-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, 0.12);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .staff-person-name {
            font-weight: 800;
            display: block;
        }

        .staff-person-meta,
        .staff-detail {
            color: #64748b;
            font-size: .82rem;
        }

        .staff-role-pill {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .38rem .7rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            background: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
        }

        .staff-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: flex-end;
        }

        .staff-actions .btn {
            border-radius: 999px;
        }

        html[data-bs-theme="dark"] .staff-hero,
        html[data-bs-theme="dark"] .staff-card,
        html[data-bs-theme="dark"] .staff-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .staff-hero {
            background:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .staff-eyebrow,
        html[data-bs-theme="dark"] .staff-quick-chip {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .staff-title,
        html[data-bs-theme="dark"] .staff-kpi-value,
        html[data-bs-theme="dark"] .staff-card-title,
        html[data-bs-theme="dark"] .staff-person-name {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .staff-copy,
        html[data-bs-theme="dark"] .staff-kpi-label,
        html[data-bs-theme="dark"] .staff-kpi-note,
        html[data-bs-theme="dark"] .staff-card-copy,
        html[data-bs-theme="dark"] .staff-person-meta,
        html[data-bs-theme="dark"] .staff-detail,
        html[data-bs-theme="dark"] .staff-table thead th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .staff-table tbody tr {
            background: rgba(148, 163, 184, 0.08);
        }

        html[data-bs-theme="dark"] .staff-table tbody td {
            border-color: rgba(148, 163, 184, 0.14);
        }

        html[data-bs-theme="dark"] .staff-role-pill {
            background: rgba(59, 130, 246, 0.16);
            color: #bfdbfe;
        }

        @media (max-width: 768px) {
            .staff-hero-bar,
            .staff-card-head {
                align-items: stretch;
            }

            .staff-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $adminCount = $users->filter(fn($user) => $user->role?->role_name === 'admin')->count();
        $cashierCount = $users->filter(fn($user) => $user->role?->role_name === 'cashier')->count();
        $chefCount = $users->filter(fn($user) => $user->role?->role_name === 'chef')->count();
    @endphp

    <div class="staff-shell">
        <section class="staff-hero">
            <span class="staff-eyebrow"><i class="bi bi-people-fill"></i> Team Management</span>
            <h1 class="staff-title">Manajemen Karyawan</h1>
            <p class="staff-copy">
                Kelola akun karyawan tenant dengan tampilan yang lebih nyaman. Dari sini Anda bisa melihat
                siapa yang bertugas sebagai admin, kasir, atau chef lalu memperbarui datanya dengan cepat.
            </p>

            <div class="staff-hero-bar">
                <div class="staff-quick">
                    <span class="staff-quick-chip"><i class="bi bi-shop"></i> {{ $currentTenant?->name ?? 'Tenant Aktif' }}</span>
                    <span class="staff-quick-chip"><i class="bi bi-people"></i> {{ $users->count() }} karyawan</span>
                </div>
                <a href="{{ route('users.create', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah User
                </a>
            </div>
        </section>

        <section class="staff-kpi-grid">
            <article class="staff-kpi" style="--staff-glow: rgba(59, 130, 246, 0.12);">
                <div class="staff-kpi-label">Total Karyawan</div>
                <p class="staff-kpi-value">{{ number_format($users->count(), 0, ',', '.') }}</p>
                <p class="staff-kpi-note">Seluruh akun staff tenant yang tercatat.</p>
            </article>
            <article class="staff-kpi" style="--staff-glow: rgba(249, 115, 22, 0.12);">
                <div class="staff-kpi-label">Admin</div>
                <p class="staff-kpi-value">{{ number_format($adminCount, 0, ',', '.') }}</p>
                <p class="staff-kpi-note">Mengelola tenant dan akses utama.</p>
            </article>
            <article class="staff-kpi" style="--staff-glow: rgba(34, 197, 94, 0.12);">
                <div class="staff-kpi-label">Kasir</div>
                <p class="staff-kpi-value">{{ number_format($cashierCount, 0, ',', '.') }}</p>
                <p class="staff-kpi-note">Menangani pembayaran dan status transaksi.</p>
            </article>
            <article class="staff-kpi" style="--staff-glow: rgba(168, 85, 247, 0.12);">
                <div class="staff-kpi-label">Chef</div>
                <p class="staff-kpi-value">{{ number_format($chefCount, 0, ',', '.') }}</p>
                <p class="staff-kpi-note">Berfokus pada proses masak dan penyelesaian order.</p>
            </article>
        </section>

        <section class="staff-card">
            <div class="staff-card-head">
                <div>
                    <h2 class="staff-card-title">Daftar Karyawan Tenant</h2>
                    <p class="staff-card-copy">Lihat data akun staff tenant lalu edit atau hapus langsung dari tabel ini.</p>
                </div>
            </div>

            <div class="staff-table-wrap">
                <table class="staff-table table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="staff-person-name">{{ $user['username'] }}</span>
                                    <div class="staff-person-meta">User #{{ $user->id }}</div>
                                </td>
                                <td>
                                    <span class="staff-person-name">{{ $user['fullname'] }}</span>
                                </td>
                                <td><span class="staff-detail">{{ $user['phone'] }}</span></td>
                                <td><span class="staff-detail">{{ $user['email'] }}</span></td>
                                <td>
                                    <span class="staff-role-pill">
                                        <i class="bi bi-shield-check"></i>
                                        {{ $user->role->role_name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="staff-actions">
                                        <a href="{{ route('users.edit', ['tenant' => $currentTenant->slug, 'user' => $user->id]) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('users.destroy', ['tenant' => $currentTenant->slug, 'user' => $user->id]) }}" method="POST"
                                            class="d-inline form-delete" data-name="{{ $user->fullname }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash3 me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('#table1');
            if (table) new simpleDatatables.DataTable(table, {
                searchable: true,
                perPageSelect: [5, 10, 20, 50],
                perPage: 10
            });

            document.querySelectorAll('.form-delete').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const name = form.dataset.name || 'user ini';
                    Swal.fire({
                        title: 'Hapus karyawan?',
                        html: `Akun <b>${name}</b> akan dihapus dari tenant ini.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-primary mx-2',
                            cancelButton: 'btn btn-light mx-2'
                        },
                        buttonsStyling: false,
                    }).then((r) => {
                        if (r.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });
        });
    </script>
@endsection

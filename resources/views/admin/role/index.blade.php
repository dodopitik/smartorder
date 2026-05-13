@extends('admin.layout.master')
@section('title', 'Manajemen Role')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .role-shell {
            display: grid;
            gap: 1.2rem;
        }

        .role-hero,
        .role-card,
        .role-kpi {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .role-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(168, 85, 247, 0.14), transparent 24%),
                linear-gradient(135deg, rgba(245, 243, 255, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(239, 246, 255, 0.92) 100%);
        }

        .role-eyebrow {
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

        .role-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .role-copy {
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 760px;
        }

        .role-hero-bar {
            margin-top: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .role-quick {
            display: inline-flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .role-quick-chip {
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

        .role-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .role-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.15rem;
        }

        .role-kpi::after {
            content: "";
            position: absolute;
            top: -26px;
            right: -26px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--role-glow, rgba(168, 85, 247, 0.12));
        }

        .role-kpi-label {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .35rem;
        }

        .role-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .role-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .82rem;
            margin: .35rem 0 0;
        }

        .role-card {
            padding: 1.2rem;
        }

        .role-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .role-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .role-card-copy {
            margin: .25rem 0 0;
            color: #64748b;
            line-height: 1.65;
            font-size: .9rem;
        }

        .role-table-wrap {
            overflow: auto;
        }

        .role-table {
            width: 100%;
            min-width: 720px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .role-table thead th {
            color: #64748b;
            font-size: .79rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0 .95rem .2rem;
            white-space: nowrap;
        }

        .role-table tbody tr {
            background: rgba(15, 23, 42, 0.04);
        }

        .role-table tbody td {
            padding: 1rem .95rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .role-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, 0.12);
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .role-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, 0.12);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .role-name {
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .38rem .7rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
        }

        .role-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: flex-end;
        }

        .role-actions .btn {
            border-radius: 999px;
        }

        html[data-bs-theme="dark"] .role-hero,
        html[data-bs-theme="dark"] .role-card,
        html[data-bs-theme="dark"] .role-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .role-hero {
            background:
                radial-gradient(circle at top right, rgba(168, 85, 247, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .role-eyebrow,
        html[data-bs-theme="dark"] .role-quick-chip,
        html[data-bs-theme="dark"] .role-pill {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .role-title,
        html[data-bs-theme="dark"] .role-kpi-value,
        html[data-bs-theme="dark"] .role-card-title,
        html[data-bs-theme="dark"] .role-name {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .role-copy,
        html[data-bs-theme="dark"] .role-kpi-label,
        html[data-bs-theme="dark"] .role-kpi-note,
        html[data-bs-theme="dark"] .role-card-copy,
        html[data-bs-theme="dark"] .role-table thead th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .role-table tbody tr {
            background: rgba(148, 163, 184, 0.08);
        }

        html[data-bs-theme="dark"] .role-table tbody td {
            border-color: rgba(148, 163, 184, 0.14);
        }

        @media (max-width: 768px) {
            .role-hero-bar,
            .role-card-head {
                align-items: stretch;
            }

            .role-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $roleCount = $roles->count();
        $coreRoles = $roles->whereIn('role_name', ['admin', 'cashier', 'chef'])->count();
    @endphp

    <div class="role-shell">
        <section class="role-hero">
            <span class="role-eyebrow"><i class="bi bi-shield-lock-fill"></i> Access Control</span>
            <h1 class="role-title">Manajemen Role</h1>
            <p class="role-copy">
                Atur struktur akses tenant lewat daftar role yang lebih rapi. Dari sini Anda bisa melihat
                role aktif, menambah role baru, dan menjaga pembagian akses tim tetap terstruktur.
            </p>

            <div class="role-hero-bar">
                <div class="role-quick">
                    <span class="role-quick-chip"><i class="bi bi-shop"></i> {{ $currentTenant?->name ?? 'Tenant Aktif' }}</span>
                    <span class="role-quick-chip"><i class="bi bi-shield-check"></i> {{ $roleCount }} role</span>
                </div>
                <a href="{{ route('roles.create', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Role
                </a>
            </div>
        </section>

        <section class="role-kpi-grid">
            <article class="role-kpi" style="--role-glow: rgba(168, 85, 247, 0.12);">
                <div class="role-kpi-label">Total Role</div>
                <p class="role-kpi-value">{{ number_format($roleCount, 0, ',', '.') }}</p>
                <p class="role-kpi-note">Seluruh role yang tersedia untuk panel admin.</p>
            </article>
            <article class="role-kpi" style="--role-glow: rgba(59, 130, 246, 0.12);">
                <div class="role-kpi-label">Role Inti Operasional</div>
                <p class="role-kpi-value">{{ number_format($coreRoles, 0, ',', '.') }}</p>
                <p class="role-kpi-note">Admin, kasir, dan chef yang umum dipakai tenant.</p>
            </article>
        </section>

        <section class="role-card">
            <div class="role-card-head">
                <div>
                    <h2 class="role-card-title">Daftar Role Tenant</h2>
                    <p class="role-card-copy">Lihat role yang tersedia lalu edit atau hapus sesuai kebutuhan pembagian akses kerja tenant.</p>
                </div>
            </div>

            <div class="role-table-wrap">
                <table class="role-table table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Role</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="role-name">
                                        <i class="bi bi-person-badge-fill"></i>
                                        {{ $role['role_name'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="role-actions">
                                        <a href="{{ route('roles.edit', ['tenant' => $currentTenant->slug, 'role' => $role->id]) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('roles.destroy', ['tenant' => $currentTenant->slug, 'role' => $role->id]) }}" method="POST"
                                            class="d-inline form-delete" data-name="{{ $role->role_name }}">
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
                    const name = form.dataset.name || 'role ini';
                    Swal.fire({
                        title: 'Hapus role?',
                        html: `Role <b>${name}</b> akan dihapus dari daftar akses tenant.`,
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

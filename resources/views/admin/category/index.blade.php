@extends('admin.layout.master')
@section('title', 'Manajemen Kategori')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .panel-shell {
            display: grid;
            gap: 1.2rem;
        }

        .panel-hero,
        .panel-card,
        .panel-kpi {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .panel-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.16), transparent 24%),
                linear-gradient(135deg, rgba(255, 247, 237, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(239, 246, 255, 0.92) 100%);
        }

        .panel-eyebrow {
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

        .panel-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .panel-copy {
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 760px;
        }

        .panel-hero-bar {
            margin-top: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .panel-quick {
            display: inline-flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .panel-quick-chip {
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

        .panel-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .panel-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.15rem;
        }

        .panel-kpi::after {
            content: "";
            position: absolute;
            top: -26px;
            right: -26px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--panel-glow, rgba(249, 115, 22, 0.12));
        }

        .panel-kpi-label {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .35rem;
        }

        .panel-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .panel-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .82rem;
            margin: .35rem 0 0;
        }

        .panel-card {
            padding: 1.2rem;
        }

        .panel-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .panel-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .panel-card-copy {
            margin: .25rem 0 0;
            color: #64748b;
            line-height: 1.65;
            font-size: .9rem;
        }

        .panel-table-wrap {
            overflow: auto;
        }

        .panel-table {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .panel-table thead th {
            color: #64748b;
            font-size: .79rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0 .95rem .2rem;
            white-space: nowrap;
        }

        .panel-table tbody tr {
            background: rgba(15, 23, 42, 0.04);
        }

        .panel-table tbody td {
            padding: 1rem .95rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .panel-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, 0.12);
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .panel-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, 0.12);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .category-name {
            font-weight: 800;
            display: block;
        }

        .category-meta {
            color: #64748b;
            font-size: .82rem;
            margin-top: .2rem;
        }

        .category-desc {
            max-width: 420px;
            color: #64748b;
            line-height: 1.6;
        }

        .panel-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: flex-end;
        }

        .panel-actions .btn {
            border-radius: 999px;
        }

        html[data-bs-theme="dark"] .panel-hero,
        html[data-bs-theme="dark"] .panel-card,
        html[data-bs-theme="dark"] .panel-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .panel-hero {
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .panel-eyebrow,
        html[data-bs-theme="dark"] .panel-quick-chip {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .panel-title,
        html[data-bs-theme="dark"] .panel-kpi-value,
        html[data-bs-theme="dark"] .panel-card-title,
        html[data-bs-theme="dark"] .category-name {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .panel-copy,
        html[data-bs-theme="dark"] .panel-kpi-label,
        html[data-bs-theme="dark"] .panel-kpi-note,
        html[data-bs-theme="dark"] .panel-card-copy,
        html[data-bs-theme="dark"] .category-meta,
        html[data-bs-theme="dark"] .category-desc,
        html[data-bs-theme="dark"] .panel-table thead th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .panel-table tbody tr {
            background: rgba(148, 163, 184, 0.08);
        }

        html[data-bs-theme="dark"] .panel-table tbody td {
            border-color: rgba(148, 163, 184, 0.14);
        }

        @media (max-width: 768px) {
            .panel-hero-bar,
            .panel-card-head {
                align-items: stretch;
            }

            .panel-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $describedCount = $categories->filter(fn($category) => filled($category->description))->count();
    @endphp

    <div class="panel-shell">
        <section class="panel-hero">
            <span class="panel-eyebrow"><i class="bi bi-tags-fill"></i> Category Management</span>
            <h1 class="panel-title">Manajemen Kategori</h1>
            <p class="panel-copy">
                Susun kategori menu tenant agar katalog customer lebih mudah dibaca. Halaman ini membantu
                tim menjaga struktur kategori tetap rapi, konsisten, dan siap dipakai di sisi customer.
            </p>

            <div class="panel-hero-bar">
                <div class="panel-quick">
                    <span class="panel-quick-chip"><i class="bi bi-shop"></i> {{ $currentTenant?->name ?? 'Tenant Aktif' }}</span>
                    <span class="panel-quick-chip"><i class="bi bi-tags"></i> {{ $categories->count() }} kategori</span>
                </div>
                <a href="{{ route('categories.create', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                </a>
            </div>
        </section>

        <section class="panel-kpi-grid">
            <article class="panel-kpi" style="--panel-glow: rgba(249, 115, 22, 0.12);">
                <div class="panel-kpi-label">Total Kategori</div>
                <p class="panel-kpi-value">{{ number_format($categories->count(), 0, ',', '.') }}</p>
                <p class="panel-kpi-note">Seluruh kategori aktif untuk tenant ini.</p>
            </article>
            <article class="panel-kpi" style="--panel-glow: rgba(59, 130, 246, 0.12);">
                <div class="panel-kpi-label">Kategori dengan Deskripsi</div>
                <p class="panel-kpi-value">{{ number_format($describedCount, 0, ',', '.') }}</p>
                <p class="panel-kpi-note">Membantu penjelasan kategori lebih informatif.</p>
            </article>
        </section>

        <section class="panel-card">
            <div class="panel-card-head">
                <div>
                    <h2 class="panel-card-title">Daftar Kategori Tenant</h2>
                    <p class="panel-card-copy">Lihat seluruh kategori yang dipakai tenant beserta deskripsinya, lalu edit atau hapus dari sini.</p>
                </div>
            </div>

            <div class="panel-table-wrap">
                <table class="panel-table table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="category-name">{{ $category->category_name }}</span>
                                    <div class="category-meta">Kategori #{{ $category->id }}</div>
                                </td>
                                <td>
                                    <div class="category-desc">{{ $category->description ?: 'Belum ada deskripsi kategori.' }}</div>
                                </td>
                                <td>
                                    <div class="panel-actions">
                                        <a href="{{ route('categories.edit', ['tenant' => $currentTenant->slug, 'category' => $category->id]) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', ['tenant' => $currentTenant->slug, 'category' => $category->id]) }}" method="POST"
                                            class="d-inline form-delete" data-name="{{ $category->category_name }}">
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
                    const name = form.dataset.name || 'kategori ini';
                    Swal.fire({
                        title: 'Hapus kategori?',
                        html: `Kategori <b>${name}</b> akan dihapus dari tenant ini.`,
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

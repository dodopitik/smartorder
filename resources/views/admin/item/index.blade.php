@extends('admin.layout.master')
@section('title', 'Daftar Menu')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .catalog-shell {
            display: grid;
            gap: 1.2rem;
        }

        .catalog-hero,
        .catalog-card,
        .catalog-kpi {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .catalog-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.16), transparent 24%),
                linear-gradient(135deg, rgba(255, 247, 237, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(239, 246, 255, 0.92) 100%);
        }

        .catalog-eyebrow {
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

        .catalog-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .catalog-copy {
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 760px;
        }

        .catalog-hero-bar {
            margin-top: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .catalog-quick {
            display: inline-flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .catalog-quick-chip {
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

        .catalog-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .catalog-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.15rem;
        }

        .catalog-kpi::after {
            content: "";
            position: absolute;
            top: -26px;
            right: -26px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--catalog-glow, rgba(249, 115, 22, 0.12));
        }

        .catalog-kpi-label {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .35rem;
        }

        .catalog-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .catalog-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .82rem;
            margin: .35rem 0 0;
        }

        .catalog-card {
            padding: 1.2rem;
        }

        .catalog-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .catalog-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .catalog-card-copy {
            margin: .25rem 0 0;
            color: #64748b;
            line-height: 1.65;
            font-size: .9rem;
        }

        .catalog-table-wrap {
            overflow: auto;
        }

        .catalog-table {
            width: 100%;
            min-width: 820px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .catalog-table thead th {
            color: #64748b;
            font-size: .79rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0 .95rem .2rem;
            white-space: nowrap;
        }

        .catalog-table tbody tr {
            background: rgba(15, 23, 42, 0.04);
        }

        .catalog-table tbody td {
            padding: 1rem .95rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .catalog-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, 0.12);
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .catalog-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, 0.12);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: .9rem;
            min-width: 240px;
        }

        .menu-item img {
            width: 74px;
            height: 74px;
            border-radius: 20px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid rgba(148, 163, 184, 0.12);
        }

        .menu-item-name {
            font-weight: 800;
            display: block;
        }

        .menu-item-meta {
            color: #64748b;
            font-size: .82rem;
            margin-top: .2rem;
        }

        .catalog-pill {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .38rem .7rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .catalog-pill.category {
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
        }

        .catalog-pill.available {
            background: rgba(22, 163, 74, 0.14);
            color: #15803d;
        }

        .catalog-pill.unavailable {
            background: rgba(220, 38, 38, 0.14);
            color: #b91c1c;
        }

        .catalog-price {
            font-weight: 900;
            letter-spacing: -.02em;
            white-space: nowrap;
        }

        .catalog-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: flex-end;
        }

        .catalog-actions .btn {
            border-radius: 999px;
        }

        html[data-bs-theme="dark"] .catalog-hero,
        html[data-bs-theme="dark"] .catalog-card,
        html[data-bs-theme="dark"] .catalog-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .catalog-hero {
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .catalog-eyebrow,
        html[data-bs-theme="dark"] .catalog-quick-chip,
        html[data-bs-theme="dark"] .catalog-pill.category {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .catalog-title,
        html[data-bs-theme="dark"] .catalog-kpi-value,
        html[data-bs-theme="dark"] .catalog-card-title,
        html[data-bs-theme="dark"] .menu-item-name,
        html[data-bs-theme="dark"] .catalog-price {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .catalog-copy,
        html[data-bs-theme="dark"] .catalog-kpi-label,
        html[data-bs-theme="dark"] .catalog-kpi-note,
        html[data-bs-theme="dark"] .catalog-card-copy,
        html[data-bs-theme="dark"] .menu-item-meta,
        html[data-bs-theme="dark"] .catalog-table thead th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .catalog-table tbody tr {
            background: rgba(148, 163, 184, 0.08);
        }

        html[data-bs-theme="dark"] .catalog-table tbody td,
        html[data-bs-theme="dark"] .menu-item img {
            border-color: rgba(148, 163, 184, 0.14);
        }

        @media (max-width: 768px) {
            .catalog-hero-bar,
            .catalog-card-head {
                align-items: stretch;
            }

            .catalog-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $availableItems = $items->where('is_available', 1)->count();
        $inactiveItems = $items->count() - $availableItems;
        $categoryTotal = $items->pluck('category.category_name')->filter()->unique()->count();
        $averagePrice = $items->count() > 0 ? (int) round($items->avg('price')) : 0;
    @endphp

    <div class="catalog-shell">
        <section class="catalog-hero">
            <span class="catalog-eyebrow"><i class="bi bi-journal-bookmark-fill"></i> Menu Management</span>
            <h1 class="catalog-title">Daftar Menu Tenant</h1>
            <p class="catalog-copy">
                Rapikan katalog makanan dan minuman tenant dengan tampilan yang lebih jelas. Dari sini tim bisa
                memantau item aktif, kategori, harga, dan langsung mengedit menu yang perlu diperbarui.
            </p>

            <div class="catalog-hero-bar">
                <div class="catalog-quick">
                    <span class="catalog-quick-chip"><i class="bi bi-shop"></i> {{ $currentTenant?->name ?? 'Tenant Aktif' }}</span>
                    <span class="catalog-quick-chip"><i class="bi bi-tags"></i> {{ $categoryTotal }} kategori</span>
                    <span class="catalog-quick-chip"><i class="bi bi-cash-stack"></i> Rata-rata Rp {{ number_format($averagePrice, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('items.create', ['tenant' => $currentTenant->slug]) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Menu
                </a>
            </div>
        </section>

        <section class="catalog-kpi-grid">
            <article class="catalog-kpi" style="--catalog-glow: rgba(249, 115, 22, 0.12);">
                <div class="catalog-kpi-label">Total Menu</div>
                <p class="catalog-kpi-value">{{ number_format($items->count(), 0, ',', '.') }}</p>
                <p class="catalog-kpi-note">Seluruh item yang tercatat di tenant ini.</p>
            </article>
            <article class="catalog-kpi" style="--catalog-glow: rgba(34, 197, 94, 0.12);">
                <div class="catalog-kpi-label">Menu Aktif</div>
                <p class="catalog-kpi-value">{{ number_format($availableItems, 0, ',', '.') }}</p>
                <p class="catalog-kpi-note">Siap tampil untuk customer tenant.</p>
            </article>
            <article class="catalog-kpi" style="--catalog-glow: rgba(220, 38, 38, 0.12);">
                <div class="catalog-kpi-label">Menu Nonaktif</div>
                <p class="catalog-kpi-value">{{ number_format($inactiveItems, 0, ',', '.') }}</p>
                <p class="catalog-kpi-note">Perlu dicek ulang sebelum diaktifkan.</p>
            </article>
            <article class="catalog-kpi" style="--catalog-glow: rgba(59, 130, 246, 0.12);">
                <div class="catalog-kpi-label">Kategori Dipakai</div>
                <p class="catalog-kpi-value">{{ number_format($categoryTotal, 0, ',', '.') }}</p>
                <p class="catalog-kpi-note">Membantu customer menjelajahi menu lebih cepat.</p>
            </article>
        </section>

        <section class="catalog-card">
            <div class="catalog-card-head">
                <div>
                    <h2 class="catalog-card-title">Katalog Menu</h2>
                    <p class="catalog-card-copy">Lihat seluruh item beserta gambar, kategori, status tampil, dan aksi cepat untuk edit atau hapus.</p>
                </div>
            </div>

            <div class="catalog-table-wrap">
                <table class="catalog-table table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="menu-item">
                                        <img src="{{ asset('img_item_upload/' . $item['image']) }}"
                                            alt="{{ $item['name'] }}"
                                            onerror="this.onerror=null;this.src='{{ $item['image'] }}';">
                                        <div>
                                            <span class="menu-item-name">{{ $item['name'] }}</span>
                                            <div class="menu-item-meta">ID Menu #{{ $item->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="catalog-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="catalog-pill category">
                                        <i class="bi bi-tag-fill"></i>
                                        {{ $item->category?->category_name ?? 'Tanpa kategori' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="catalog-pill {{ $item->is_available == 1 ? 'available' : 'unavailable' }}">
                                        <i class="bi {{ $item->is_available == 1 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        {{ $item->is_available == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="catalog-actions">
                                        <a href="{{ route('items.edit', ['tenant' => $currentTenant->slug, 'item' => $item->id]) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('items.destroy', ['tenant' => $currentTenant->slug, 'item' => $item->id]) }}"
                                            method="POST"
                                            class="d-inline form-delete"
                                            data-name="{{ $item->name }}">
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
            if (table) {
                new simpleDatatables.DataTable(table, {
                    searchable: true,
                    perPageSelect: [5, 10, 20, 50],
                    perPage: 10
                });
            }

            document.querySelectorAll('.form-delete').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const name = form.dataset.name || 'item ini';
                    Swal.fire({
                        title: 'Hapus menu?',
                        html: `Menu <b>${name}</b> akan dihapus dari katalog tenant.`,
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archana Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --muted: #475569;
            --soft: #f8fafc;
            --line: rgba(15, 23, 42, 0.08);
            --panel: rgba(255, 255, 255, 0.88);
            --accent: #f97316;
            --accent-deep: #c2410c;
            --sea: #0f766e;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Outfit", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.22), transparent 30%),
                radial-gradient(circle at 90% 10%, rgba(15, 118, 110, 0.16), transparent 24%),
                linear-gradient(135deg, #fff7ed 0%, #f8fafc 50%, #eef6ff 100%);
            min-height: 100vh;
        }

        a {
            color: inherit;
        }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 56px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            padding: 14px 18px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand strong {
            font-size: 1.05rem;
            letter-spacing: -0.02em;
        }

        .brand span {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.9fr);
            gap: 22px;
            margin-bottom: 22px;
        }

        .hero-main,
        .hero-side,
        .panel,
        .tenant-card {
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--panel);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
        }

        .hero-main {
            position: relative;
            overflow: hidden;
            padding: 32px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.92) 0%, rgba(255, 247, 237, 0.98) 55%, rgba(255, 255, 255, 0.92) 100%);
        }

        .hero-main::after {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 999px;
            right: -90px;
            top: -80px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.22), transparent 70%);
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-main h1 {
            font-size: clamp(2.5rem, 6vw, 5.25rem);
            line-height: 0.96;
            letter-spacing: -0.05em;
            margin: 18px 0 18px;
            max-width: 10ch;
        }

        .hero-main p {
            max-width: 62ch;
            font-size: 1.04rem;
            line-height: 1.8;
            color: var(--muted);
            margin: 0 0 22px;
        }

        .hero-cta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 999px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button-primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 14px 28px rgba(249, 115, 22, 0.28);
        }

        .button-secondary {
            background: rgba(15, 23, 42, 0.06);
            color: var(--ink);
        }

        .button-dark {
            background: var(--ink);
            color: #fff;
        }

        .mini-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .mini-stat {
            padding: 14px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        .mini-stat .label {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .mini-stat .value {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .hero-side {
            padding: 22px;
            background:
                linear-gradient(180deg, rgba(15, 23, 42, 0.96) 0%, rgba(30, 41, 59, 0.97) 100%);
            color: #fff;
        }

        .hero-side h2 {
            margin: 0 0 12px;
            font-size: 1.4rem;
        }

        .hero-side p {
            margin: 0 0 18px;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.8;
        }

        .flow-list {
            display: grid;
            gap: 12px;
        }

        .flow-item {
            padding: 14px 14px 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.09);
        }

        .flow-item strong {
            display: block;
            margin-bottom: 4px;
        }

        .flow-item span {
            display: block;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr);
            gap: 22px;
        }

        .panel {
            padding: 24px;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 14px;
            margin-bottom: 18px;
        }

        .section-head h3 {
            margin: 0 0 6px;
            font-size: 1.55rem;
        }

        .section-head p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .tenant-list {
            display: grid;
            gap: 16px;
        }

        .tenant-card {
            position: relative;
            overflow: hidden;
            padding: 22px;
            background: rgba(255, 255, 255, 0.92);
        }

        .tenant-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 8px;
            background: linear-gradient(180deg, var(--accent-local), var(--accent-soft-local));
        }

        .tenant-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .tenant-top h4 {
            margin: 0 0 4px;
            font-size: 1.35rem;
        }

        .tenant-path {
            color: var(--muted);
            font-size: 0.92rem;
            font-weight: 600;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .tenant-card p {
            margin: 0 0 16px;
            color: var(--muted);
            line-height: 1.8;
        }

        .tenant-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .quick-grid {
            display: grid;
            gap: 14px;
        }

        .quick-card {
            padding: 18px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        .quick-card h4 {
            margin: 0 0 8px;
            font-size: 1.04rem;
        }

        .quick-card p {
            margin: 0 0 12px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 0.96rem;
        }

        .quick-card code {
            display: inline-block;
            padding: 8px 10px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            font-size: 0.84rem;
            word-break: break-all;
        }

        .empty-state {
            padding: 18px;
            border-radius: 22px;
            border: 1px dashed rgba(15, 23, 42, 0.2);
            background: rgba(248, 250, 252, 0.8);
            color: var(--muted);
        }

        .footer-note {
            margin-top: 22px;
            text-align: center;
            color: var(--muted);
            font-size: 0.92rem;
        }

        @media (max-width: 980px) {
            .hero,
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .shell {
                width: min(100% - 20px, 1180px);
                padding-top: 16px;
            }

            .topbar {
                padding: 14px;
            }

            .hero-main,
            .hero-side,
            .panel,
            .tenant-card {
                border-radius: 24px;
            }

            .hero-main,
            .hero-side,
            .panel {
                padding: 20px;
            }

            .mini-stats {
                grid-template-columns: 1fr;
            }

            .topbar,
            .section-head,
            .tenant-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .top-actions,
            .hero-cta,
            .tenant-links {
                width: 100%;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="shell">
        <header class="topbar">
            <div class="brand">
                <strong>Archana Order</strong>
                <span>Portal masuk customer, admin tenant, dan super admin.</span>
            </div>
            <div class="top-actions">
                <a class="button button-secondary" href="#tenant-area">Lihat Tenant</a>
                <a class="button button-dark" href="{{ route('platform.login') }}">Login Super Admin</a>
            </div>
        </header>

        <section class="hero">
            <div class="hero-main">
                <span class="eyebrow">Multi Tenant Ordering Platform</span>
                <h1>Satu portal, tiga jalur masuk yang jelas.</h1>
                <p>
                    Customer cukup buka menu tenant dari QR meja. Admin tenant masuk ke panel outlet masing-masing.
                    Super admin mengelola tenant, billing, dan performa platform dari area terpisah.
                </p>

                <div class="hero-cta">
                    @if ($tenants->isNotEmpty())
                        <a class="button button-primary" href="{{ route('tenant.menu', ['tenant' => $tenants->first()->slug]) }}">
                            Buka Menu Customer
                        </a>
                        <a class="button button-secondary" href="{{ route('tenant.auth.login', ['tenant' => $tenants->first()->slug]) }}">
                            Login Admin Tenant
                        </a>
                    @endif
                    <a class="button button-dark" href="{{ route('platform.login') }}">Masuk Super Admin</a>
                </div>

                <div class="mini-stats">
                    <div class="mini-stat">
                        <div class="label">Tenant Aktif</div>
                        <div class="value">{{ $tenants->count() }}</div>
                    </div>
                    <div class="mini-stat">
                        <div class="label">Flow Customer</div>
                        <div class="value">QR ke Menu</div>
                    </div>
                    <div class="mini-stat">
                        <div class="label">Flow Admin</div>
                        <div class="value">Panel Terpisah</div>
                    </div>
                </div>
            </div>

            <aside class="hero-side">
                <h2>Flow yang Dipakai</h2>
                <p>Supaya aplikasi lebih mudah dibaca, landing ini sekarang menjelaskan jalur masuk berdasarkan peran.</p>
                <div class="flow-list">
                    <div class="flow-item">
                        <strong>Customer Tenant</strong>
                        <span>Buka `hotel/{tenant}` untuk lihat menu, keranjang, dan checkout dari QR meja.</span>
                    </div>
                    <div class="flow-item">
                        <strong>Admin Tenant</strong>
                        <span>Buka `hotel/{tenant}/admin/login` untuk kelola pesanan, menu, kategori, user, dan laporan outlet.</span>
                    </div>
                    <div class="flow-item">
                        <strong>Super Admin</strong>
                        <span>Buka `platform/login` untuk mengelola tenant, owner, billing, dan health bisnis platform.</span>
                    </div>
                </div>
            </aside>
        </section>

        <section class="content-grid" id="tenant-area">
            <div class="panel">
                <div class="section-head">
                    <div>
                        <h3>Tenant Aktif</h3>
                        <p>Pilih tenant yang ingin dibuka. Landing ini sengaja dibuat ringan supaya langsung mengarah ke jalur yang tepat.</p>
                    </div>
                </div>

                <div class="tenant-list">
                    @forelse ($tenants as $tenant)
                        <article class="tenant-card" style="--accent-local: {{ $tenant->primary_color ?? '#f97316' }}; --accent-soft-local: {{ $tenant->secondary_color ?? '#0f172a' }};">
                            <div class="tenant-top">
                                <div>
                                    <h4>{{ $tenant->name }}</h4>
                                    <div class="tenant-path">/hotel/{{ $tenant->slug }}</div>
                                </div>
                                <span class="status-pill">{{ $tenant->tagline ?: 'Outlet aktif' }}</span>
                            </div>

                            <p>{{ $tenant->description ?: 'Tenant siap menerima order customer dan dikelola dari panel admin terpisah.' }}</p>

                            <div class="tenant-links">
                                <a class="button button-primary" href="{{ route('tenant.menu', ['tenant' => $tenant->slug]) }}">Buka Menu</a>
                                <a class="button button-secondary" href="{{ route('tenant.auth.login', ['tenant' => $tenant->slug]) }}">Login Admin Tenant</a>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            Belum ada tenant aktif. Tambahkan tenant dari panel super admin agar customer dan admin tenant bisa masuk dari URL masing-masing.
                        </div>
                    @endforelse
                </div>
            </div>

            <aside class="panel">
                <div class="section-head">
                    <div>
                        <h3>Jalur Cepat</h3>
                        <p>Shortcut ini membantu Anda memahami URL yang dipakai tanpa harus menebak-nebak.</p>
                    </div>
                </div>

                <div class="quick-grid">
                    @if ($tenants->isNotEmpty())
                        @php($primaryTenant = $tenants->first())
                        <div class="quick-card">
                            <h4>Customer</h4>
                            <p>Gunakan URL ini untuk akses menu tenant dari QR atau browser.</p>
                            <code>{{ url('/hotel/' . $primaryTenant->slug) }}</code>
                        </div>

                        <div class="quick-card">
                            <h4>Admin Tenant</h4>
                            <p>Masuk ke panel operasional tenant untuk mengelola menu, pesanan, dan user.</p>
                            <code>{{ url('/hotel/' . $primaryTenant->slug . '/admin/login') }}</code>
                        </div>
                    @endif

                    <div class="quick-card">
                        <h4>Super Admin</h4>
                        <p>Masuk ke area platform untuk menambah tenant, memantau billing, dan mengecek penghasilan.</p>
                        <code>{{ url('/platform/login') }}</code>
                    </div>
                </div>
            </aside>
        </section>

        <div class="footer-note">
            Flow sekarang dipisah jelas: `customer`, `admin tenant`, dan `super admin`.
        </div>
    </main>
</body>

</html>

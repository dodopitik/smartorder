<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $currentTenant?->name ?? 'Tenant Menu' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="{{ $currentTenant?->description ?? 'Multi tenant ordering experience.' }}" name="description">

    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('assets/customer/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --tenant-primary: {{ $currentTenant?->primary_color ?? '#ff7a18' }};
            --tenant-secondary: {{ $currentTenant?->secondary_color ?? '#111827' }};
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(255, 214, 170, 0.42), transparent 24%),
                radial-gradient(circle at bottom right, rgba(251, 191, 36, 0.18), transparent 22%),
                linear-gradient(180deg, #fffaf4 0%, #fff2e2 100%);
        }

        .bg-happy,
        .btn-primary,
        .bg-primary {
            background-color: var(--tenant-primary) !important;
            border-color: var(--tenant-primary) !important;
        }

        .text-happy,
        .text-primary {
            color: var(--tenant-primary) !important;
        }

        .border-happy,
        .border-primary,
        .border-secondary {
            border-color: var(--tenant-primary) !important;
        }

        .tenant-hero {
            padding-top: 9.5rem;
            padding-bottom: 3rem;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.88), transparent 28%),
                radial-gradient(circle at 85% 20%, rgba(251, 191, 36, 0.18), transparent 22%),
                linear-gradient(135deg, #fff9f1 0%, #ffedd5 42%, #fdba74 100%);
            color: #1f2937;
        }

        .tenant-hero::before,
        .tenant-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .tenant-hero::before {
            width: 280px;
            height: 280px;
            top: -90px;
            right: -70px;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.22), transparent 68%);
        }

        .tenant-hero::after {
            width: 360px;
            height: 360px;
            bottom: -180px;
            left: -120px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.58), transparent 70%);
        }

        .tenant-hero > .container {
            position: relative;
            z-index: 1;
        }

        .tenant-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .tenant-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 251, 235, 0.92);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .88rem;
            font-weight: 700;
            color: #7c2d12;
            border: 1px solid rgba(251, 146, 60, 0.18);
            box-shadow: 0 10px 22px rgba(251, 146, 60, 0.12);
        }

        .tenant-hero-title {
            font-size: clamp(2.3rem, 5vw, 4.25rem);
            line-height: 1.02;
            letter-spacing: -.05em;
            color: #111827;
            text-shadow: 0 10px 30px rgba(255, 255, 255, 0.12);
        }

        .tenant-hero-copy {
            color: #7c4a2d !important;
            max-width: 640px;
            line-height: 1.7;
        }

        .tenant-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 1.2rem;
        }

        .tenant-hero-btn {
            min-height: 52px;
            padding: 0 1.2rem;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        .tenant-hero-btn.btn-light {
            background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%) !important;
            color: #111827 !important;
            border: 1px solid rgba(251, 146, 60, 0.14);
        }

        .tenant-summary-card {
            position: relative;
            overflow: hidden;
            padding: 1.6rem !important;
            background: linear-gradient(180deg, rgba(255, 251, 235, 0.96) 0%, rgba(255, 255, 255, 0.92) 100%);
            border: 1px solid rgba(251, 146, 60, 0.14);
            box-shadow: 0 24px 44px rgba(124, 58, 12, 0.12);
        }

        .tenant-summary-card::after {
            content: "";
            position: absolute;
            top: -34px;
            right: -28px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.12), transparent 68%);
            pointer-events: none;
        }

        .tenant-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .tenant-summary-item {
            position: relative;
            z-index: 1;
            padding: 1rem;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.78) 0%, rgba(255, 247, 237, 0.96) 100%);
            border: 1px solid rgba(251, 146, 60, 0.12);
        }

        .tenant-summary-item .label {
            color: #92400e;
            font-size: .82rem;
            margin-bottom: .35rem;
        }

        .tenant-summary-item .value {
            font-size: 1.7rem;
            font-weight: 900;
            letter-spacing: -.03em;
            color: #7c2d12;
            margin: 0;
        }

        .tenant-summary-copy {
            position: relative;
            z-index: 1;
            color: #7c4a2d;
            line-height: 1.65;
            margin: 1rem 0 0;
        }

        .tenant-navbar .nav-link {
            color: #111827 !important;
            font-weight: 600;
        }

        .tenant-navbar .nav-link:hover,
        .tenant-navbar .nav-link:focus {
            color: var(--tenant-secondary) !important;
        }

        .tenant-navbar .nav-link.active {
            color: var(--tenant-primary) !important;
        }

        .sticky-cart-bar {
            position: fixed;
            left: 16px;
            right: 16px;
            bottom: 16px;
            z-index: 1040;
        }

        .sticky-cart-inner {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            background: rgba(15, 23, 42, 0.94);
            color: #fff;
            border-radius: 20px;
            padding: 14px 16px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.26);
        }

        .sticky-cart-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 44px;
            padding: 0 18px;
            background: var(--tenant-primary);
            color: #fff;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
        }

        .menu-entry {
            height: 100%;
            overflow: hidden;
            position: relative;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .menu-entry:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 46px rgba(15, 23, 42, 0.12);
            border-color: color-mix(in srgb, var(--tenant-primary) 18%, rgba(15, 23, 42, 0.08));
        }

        .menu-entry-media {
            position: relative;
            aspect-ratio: 4 / 3;
            background:
                radial-gradient(circle at top right, color-mix(in srgb, var(--tenant-primary) 12%, transparent), transparent 45%),
                linear-gradient(135deg, rgba(15, 23, 42, 0.06), rgba(15, 23, 42, 0.02));
            overflow: hidden;
        }

        .menu-entry-media img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .menu-entry:hover .menu-entry-media img {
            transform: scale(1.04);
        }

        .menu-entry-body {
            min-height: 260px;
            position: relative;
        }

        .menu-entry-title {
            font-size: 1.18rem;
            line-height: 1.35;
            letter-spacing: -.02em;
        }

        .menu-entry-desc {
            font-size: .95rem;
            line-height: 1.65;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-entry-price {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(255, 237, 213, 0.95), rgba(254, 215, 170, 0.95));
            color: #9a3412;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: -.02em;
            box-shadow: inset 0 0 0 1px rgba(251, 146, 60, 0.08);
        }

        .menu-add-btn {
            min-height: 44px;
            padding: 0 .95rem;
            font-size: .92rem;
            background: linear-gradient(135deg, #f97316, #ea580c) !important;
            border-color: transparent !important;
            box-shadow: 0 12px 24px rgba(249, 115, 22, 0.2);
        }

        .menu-add-btn i {
            font-size: .9rem;
        }

        .menu-category-badge {
            background: rgba(124, 45, 18, 0.82);
            backdrop-filter: blur(10px);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .menu-grid {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        @media (max-width: 768px) {
            .tenant-hero {
                padding-top: 8.5rem;
                padding-bottom: 2.4rem;
            }

            .tenant-hero-title {
                font-size: clamp(1.95rem, 9vw, 2.8rem);
                margin-bottom: .85rem !important;
            }

            .tenant-hero-copy {
                font-size: .98rem !important;
                margin-bottom: 1rem !important;
            }

            .tenant-hero-actions {
                gap: .75rem;
            }

            .tenant-hero-btn {
                min-height: 46px;
                padding: 0 1rem;
                font-size: .92rem;
            }

            .tenant-summary-card {
                padding: 1.15rem !important;
            }

            .tenant-summary-grid {
                gap: .7rem;
            }

            .tenant-summary-item {
                padding: .85rem;
            }

            .tenant-summary-item .value {
                font-size: 1.35rem;
            }

            .menu-grid {
                --bs-gutter-x: .8rem;
                --bs-gutter-y: .9rem;
            }

            .tenant-card {
                border-radius: 20px;
            }

            .menu-entry-media {
                aspect-ratio: 1 / 1;
            }

            .menu-entry-body {
                min-height: auto;
                padding: .95rem !important;
                gap: .4rem;
            }

            .menu-entry-title {
                font-size: .98rem;
            }

            .menu-entry-desc {
                font-size: .82rem;
                line-height: 1.45;
                -webkit-line-clamp: 2;
                margin-bottom: .85rem !important;
            }

            .menu-entry-price {
                padding: .35rem .58rem;
                font-size: .86rem;
            }

            .menu-add-btn {
                min-height: 38px;
                padding: 0 .8rem;
                font-size: .82rem;
            }

            .menu-category-badge {
                margin: .6rem !important;
                padding: .4rem .6rem !important;
                font-size: .68rem;
            }

            .sticky-cart-inner {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Mobile navbar collapse fix */
        @media (max-width: 1199.98px) {
            .tenant-navbar .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(12px);
                border-radius: 16px;
                margin-top: 12px;
                padding: 1rem 1.25rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
                box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1);
            }

            .tenant-navbar .navbar-nav {
                gap: 0 !important;
            }

            .tenant-navbar .navbar-nav .nav-link {
                padding: 0.75rem 0.5rem;
                border-bottom: 1px solid rgba(15, 23, 42, 0.05);
            }

            .tenant-navbar .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }

            .tenant-navbar .navbar-toggler {
                border: 1px solid rgba(15, 23, 42, 0.1);
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.8);
            }

            .tenant-navbar .navbar-toggler:focus {
                box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
            }
        }

        /* Footer styling */
        .tenant-footer {
            background: #FFB366 !important;
            color: #1f2937;
        }

        .tenant-footer a {
            color: #7c2d12;
            text-decoration: none;
        }

        .tenant-footer a:hover {
            color: #111827;
        }
    </style>
</head>

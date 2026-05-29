<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ Auth::check() && Auth::user()->role->role_name === 'super_admin' ? 'Platform Console' : ($currentTenant?->name ?? 'Tenant Dashboard') }} </title>


    <link rel="stylesheet" crossorigin href="{{ asset('assets/admin/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/admin/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/admin/compiled/css/iconly.css') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo/archana1.png') }}">

    <style>
        :root {
            --admin-accent: #f97316;
            --admin-accent-soft: rgba(249, 115, 22, 0.14);
            --admin-bg: #f5f7fb;
            --admin-surface: rgba(255, 255, 255, 0.88);
            --admin-surface-strong: #ffffff;
            --admin-border: rgba(15, 23, 42, 0.08);
            --admin-text: #0f172a;
            --admin-muted: #64748b;
            --admin-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            --admin-sidebar-bg: linear-gradient(180deg, #0f172a 0%, #162033 100%);
            --admin-sidebar-text: rgba(226, 232, 240, 0.78);
            --admin-sidebar-active: #ffffff;
            --admin-sidebar-hover: rgba(255, 255, 255, 0.06);
        }

        html[data-bs-theme="dark"] {
            --admin-accent: #fb923c;
            --admin-accent-soft: rgba(251, 146, 60, 0.18);
            --admin-bg: #0b1120;
            --admin-surface: rgba(15, 23, 42, 0.88);
            --admin-surface-strong: #111c34;
            --admin-border: rgba(148, 163, 184, 0.18);
            --admin-text: #e2e8f0;
            --admin-muted: #94a3b8;
            --admin-shadow: 0 24px 60px rgba(2, 6, 23, 0.45);
            --admin-sidebar-bg: linear-gradient(180deg, #020617 0%, #0f172a 100%);
            --admin-sidebar-text: rgba(226, 232, 240, 0.74);
            --admin-sidebar-active: #ffffff;
            --admin-sidebar-hover: rgba(148, 163, 184, 0.12);
        }

        html,
        body {
            background: var(--admin-bg);
            color: var(--admin-text);
        }

        body {
            min-height: 100vh;
            background-image:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 26%),
                radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.08), transparent 28%);
        }

        #main {
            background: transparent;
            min-height: 100vh;
        }

        .page-heading,
        .page-content,
        .section {
            color: var(--admin-text);
        }

        .card,
        .modal-content,
        .swal2-popup,
        .platform-card,
        .platform-kpi,
        .tenant-card {
            background: var(--admin-surface) !important;
            backdrop-filter: blur(14px);
            border: 1px solid var(--admin-border) !important;
            box-shadow: var(--admin-shadow);
        }

        .card,
        .platform-card,
        .platform-kpi {
            border-radius: 24px;
        }

        .card .card-header,
        .modal-header {
            border-color: var(--admin-border) !important;
        }

        .table,
        .table td,
        .table th,
        .text-muted,
        .text-subtitle,
        .small {
            color: inherit;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(148, 163, 184, 0.06);
            --bs-table-striped-color: var(--admin-text);
            --bs-table-color: var(--admin-text);
            --bs-table-border-color: var(--admin-border);
        }

        .table thead th,
        .platform-table th {
            color: var(--admin-muted) !important;
            font-weight: 700;
        }

        .alert {
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            box-shadow: var(--admin-shadow);
        }

        .btn-primary,
        .swal2-confirm.btn-primary,
        .theme-toggle.is-dark {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: transparent;
            box-shadow: 0 12px 30px rgba(249, 115, 22, 0.22);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #fb923c, #f97316);
            border-color: transparent;
        }

        .btn-light,
        .btn-outline-secondary,
        .btn-outline-primary,
        .btn-outline-danger,
        .btn-outline-dark,
        .theme-toggle {
            border-radius: 999px;
        }

        .breadcrumb-item a,
        a {
            color: inherit;
        }

        .sidebar-wrapper {
            background: var(--admin-sidebar-bg) !important;
            border-right: 1px solid rgba(148, 163, 184, 0.14);
            box-shadow: 18px 0 45px rgba(15, 23, 42, 0.16);
        }

        .sidebar-header {
            padding: 1.2rem 1.2rem .75rem;
        }

        .sidebar-brand-card {
            padding: .75rem .8rem .8rem;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .sidebar-brand-shell {
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        .sidebar-brand-title {
            font-size: .92rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: #fff;
        }

        .sidebar-brand-subtitle {
            color: var(--admin-sidebar-text);
            font-size: .75rem;
        }

        .sidebar-menu {
            padding: .4rem .7rem 1rem;
        }

        .menu {
            display: grid;
            gap: .15rem;
        }

        .sidebar-section-label {
            display: flex;
            align-items: center;
            gap: .45rem;
            padding: .7rem .6rem .25rem;
            color: rgba(148, 163, 184, 0.88);
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 800;
        }

        .sidebar-section-label::before {
            content: "";
            width: 14px;
            height: 1px;
            background: rgba(148, 163, 184, 0.32);
            flex-shrink: 0;
        }

        .sidebar-wrapper .menu .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: .65rem;
            border-radius: 14px;
            color: var(--admin-sidebar-text);
            padding: .6rem .8rem;
            transition: all .2s ease;
        }

        .sidebar-link-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: inherit;
            font-size: .88rem;
            flex-shrink: 0;
            transition: inherit;
        }

        .sidebar-link-copy {
            display: flex;
            flex-direction: column;
            gap: .1rem;
            min-width: 0;
        }

        .sidebar-link-title {
            color: inherit;
            font-weight: 600;
            font-size: .88rem;
            line-height: 1.2;
        }

        .sidebar-wrapper .menu .sidebar-link:hover {
            background: var(--admin-sidebar-hover);
            color: var(--admin-sidebar-active);
        }

        .sidebar-wrapper .menu .sidebar-link:hover .sidebar-link-icon {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .sidebar-wrapper .menu .sidebar-item.active > .sidebar-link,
        .sidebar-wrapper .menu .sidebar-link.active {
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(249, 115, 22, 0.1));
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(251, 146, 60, 0.18);
        }

        .sidebar-wrapper .menu .sidebar-item.active > .sidebar-link::before {
            content: "";
            position: absolute;
            top: 50%;
            left: .35rem;
            width: 3px;
            height: 20px;
            border-radius: 999px;
            transform: translateY(-50%);
            background: linear-gradient(180deg, #fdba74, #f97316);
        }

        .sidebar-wrapper .menu .sidebar-item.active > .sidebar-link .sidebar-link-icon,
        .sidebar-wrapper .menu .sidebar-link.active .sidebar-link-icon {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-wrapper .menu .sidebar-link i,
        .sidebar-wrapper .menu .sidebar-link span {
            color: inherit !important;
        }

        .sidebar-footer-card {
            margin: .75rem .7rem 1rem;
            padding: .8rem;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.18), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(251, 146, 60, 0.16);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .sidebar-footer-eyebrow {
            color: rgba(255, 237, 213, 0.82);
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .3rem;
            font-weight: 800;
        }

        .sidebar-footer-title {
            color: #fff;
            font-weight: 800;
            font-size: .85rem;
            margin: 0 0 .25rem;
            line-height: 1.35;
        }

        .sidebar-footer-copy {
            color: rgba(226, 232, 240, 0.82);
            font-size: .72rem;
            margin: 0;
            line-height: 1.55;
        }

        .sidebar-footer-meta {
            margin-top: .55rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: rgba(255, 237, 213, 0.88);
            font-size: .7rem;
            font-weight: 700;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .55rem .85rem;
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            background: var(--admin-surface);
            backdrop-filter: blur(14px);
            box-shadow: var(--admin-shadow);
            margin-bottom: 1rem;
        }

        .admin-topbar-meta {
            display: flex;
            align-items: center;
            gap: .85rem;
            min-width: 0;
        }

        .admin-topbar-title {
            margin: 0;
            font-size: clamp(.95rem, 1.6vw, 1.1rem);
            font-weight: 800;
            color: var(--admin-text);
            line-height: 1.2;
        }

        .admin-topbar-subtitle {
            margin: .05rem 0 0;
            color: var(--admin-muted);
            font-size: .82rem;
            line-height: 1.3;
        }

        .admin-user-chip {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .4rem .7rem;
            border-radius: 14px;
            background: var(--admin-accent-soft);
            color: var(--admin-text);
            max-width: 100%;
        }

        .admin-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            font-weight: 800;
            font-size: .88rem;
            flex-shrink: 0;
        }

        .admin-user-text {
            min-width: 0;
        }

        .admin-user-role {
            display: block;
            color: var(--admin-muted);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            line-height: 1.2;
        }

        .admin-user-name {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 700;
            font-size: .9rem;
            line-height: 1.2;
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .5rem .9rem;
            min-height: 40px;
            border-radius: 999px;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            color: var(--admin-text);
            font-weight: 700;
            font-size: .85rem;
            line-height: 1;
            cursor: pointer;
            transition: transform .18s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .theme-toggle:hover {
            border-color: rgba(249, 115, 22, 0.45);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.12);
        }

        .theme-toggle:active {
            transform: scale(.97);
        }

        .theme-toggle i {
            font-size: 1.1rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
        }

        .theme-toggle.is-dark {
            color: #fff;
        }

        .theme-toggle.is-dark:hover {
            box-shadow: 0 10px 26px rgba(249, 115, 22, 0.3);
        }

        .theme-toggle-label {
            line-height: 1;
        }

        .page-heading .page-title h3,
        .page-heading h3,
        .page-title h3 {
            color: var(--admin-text);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .page-heading .page-title p,
        .page-title p,
        .text-subtitle,
        .platform-empty,
        .sidebar-brand-subtitle {
            color: var(--admin-muted) !important;
        }

        .modal-content {
            border-radius: 24px;
            overflow: hidden;
        }

        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        }

        .burger-btn i {
            color: var(--admin-text);
        }

        html[data-bs-theme="dark"] .card,
        html[data-bs-theme="dark"] .card-header,
        html[data-bs-theme="dark"] .card-body,
        html[data-bs-theme="dark"] .modal-content,
        html[data-bs-theme="dark"] .modal-body,
        html[data-bs-theme="dark"] .modal-footer,
        html[data-bs-theme="dark"] .dataTable-wrapper,
        html[data-bs-theme="dark"] .dataTable-container,
        html[data-bs-theme="dark"] .dataTable-top,
        html[data-bs-theme="dark"] .dataTable-bottom,
        html[data-bs-theme="dark"] .bg-white,
        html[data-bs-theme="dark"] .bg-light {
            background: var(--admin-surface-strong) !important;
            color: var(--admin-text) !important;
            border-color: var(--admin-border) !important;
        }

        html[data-bs-theme="dark"] .btn-light,
        html[data-bs-theme="dark"] .btn-outline-secondary,
        html[data-bs-theme="dark"] .btn-outline-primary,
        html[data-bs-theme="dark"] .btn-outline-danger,
        html[data-bs-theme="dark"] .btn-outline-dark,
        html[data-bs-theme="dark"] .theme-toggle {
            background: #162033 !important;
            color: var(--admin-text) !important;
            border-color: rgba(148, 163, 184, 0.22) !important;
        }

        html[data-bs-theme="dark"] .btn-light:hover,
        html[data-bs-theme="dark"] .btn-outline-secondary:hover,
        html[data-bs-theme="dark"] .btn-outline-primary:hover,
        html[data-bs-theme="dark"] .btn-outline-danger:hover,
        html[data-bs-theme="dark"] .btn-outline-dark:hover,
        html[data-bs-theme="dark"] .theme-toggle:hover {
            background: #1d2940 !important;
            color: #f8fafc !important;
        }

        /* Dark mode untuk app bar mobile (burger, theme, brand) */
        html[data-bs-theme="dark"] .admin-mobile-bar {
            background: var(--admin-surface-strong);
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-bs-theme="dark"] .admin-burger,
        html[data-bs-theme="dark"] .admin-mobile-theme {
            background: #162033;
            border-color: rgba(148, 163, 184, 0.22);
        }

        html[data-bs-theme="dark"] .admin-burger span {
            background: #e2e8f0;
        }

        html[data-bs-theme="dark"] .admin-burger:hover,
        html[data-bs-theme="dark"] .admin-mobile-theme:hover {
            border-color: rgba(251, 146, 60, 0.5);
        }

        html[data-bs-theme="dark"] .admin-burger.is-active {
            background: rgba(251, 146, 60, 0.18);
            border-color: rgba(251, 146, 60, 0.5);
        }

        html[data-bs-theme="dark"] .admin-burger.is-active span {
            background: #fb923c;
        }

        html[data-bs-theme="dark"] .alert-success {
            background: rgba(22, 163, 74, 0.14) !important;
            color: #bbf7d0 !important;
            border-color: rgba(34, 197, 94, 0.22) !important;
        }

        html[data-bs-theme="dark"] .alert-danger {
            background: rgba(220, 38, 38, 0.14) !important;
            color: #fecaca !important;
            border-color: rgba(248, 113, 113, 0.22) !important;
        }

        html[data-bs-theme="dark"] .badge.bg-secondary {
            background: #24324d !important;
            color: #e2e8f0 !important;
        }

        html[data-bs-theme="dark"] .badge.bg-warning {
            background: rgba(245, 158, 11, 0.18) !important;
            color: #fde68a !important;
        }

        html[data-bs-theme="dark"] .badge.bg-info {
            background: rgba(59, 130, 246, 0.18) !important;
            color: #bfdbfe !important;
        }

        html[data-bs-theme="dark"] .badge.bg-success {
            background: rgba(22, 163, 74, 0.18) !important;
            color: #bbf7d0 !important;
        }

        html[data-bs-theme="dark"] .form-control,
        html[data-bs-theme="dark"] .form-select,
        html[data-bs-theme="dark"] .form-check-input,
        html[data-bs-theme="dark"] input,
        html[data-bs-theme="dark"] textarea,
        html[data-bs-theme="dark"] select {
            background-color: #111c34 !important;
            color: var(--admin-text) !important;
            border-color: rgba(148, 163, 184, 0.22) !important;
        }

        html[data-bs-theme="dark"] .form-control::placeholder,
        html[data-bs-theme="dark"] textarea::placeholder {
            color: #7c8ba1 !important;
        }

        html[data-bs-theme="dark"] .swal2-popup {
            background: #111c34 !important;
            color: var(--admin-text) !important;
        }

        html[data-bs-theme="dark"] .swal2-title,
        html[data-bs-theme="dark"] .swal2-html-container {
            color: var(--admin-text) !important;
        }

        html[data-bs-theme="dark"] .sidebar-brand-card {
            background: linear-gradient(135deg, rgba(148, 163, 184, 0.08), rgba(255, 255, 255, 0.02));
            border-color: rgba(148, 163, 184, 0.14);
        }

        html[data-bs-theme="dark"] .sidebar-footer-card {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.16), rgba(17, 24, 39, 0.18));
            border-color: rgba(251, 146, 60, 0.14);
        }

        /* ===== RESPONSIVE: Tablet (max-width: 991.98px) ===== */
        @media (max-width: 991.98px) {
            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
                padding: .85rem 1rem;
                border-radius: 20px;
                margin-bottom: 1rem;
            }

            .admin-topbar-meta {
                flex-direction: column;
                align-items: stretch;
                gap: .75rem;
            }

            .admin-topbar-title {
                font-size: 1.1rem;
            }

            .admin-topbar-subtitle {
                font-size: .85rem;
            }

            .admin-user-chip {
                padding: .65rem .85rem;
                border-radius: 14px;
            }

            .admin-user-avatar {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                font-size: .85rem;
            }

            .admin-user-name {
                font-size: .9rem;
            }

            .theme-toggle {
                padding: .65rem .85rem;
                font-size: .88rem;
                align-self: flex-start;
            }

            #main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                padding-top: 1rem !important;
            }

            .page-heading h3,
            .page-heading .page-title h3,
            .page-title h3 {
                font-size: 1.4rem;
            }

            .page-content {
                padding: 0;
            }
        }

        /* ===== RESPONSIVE: Mobile (max-width: 767.98px) ===== */
        @media (max-width: 767.98px) {
            .admin-topbar {
                padding: .75rem .85rem;
                border-radius: 18px;
                gap: .75rem;
            }

            .admin-topbar-title {
                font-size: 1rem;
            }

            .admin-topbar-subtitle {
                font-size: .8rem;
                line-height: 1.5;
            }

            .admin-user-chip {
                padding: .55rem .75rem;
                gap: .6rem;
            }

            .admin-user-avatar {
                width: 32px;
                height: 32px;
                border-radius: 10px;
                font-size: .8rem;
            }

            .admin-user-name {
                font-size: .85rem;
            }

            .admin-user-role {
                font-size: .72rem;
            }

            .theme-toggle {
                padding: .55rem .75rem;
                font-size: .82rem;
            }

            .page-heading h3,
            .page-heading .page-title h3,
            .page-title h3 {
                font-size: 1.2rem;
            }

            .page-heading p,
            .page-title p {
                font-size: .85rem;
            }

            .card {
                border-radius: 18px;
            }

            .modal-content {
                border-radius: 18px;
                margin: .5rem;
            }

            .alert {
                border-radius: 14px;
                padding: .75rem 1rem;
                font-size: .88rem;
            }

            footer .clearfix {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: .25rem;
            }

            footer .float-start,
            footer .float-end {
                float: none !important;
            }
        }

        /* ===== RESPONSIVE: Small Mobile (max-width: 479.98px) ===== */
        @media (max-width: 479.98px) {
            #main {
                padding-left: .75rem !important;
                padding-right: .75rem !important;
            }

            .admin-mobile-bar {
                padding: .5rem .6rem;
                border-radius: 16px;
            }

            .admin-mobile-title {
                font-size: .88rem;
            }

            .admin-mobile-eyebrow {
                font-size: .58rem;
            }

            .admin-burger {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .admin-mobile-theme,
            .admin-mobile-avatar {
                width: 36px;
                height: 36px;
                border-radius: 11px;
                font-size: .85rem;
            }

            .admin-topbar {
                padding: .65rem .7rem;
                border-radius: 16px;
            }

            .admin-topbar-title {
                font-size: .92rem;
            }

            .admin-topbar-subtitle {
                font-size: .76rem;
            }

            .admin-user-chip {
                padding: .5rem .65rem;
                border-radius: 12px;
            }

            .admin-user-avatar {
                width: 28px;
                height: 28px;
                font-size: .72rem;
            }

            .admin-user-name {
                font-size: .8rem;
            }

            .theme-toggle {
                padding: .5rem .65rem;
                font-size: .78rem;
            }

            .theme-toggle-label {
                display: none;
            }

            .page-heading h3,
            .page-heading .page-title h3,
            .page-title h3 {
                font-size: 1.05rem;
            }
        }

        /* ===== RESPONSIVE: Sidebar Mobile Improvements ===== */
        /* Selector disamakan/dilebihkan dari Mazer:
           - app.css      : #sidebar .sidebar-wrapper{left:-300px}
           - app-dark.css : html[data-bs-theme=dark] #sidebar .sidebar-wrapper{left:-300px}
           Kita override 'left' di kedua tema lalu kontrol via transform. */
        @media (max-width: 1199.98px) {
            html #sidebar .sidebar-wrapper,
            html[data-bs-theme="dark"] #sidebar .sidebar-wrapper,
            html[data-bs-theme="light"] #sidebar .sidebar-wrapper {
                position: fixed;
                top: 0;
                left: 0 !important;
                height: 100vh;
                height: 100dvh;
                z-index: 1055;
                width: 264px;
                transform: translateX(-100%);
                transition: transform .32s cubic-bezier(.4, 0, .2, 1);
                overflow-y: auto;
            }

            html #sidebar .sidebar-wrapper.sidebar-open,
            html[data-bs-theme="dark"] #sidebar .sidebar-wrapper.sidebar-open,
            html[data-bs-theme="light"] #sidebar .sidebar-wrapper.sidebar-open {
                transform: translateX(0);
                box-shadow: 24px 0 70px rgba(2, 6, 23, 0.45);
            }

            .sidebar-brand-card {
                padding: .65rem .7rem;
                border-radius: 14px;
            }

            .sidebar-brand-title {
                font-size: .85rem;
            }

            .sidebar-brand-subtitle {
                font-size: .72rem;
            }

            .sidebar-footer-card {
                margin: .6rem .6rem .85rem;
                padding: .7rem;
                border-radius: 12px;
            }

            .sidebar-footer-title {
                font-size: .8rem;
            }

            .sidebar-footer-copy {
                font-size: .68rem;
            }

            .sidebar-footer-meta {
                font-size: .66rem;
            }

            #main {
                padding-top: 0;
            }
        }

        /* ===== Sidebar Backdrop Overlay ===== */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1054;
            background: rgba(2, 6, 23, 0.55);
            backdrop-filter: blur(3px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 1200px) {
            .sidebar-backdrop {
                display: none;
            }
        }

        body.sidebar-locked {
            overflow: hidden;
        }

        /* ===== Mobile App Bar ===== */
        .admin-mobile-bar {
            display: none;
            align-items: center;
            gap: .7rem;
            padding: .6rem .7rem;
            margin-bottom: 1rem;
            border-radius: 18px;
            background: var(--admin-surface);
            backdrop-filter: blur(14px);
            border: 1px solid var(--admin-border);
            box-shadow: var(--admin-shadow);
            position: sticky;
            top: .5rem;
            z-index: 1040;
        }

        @media (max-width: 1199.98px) {
            .admin-mobile-bar {
                display: flex;
            }

            /* Topbar berat digantikan app bar mobile yang lebih ringkas */
            .admin-topbar {
                display: none;
            }
        }

        .admin-mobile-brand {
            display: flex;
            flex-direction: column;
            gap: .08rem;
            min-width: 0;
            flex: 1;
        }

        .admin-mobile-eyebrow {
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: var(--admin-accent);
            line-height: 1;
        }

        .admin-mobile-title {
            font-size: .98rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--admin-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .admin-mobile-actions {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-shrink: 0;
        }

        /* Tombol ikon di app bar (burger, theme, avatar) — semua 44x44 biar simetris */
        .admin-burger,
        .admin-mobile-theme,
        .admin-mobile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            padding: 0;
            transition: transform .18s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .admin-mobile-theme {
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            color: var(--admin-text);
            font-size: 1.15rem;
            cursor: pointer;
        }

        .admin-mobile-theme i {
            line-height: 1;
            display: block;
        }

        .admin-mobile-theme:hover {
            border-color: rgba(249, 115, 22, 0.45);
            color: var(--admin-accent);
        }

        .admin-mobile-theme:active {
            transform: scale(.92);
        }

        .admin-mobile-avatar {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 8px 18px rgba(249, 115, 22, 0.32);
        }

        /* ===== Animated Burger Button (3 garis presisi center, jadi X saat aktif) ===== */
        .admin-burger {
            position: relative;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            cursor: pointer;
        }

        .admin-burger:hover {
            border-color: rgba(249, 115, 22, 0.45);
        }

        .admin-burger:active {
            transform: scale(.92);
        }

        /* Garis burger digambar absolut & center sempurna pakai transform */
        .admin-burger span {
            position: absolute;
            left: 50%;
            top: 50%;
            display: block;
            width: 19px;
            height: 2.5px;
            border-radius: 999px;
            background: var(--admin-text);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1), opacity .2s ease, background .2s ease;
        }

        .admin-burger span:nth-child(1) {
            transform: translate(-50%, -50%) translateY(-6px);
        }

        .admin-burger span:nth-child(2) {
            transform: translate(-50%, -50%);
        }

        .admin-burger span:nth-child(3) {
            transform: translate(-50%, -50%) translateY(6px);
        }

        .admin-burger.is-active {
            background: var(--admin-accent-soft);
            border-color: rgba(249, 115, 22, 0.45);
        }

        .admin-burger.is-active span {
            background: var(--admin-accent);
        }

        .admin-burger.is-active span:nth-child(1) {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .admin-burger.is-active span:nth-child(2) {
            opacity: 0;
        }

        .admin-burger.is-active span:nth-child(3) {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        /* ===== RESPONSIVE: Table Improvements ===== */
        @media (max-width: 767.98px) {
            .table-responsive,
            .orders-table-wrap,
            [class*="table-wrap"] {
                margin: 0 -.5rem;
                padding: 0 .5rem;
                border-radius: 14px;
            }

            .table {
                font-size: .85rem;
            }

            .table td,
            .table th {
                padding: .65rem .5rem;
            }

            .btn {
                font-size: .82rem;
                padding: .4rem .75rem;
            }

            .btn-sm {
                font-size: .76rem;
                padding: .3rem .6rem;
            }
        }

        /* ===== RESPONSIVE: Form Controls ===== */
        @media (max-width: 767.98px) {
            .form-control,
            .form-select {
                font-size: .88rem;
                padding: .55rem .75rem;
                border-radius: 12px;
            }

            .form-label {
                font-size: .85rem;
            }
        }
    </style>


    @yield('css')
</head>

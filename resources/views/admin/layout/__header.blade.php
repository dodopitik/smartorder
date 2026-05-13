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
            padding: 1.6rem 1.5rem 1rem;
        }

        .sidebar-brand-card {
            padding: 1rem 1rem 1.05rem;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .sidebar-brand-shell {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }

        .sidebar-brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: #fff;
        }

        .sidebar-brand-subtitle {
            color: var(--admin-sidebar-text);
            font-size: .82rem;
        }

        .sidebar-menu {
            padding: .6rem .9rem 1.2rem;
        }

        .menu {
            display: grid;
            gap: .3rem;
        }

        .sidebar-section-label {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .9rem .7rem .35rem;
            color: rgba(148, 163, 184, 0.88);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 800;
        }

        .sidebar-section-label::before {
            content: "";
            width: 18px;
            height: 1px;
            background: rgba(148, 163, 184, 0.32);
            flex-shrink: 0;
        }

        .sidebar-wrapper .menu .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: .85rem;
            border-radius: 18px;
            color: var(--admin-sidebar-text);
            padding: .85rem 1rem;
            transition: all .2s ease;
        }

        .sidebar-link-icon {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: inherit;
            font-size: 1rem;
            flex-shrink: 0;
            transition: inherit;
        }

        .sidebar-link-copy {
            display: flex;
            flex-direction: column;
            gap: .14rem;
            min-width: 0;
        }

        .sidebar-link-title {
            color: inherit;
            font-weight: 700;
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
            left: .45rem;
            width: 4px;
            height: 24px;
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
            margin: 1rem .95rem 1.2rem;
            padding: 1rem;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.18), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(251, 146, 60, 0.16);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .sidebar-footer-eyebrow {
            color: rgba(255, 237, 213, 0.82);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .45rem;
            font-weight: 800;
        }

        .sidebar-footer-title {
            color: #fff;
            font-weight: 800;
            margin: 0 0 .35rem;
            line-height: 1.35;
        }

        .sidebar-footer-copy {
            color: rgba(226, 232, 240, 0.82);
            font-size: .8rem;
            margin: 0;
            line-height: 1.6;
        }

        .sidebar-footer-meta {
            margin-top: .8rem;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: rgba(255, 237, 213, 0.88);
            font-size: .78rem;
            font-weight: 700;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            background: var(--admin-surface);
            backdrop-filter: blur(14px);
            box-shadow: var(--admin-shadow);
            margin-bottom: 1.25rem;
        }

        .admin-topbar-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .admin-topbar-title {
            margin: 0;
            font-size: clamp(1rem, 2vw, 1.25rem);
            font-weight: 800;
            color: var(--admin-text);
        }

        .admin-topbar-subtitle {
            margin: .1rem 0 0;
            color: var(--admin-muted);
            font-size: .92rem;
        }

        .admin-user-chip {
            display: inline-flex;
            align-items: center;
            gap: .8rem;
            padding: .8rem 1rem;
            border-radius: 18px;
            background: var(--admin-accent-soft);
            color: var(--admin-text);
            max-width: 100%;
        }

        .admin-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            font-weight: 800;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .admin-user-text {
            min-width: 0;
        }

        .admin-user-role {
            display: block;
            color: var(--admin-muted);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .admin-user-name {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 700;
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            padding: .8rem 1rem;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-strong);
            color: var(--admin-text);
            font-weight: 700;
        }

        .theme-toggle i {
            font-size: 1rem;
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

        @media (max-width: 991.98px) {
            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-topbar-meta {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>


    @yield('css')
</head>

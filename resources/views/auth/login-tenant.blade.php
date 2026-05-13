<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->name ?? 'Tenant' }} — Admin Login</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #fffbf7;
        }

        /* Left panel - illustration/branding */
        .brand-panel {
            display: none;
            width: 50%;
            background: linear-gradient(160deg, #ff7a18 0%, #e85d04 40%, #dc2f02 100%);
            position: relative;
            overflow: hidden;
            padding: 3rem;
            flex-direction: column;
            justify-content: space-between;
        }

        @media (min-width: 1024px) {
            .brand-panel { display: flex; }
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-logo svg {
            width: 24px;
            height: 24px;
            color: #fff;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
        }

        .brand-desc {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            max-width: 380px;
        }

        .brand-features {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-feature-icon svg {
            width: 16px;
            height: 16px;
            color: #fff;
        }

        .brand-feature span {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* Right panel - form */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .tenant-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            border: 1px solid #fed7aa;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #c2410c;
            margin-bottom: 2rem;
        }

        .tenant-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f97316;
            animation: pulse-orange 2s infinite;
        }

        @keyframes pulse-orange {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1c1917;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            font-size: 0.9rem;
            color: #78716c;
            line-height: 1.5;
            margin-bottom: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #44403c;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1rem;
            background: #fff;
            border: 1.5px solid #e7e5e4;
            border-radius: 12px;
            color: #1c1917;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: #a8a29e;
        }

        .form-input:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            margin-top: 1.5rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(249, 115, 22, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(249, 115, 22, 0.4);
            background: linear-gradient(135deg, #fb923c, #f97316);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #dc2626;
        }

        .footer-links {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f5f5f4;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: #78716c;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #f97316;
        }

        /* Decorative circles on form panel */
        .form-panel::before {
            content: '';
            position: fixed;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.04) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .form-panel::after {
            content: '';
            position: fixed;
            bottom: -80px;
            right: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.03) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- Left branding panel -->
    <div class="brand-panel">
        <div class="brand-content">
            <div class="brand-logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
            </div>
            <h2 class="brand-title">{{ $tenant->name ?? 'Tenant' }}</h2>
            <p class="brand-desc">Panel admin untuk mengelola operasional outlet. Kelola menu, pesanan, laporan, dan tim dari satu tempat.</p>
        </div>

        <div class="brand-features">
            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </div>
                <span>Kelola menu & kategori</span>
            </div>
            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <span>Monitor pesanan real-time</span>
            </div>
            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <span>Laporan & analitik penjualan</span>
            </div>
        </div>
    </div>

    <!-- Right form panel -->
    <div class="form-panel">
        <div class="form-wrapper">
            <div class="tenant-badge">
                <span class="dot"></span>
                {{ $tenant->name ?? 'Tenant' }}
            </div>

            <h1 class="form-title">Masuk ke Admin</h1>
            <p class="form-subtitle">Gunakan akun admin, kasir, atau chef yang terdaftar di outlet ini.</p>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.auth.login.store', ['tenant' => $tenant->slug]) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" name="email" placeholder="staff@outlet.com"
                        value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="password" placeholder="••••••••"
                        required autocomplete="current-password">
                </div>
                <button type="submit" class="btn-login">Masuk ke Panel</button>
            </form>

            <div class="footer-links">
                <a href="{{ route('tenant.menu', ['tenant' => $tenant->slug]) }}">← Kembali ke menu</a>
                <a href="{{ route('landing') }}">Halaman utama</a>
            </div>
        </div>
    </div>
</body>

</html>

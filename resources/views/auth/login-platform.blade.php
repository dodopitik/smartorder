<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Archana Order — Platform Console</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0f14;
            color: #e4e4e7;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated gradient background */
        .bg-gradient {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
        }

        /* Grid pattern overlay */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }

        .login-card {
            background: rgba(24, 24, 32, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 20px 60px rgba(0, 0, 0, 0.5),
                0 0 120px rgba(99, 102, 241, 0.03);
        }

        .badge-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a5b4fc;
            margin-bottom: 1.5rem;
        }

        .badge-label .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #6366f1;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fafafa;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: #71717a;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #a1a1aa;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: #fafafa;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: #52525b;
        }

        .form-input:focus {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            margin-top: 1.5rem;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #fca5a5;
        }

        .footer-links {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: #71717a;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #a5b4fc;
        }

        .platform-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
        }

        .platform-icon svg {
            width: 20px;
            height: 20px;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="bg-gradient"></div>
    <div class="bg-grid"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="platform-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25A2.25 2.25 0 0 1 5.25 3h13.5A2.25 2.25 0 0 1 21 5.25Z" />
                </svg>
            </div>

            <div class="badge-label">
                <span class="dot"></span>
                Platform Console
            </div>

            <h1 class="login-title">Super Admin</h1>
            <p class="login-subtitle">Kelola semua tenant, billing, owner, dan pengaturan global platform dari satu dashboard.</p>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('platform.login.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" name="email" placeholder="admin@platform.com"
                        value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="password" placeholder="••••••••"
                        required autocomplete="current-password">
                </div>
                <button type="submit" class="btn-login">Masuk ke Platform</button>
            </form>

            <div class="footer-links">
                <a href="{{ route('landing') }}">← Kembali ke halaman utama</a>
            </div>
        </div>
    </div>
</body>

</html>

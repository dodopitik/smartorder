<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $loginTitle ?? 'Login' }} - Archana Order</title>
    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/auth.css') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/archana1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo/archana1.png') }}">

</head>

<body>
    <script src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">

                    </div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border mb-4">
                        <span class="fw-bold small text-uppercase">{{ $loginContextLabel ?? 'Portal Login' }}</span>
                    </div>
                    <h1 class="auth-title">{{ $loginTitle ?? 'Login' }}</h1>
                    <p class="auth-subtitle mb-5">{{ $loginSubtitle ?? 'Silakan masuk ke panel yang sesuai dengan peran Anda.' }}</p>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ $loginAction ?? route('platform.login.store') }}">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" class="form-control form-control-xl" placeholder="Email"
                                name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password"
                                name="password" required autocomplete="current-password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="mt-4 d-flex flex-column gap-2">
                        <a href="{{ route('landing') }}" class="text-muted text-decoration-none">Kembali ke landing</a>
                        <a href="{{ route('platform.login') }}" class="text-muted text-decoration-none">Masuk sebagai Super Admin</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>
    </div>
</body>

</html>

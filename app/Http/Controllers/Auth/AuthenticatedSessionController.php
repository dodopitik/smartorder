<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('platform.login');
    }

    public function createPlatform(Request $request): View|RedirectResponse
    {
        // Jika sudah login sebagai super admin, langsung ke dashboard
        if (Auth::check() && Auth::user()->role?->role_name === 'super_admin') {
            return redirect()->route('platform.dashboard');
        }

        // Jika login sebagai role lain, logout dulu
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('platform.login');
        }

        return view('auth.login-platform');
    }

    public function createTenant(Request $request, Tenant $tenant): View|RedirectResponse
    {
        if ($request->query->has('tenant')) {
            return redirect()->to($request->fullUrlWithoutQuery(['tenant']));
        }

        // Jika sudah login sebagai staff tenant ini, langsung ke panel
        if (Auth::check()) {
            $user = Auth::user();
            $allowedRoles = ['admin', 'cashier', 'chef'];

            if (in_array($user->role?->role_name, $allowedRoles) && (int) $user->tenant_id === (int) $tenant->id) {
                return redirect()->route('tenant-admin.panel', ['tenant' => $tenant->slug]);
            }

            // Jika super admin, langsung ke panel tenant
            if ($user->role?->role_name === 'super_admin') {
                return redirect()->route('tenant-admin.panel', ['tenant' => $tenant->slug]);
            }

            // Login sebagai user lain, logout dulu
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('tenant.auth.login', ['tenant' => $tenant->slug]);
        }

        return view('auth.login-tenant', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->to($this->homeRouteFor($request->user()));
    }

    public function storePlatform(LoginRequest $request): RedirectResponse
    {
        // Logout session lama jika ada, agar tidak bentrok
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $request->authenticate();
        $request->session()->regenerate();

        if ($request->user()?->role?->role_name !== 'super_admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak punya akses ke Super Admin Platform.',
            ]);
        }

        return redirect()->to($this->homeRouteFor($request->user()));
    }

    public function storeTenant(LoginRequest $request, Tenant $tenant): RedirectResponse
    {
        // Logout session lama jika ada, agar tidak bentrok
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        $role = $user?->role?->role_name;
        $allowedRoles = ['admin', 'cashier', 'chef'];

        if (! in_array($role, $allowedRoles, true) || (int) $user?->tenant_id !== (int) $tenant->id) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak punya akses ke panel admin tenant ini.',
            ]);
        }

        return redirect()->to($this->homeRouteFor($request->user()));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $redirectTo = $request->string('redirect_to')->toString();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Hanya izinkan redirect ke path lokal (bukan protocol-relative URL)
        if ($redirectTo !== '' && str_starts_with($redirectTo, '/') && ! str_starts_with($redirectTo, '//')) {
            return redirect()->to($redirectTo);
        }

        return redirect('/');
    }
}

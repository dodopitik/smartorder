<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (Auth::guest()) {
            if ($tenant = $request->route('tenant')) {
                $tenantSlug = is_object($tenant) ? $tenant->slug : $tenant;
                return redirect()->route('tenant.auth.login', ['tenant' => $tenantSlug]);
            }

            if ($request->routeIs('platform.*')) {
                return redirect()->route('platform.login');
            }

            return redirect()->route('platform.login');
        }

        // Super admin selalu punya akses ke semua area
        if (Auth::user()->role?->role_name === 'super_admin') {
            return $next($request);
        }

        $roles = explode('|', $role);
        if (!in_array(Auth::user()->role->role_name, $roles)) {
            abort(403, 'Akses ditolak.');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->query->has('tenant')) {
            return redirect()->to($request->fullUrlWithoutQuery(['tenant']));
        }

        $tenant = $request->route('tenant');

        if (is_string($tenant)) {
            $tenant = Tenant::query()
                ->where('slug', $tenant)
                ->where('is_active', true)
                ->first();
        }

        if (! $tenant && Auth::check() && Auth::user()->role?->role_name !== 'super_admin') {
            $tenant = Auth::user()->tenant;
        }

        if ($tenant instanceof Tenant) {
            app()->instance('currentTenant', $tenant);
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}

<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function (Request $request): string {
            $user = $request->user();

            if (! $user) {
                return route('landing', absolute: false);
            }

            // Jangan redirect jika user sedang mengakses login page lain (switch akun)
            if ($request->routeIs('platform.login', 'platform.login.store', 'tenant.auth.login', 'tenant.auth.login.store')) {
                return $request->url();
            }

            if ($user->role?->role_name === 'super_admin') {
                return route('platform.dashboard', absolute: false);
            }

            $tenant = $request->route('tenant');

            if (is_string($tenant)) {
                $tenant = Tenant::query()->where('slug', $tenant)->first();
            }

            $tenantSlug = $tenant?->slug ?? $user->tenant?->slug;

            return $tenantSlug
                ? route('tenant-admin.panel', ['tenant' => $tenantSlug], absolute: false)
                : route('landing', absolute: false);
        });

        View::composer('*', function ($view): void {
            $view->with('currentTenant', app()->bound('currentTenant') ? app('currentTenant') : null);
        });
    }
}

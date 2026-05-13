<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;

abstract class Controller
{
    protected function currentTenant(): ?Tenant
    {
        return app()->bound('currentTenant') ? app('currentTenant') : null;
    }

    protected function requireTenant(): Tenant
    {
        return $this->currentTenant() ?? abort(404, 'Tenant tidak ditemukan.');
    }

    protected function homeRouteFor(?User $user = null): string
    {
        $user ??= auth()->user();

        if (! $user) {
            return route('landing', absolute: false);
        }

        if ($user->role?->role_name === 'super_admin') {
            return route('platform.dashboard', absolute: false);
        }

        $tenantSlug = $user->tenant?->slug ?? $this->currentTenant()?->slug;

        return route('tenant-admin.panel', ['tenant' => $tenantSlug], absolute: false);
    }
}

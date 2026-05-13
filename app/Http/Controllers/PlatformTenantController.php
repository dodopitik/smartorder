<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlatformTenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::query()
            ->withCount(['orders', 'items', 'users'])
            ->withSum('orders', 'grandtotal')
            ->with([
                'users' => fn ($query) => $query
                    ->whereHas('role', fn ($roleQuery) => $roleQuery->where('role_name', 'admin'))
                    ->orderBy('fullname'),
            ])
            ->orderBy('name')
            ->get();

        return view('platform.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load([
            'users.role',
            'orders' => fn ($query) => $query->latest()->limit(5)->with('user'),
        ])->loadCount(['orders', 'items', 'categories', 'users'])
            ->loadSum('orders', 'grandtotal');

        $monthlyRevenue = (int) $tenant->orders()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('grandtotal');

        $monthlyOrders = (int) $tenant->orders()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $owner = $tenant->users
            ->first(fn ($user) => $user->role?->role_name === 'admin');

        return view('platform.tenants.show', compact('tenant', 'monthlyRevenue', 'monthlyOrders', 'owner'));
    }

    public function create(): View
    {
        return view('platform.tenants.form', [
            'tenant' => new Tenant(['is_active' => true]),
            'owner' => null,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTenant($request);
        $adminRoleId = Role::query()->where('role_name', 'admin')->value('id');

        DB::transaction(function () use ($validated, $adminRoleId, $request): void {
            $tenantPayload = $this->tenantPayload($validated);

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = 'tenant-' . time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('img_tenant_logo'), $logoName);
                $tenantPayload['logo'] = $logoName;
            }

            $tenant = Tenant::create($tenantPayload);
            $this->upsertOwner($tenant, $validated, $adminRoleId);
        });

        return redirect()->route('platform.tenants.index')->with('success', 'Tenant baru berhasil dibuat.');
    }

    public function edit(Tenant $tenant): View
    {
        $owner = $tenant->users()
            ->whereHas('role', fn ($query) => $query->where('role_name', 'admin'))
            ->orderBy('fullname')
            ->first();

        return view('platform.tenants.form', [
            'tenant' => $tenant,
            'owner' => $owner,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $this->validateTenant($request, $tenant);
        $adminRoleId = Role::query()->where('role_name', 'admin')->value('id');

        DB::transaction(function () use ($tenant, $validated, $adminRoleId, $request): void {
            $tenantPayload = $this->tenantPayload($validated);

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = 'tenant-' . time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('img_tenant_logo'), $logoName);

                // Hapus logo lama
                if ($tenant->logo && file_exists(public_path('img_tenant_logo/' . $tenant->logo))) {
                    @unlink(public_path('img_tenant_logo/' . $tenant->logo));
                }

                $tenantPayload['logo'] = $logoName;
            }

            $tenant->update($tenantPayload);
            $this->upsertOwner($tenant, $validated, $adminRoleId);
        });

        return redirect()->route('platform.tenants.index')->with('success', 'Tenant berhasil diperbarui.');
    }

    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $tenant->update([
            'is_active' => ! $tenant->is_active,
        ]);

        return redirect()->route('platform.tenants.index')->with(
            'success',
            $tenant->is_active ? 'Tenant berhasil diaktifkan.' : 'Tenant berhasil dinonaktifkan.'
        );
    }

    public function destroy(Request $request, Tenant $tenant): RedirectResponse
    {
        $forceDelete = $request->boolean('force');

        if ($forceDelete) {
            DB::transaction(function () use ($tenant): void {
                // Hapus order items terlebih dahulu
                $orderIds = $tenant->orders()->pluck('id');
                \App\Models\OrderItem::whereIn('order_id', $orderIds)->forceDelete();

                // Hapus orders
                $tenant->orders()->forceDelete();

                // Hapus items
                $tenant->items()->forceDelete();

                // Hapus categories
                $tenant->categories()->forceDelete();

                // Hapus users tenant (bukan super admin)
                $tenant->users()->forceDelete();

                // Hapus tenant
                $tenant->delete();
            });

            return redirect()->route('platform.tenants.index')->with('success', 'Tenant beserta seluruh datanya berhasil dihapus permanen.');
        }

        $hasRelatedData = $tenant->orders()->exists()
            || $tenant->items()->exists()
            || $tenant->categories()->exists()
            || $tenant->users()->exists();

        if ($hasRelatedData) {
            return redirect()->route('platform.tenants.index')
                ->with('error', 'Tenant masih memiliki data terkait. Gunakan "Hapus Paksa" jika ingin menghapus beserta seluruh datanya, atau nonaktifkan tenant.');
        }

        $tenant->delete();

        return redirect()->route('platform.tenants.index')->with('success', 'Tenant berhasil dihapus permanen.');
    }

    private function validateTenant(Request $request, ?Tenant $tenant = null): array
    {
        $tenantId = $tenant?->id;
        $ownerUserId = $tenant?->users()
            ->whereHas('role', fn ($query) => $query->where('role_name', 'admin'))
            ->value('id');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('tenants', 'slug')->ignore($tenantId)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'owner_fullname' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($ownerUserId)],
            'owner_phone' => ['required', 'string', 'max:30'],
            'owner_username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($ownerUserId)],
            'owner_password' => [$tenant ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
    }

    private function tenantPayload(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'address' => $validated['address'] ?? null,
            'primary_color' => $validated['primary_color'] ?? '#ff7a18',
            'secondary_color' => $validated['secondary_color'] ?? '#FFB366',
            'hero_title' => $validated['hero_title'] ?? null,
            'hero_subtitle' => $validated['hero_subtitle'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }

    private function upsertOwner(Tenant $tenant, array $validated, int $adminRoleId): void
    {
        $owner = User::query()
            ->where('tenant_id', $tenant->id)
            ->whereHas('role', fn ($query) => $query->where('role_name', 'admin'))
            ->first();

        $payload = [
            'tenant_id' => $tenant->id,
            'fullname' => $validated['owner_fullname'],
            'email' => $validated['owner_email'],
            'phone' => $validated['owner_phone'],
            'username' => $validated['owner_username'] ?: $validated['owner_email'],
            'role_id' => $adminRoleId,
        ];

        if (! empty($validated['owner_password'])) {
            $payload['password'] = Hash::make($validated['owner_password']);
        }

        if ($owner) {
            $owner->update($payload);
            return;
        }

        User::create($payload);
    }
}

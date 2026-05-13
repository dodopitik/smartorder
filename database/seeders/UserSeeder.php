<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->first();
        $superAdminRole = Role::query()->where('role_name', 'super_admin')->value('id');
        $adminRole = Role::query()->where('role_name', 'admin')->value('id');
        $cashierRole = Role::query()->where('role_name', 'cashier')->value('id');
        $chefRole = Role::query()->where('role_name', 'chef')->value('id');

        if (! $tenant || ! $superAdminRole || ! $adminRole || ! $cashierRole || ! $chefRole) {
            return;
        }

        User::updateOrCreate(
            ['email' => 'superadmin@happyfried.test'],
            [
                'tenant_id' => null,
                'username' => 'superadmin-happyfried',
                'fullname' => 'Super Admin Happy Fried',
                'phone' => '081111111111',
                'role_id' => $superAdminRole,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@happyfried.test'],
            [
                'tenant_id' => $tenant->id,
                'username' => 'admin-happyfried',
                'fullname' => 'Admin Happy Fried',
                'phone' => '081234567890',
                'role_id' => $adminRole,
                'password' => Hash::make('password'),
            ]
        );

        User::factory(2)->create([
            'tenant_id' => $tenant->id,
            'role_id' => $cashierRole,
        ]);

        User::factory(2)->create([
            'tenant_id' => $tenant->id,
            'role_id' => $chefRole,
        ]);
    }
}

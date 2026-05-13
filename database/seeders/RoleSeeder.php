<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'super_admin', 'description' => 'Mengelola seluruh platform, tenant, billing, dan global setting.'],
            ['role_name' => 'admin', 'description' => 'Mengelola operasional tenant, user, menu, dan pesanan.'],
            ['role_name' => 'cashier', 'description' => 'Mengelola kategori, menu, dan monitoring pesanan tenant.'],
            ['role_name' => 'chef', 'description' => 'Memproses dan memperbarui status pesanan di dapur.'],
            ['role_name' => 'customer', 'description' => 'Pengguna customer yang membuat pesanan.'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['role_name' => $role['role_name']], $role);
        }
    }
}

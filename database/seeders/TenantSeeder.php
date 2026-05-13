<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::query()
            ->where('slug', '!=', 'happyfried')
            ->update(['is_active' => false]);

        Tenant::updateOrCreate(
            ['slug' => 'happyfried'],
            [
                'name' => 'Happy Fried',
                'slug' => 'happyfried',
                'tagline' => 'Crispy kitchen for every table.',
                'description' => 'Outlet utama dengan layanan dine-in dan takeaway.',
                'contact_phone' => '62895363076706',
                'contact_email' => 'hello@happyfried.test',
                'address' => 'Main Outlet',
                'primary_color' => '#ff7a18',
                'secondary_color' => '#111827',
                'hero_title' => 'Santapan cepat, alur kitchen rapi, customer happy.',
                'hero_subtitle' => 'Scan meja, pilih menu, dan pantau status pesanan dari outlet ini.',
                'is_active' => true,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $baseCategories = [
            ['category_name' => 'Makanan', 'description' => 'Menu utama untuk kebutuhan makan berat.'],
            ['category_name' => 'Minuman', 'description' => 'Pilihan minuman dingin dan hangat.'],
            ['category_name' => 'Snack', 'description' => 'Camilan ringan untuk menemani pesanan utama.'],
        ];

        foreach (Tenant::all() as $tenant) {
            foreach ($baseCategories as $category) {
                DB::table('categories')->updateOrInsert(
                    ['tenant_id' => $tenant->id, 'category_name' => $category['category_name']],
                    $category + ['tenant_id' => $tenant->id]
                );
            }
        }
    }
}

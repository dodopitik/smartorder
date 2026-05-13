<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            'Makanan' => [
                ['name' => 'Original Fried Chicken', 'description' => 'Ayam goreng crispy dengan bumbu gurih khas outlet.', 'price' => 22000],
                ['name' => 'Spicy Chicken Rice Bowl', 'description' => 'Rice bowl ayam pedas untuk makan cepat saat jam sibuk.', 'price' => 28000],
            ],
            'Minuman' => [
                ['name' => 'Lemon Tea', 'description' => 'Teh lemon segar untuk pendamping menu utama.', 'price' => 12000],
                ['name' => 'Iced Chocolate', 'description' => 'Cokelat dingin dengan rasa manis lembut.', 'price' => 18000],
            ],
            'Snack' => [
                ['name' => 'French Fries', 'description' => 'Kentang goreng renyah dengan taburan bumbu.', 'price' => 15000],
                ['name' => 'Cheese Nugget', 'description' => 'Nugget keju gurih untuk camilan ringan.', 'price' => 17000],
            ],
        ];

        foreach (Tenant::all() as $tenant) {
            foreach ($menus as $categoryName => $items) {
                $categoryId = Category::query()
                    ->where('tenant_id', $tenant->id)
                    ->where('category_name', $categoryName)
                    ->value('id');

                if (! $categoryId) {
                    continue;
                }

                foreach ($items as $item) {
                    Item::updateOrCreate(
                        ['tenant_id' => $tenant->id, 'name' => $item['name']],
                        $item + [
                            'tenant_id' => $tenant->id,
                            'category_id' => $categoryId,
                            'image' => 'happyliving.jpeg',
                            'is_available' => true,
                        ]
                    );
                }
            }
        }
    }
}

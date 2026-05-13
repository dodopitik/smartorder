<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $tenant = Tenant::query()->firstOrCreate(
            ['slug' => 'test-tenant'],
            [
                'name' => 'Test Tenant',
                'tagline' => 'Tenant for automated tests',
                'primary_color' => '#ff7a18',
                'secondary_color' => '#111827',
            ]
        );

        $category = Category::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'category_name' => 'Makanan'],
            ['description' => 'Default category for tests']
        );

        return [
            'tenant_id' => $tenant->id,
            'name' => $this->faker->name(),
            'category_id' => $category->id,
            'price' => $this->faker->numberBetween(10000, 100000),
            'image' => fake()->randomElement([
                'https://plus.unsplash.com/premium_photo-1694708455249-992010f9db32',
                'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d',
                'https://images.unsplash.com/photo-1679279726946-a158b8bcaa23',
                'https://images.unsplash.com/photo-1638502182261-7be714a565ce',
                'https://plus.unsplash.com/premium_photo-1666919818889-0b7251bea0a6',
                'https://images.unsplash.com/photo-1652752731860-ef0cf518f13a',
            ]),
            'is_available' => $this->faker->boolean(),
        ];
    }
}

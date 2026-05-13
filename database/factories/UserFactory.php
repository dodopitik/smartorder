<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

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

        $role = Role::query()->firstOrCreate(['role_name' => 'admin']);

        return [
            'tenant_id' => $tenant->id,
            'username' => fake()->unique()->userName(),
            'password' => Hash::make(static::$password ??= 'password'),
            'fullname' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'role_id' => $role->id,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => []);
    }
}

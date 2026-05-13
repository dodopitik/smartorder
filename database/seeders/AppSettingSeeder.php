<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'platform_name' => 'Archana Order',
            'support_email' => 'support@happyfried.test',
            'support_phone' => '081234567890',
            'monthly_fee_per_order' => '1000',
            'billing_cycle_note' => 'Fee platform dihitung per order settlement tiap tenant.',
            'hero_message' => 'Kelola banyak tenant, owner, billing, dan health bisnis dari satu panel.',
        ];

        foreach ($settings as $key => $value) {
            AppSetting::setValue($key, $value);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoinSetting;

class CoinSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['currency_code' => 'INR', 'coin_value' => 1.00, 'status' => true],
            ['currency_code' => 'USD', 'coin_value' => 0.012, 'status' => true],
            ['currency_code' => 'EUR', 'coin_value' => 0.011, 'status' => true],
            ['currency_code' => 'GBP', 'coin_value' => 0.0095, 'status' => true],
        ];

        foreach ($settings as $setting) {
            CoinSetting::updateOrCreate(
                ['currency_code' => $setting['currency_code']],
                $setting
            );
        }
    }
}

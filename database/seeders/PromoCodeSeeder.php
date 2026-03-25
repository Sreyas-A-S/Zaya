<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoCodes = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'reward' => 10.00,
                'usage_limit' => 100,
                'expiry_date' => Carbon::now()->addMonths(6),
                'status' => true,
            ],
            [
                'code' => 'ZAYA50',
                'type' => 'fixed',
                'reward' => 50.00,
                'usage_limit' => 50,
                'expiry_date' => Carbon::now()->addMonths(3),
                'status' => true,
            ],
            [
                'code' => 'HOLISTIC20',
                'type' => 'percentage',
                'reward' => 20.00,
                'usage_limit' => 200,
                'expiry_date' => Carbon::now()->addYear(),
                'status' => true,
            ],
            [
                'code' => 'SAVE100',
                'type' => 'fixed',
                'reward' => 100.00,
                'usage_limit' => 20,
                'expiry_date' => Carbon::now()->addMonth(),
                'status' => true,
            ],
            [
                'code' => 'SUMMER25',
                'type' => 'percentage',
                'reward' => 25.00,
                'usage_limit' => null,
                'expiry_date' => Carbon::parse('2026-08-31'),
                'status' => true,
            ],
            [
                'code' => 'FIRSTOFF',
                'type' => 'percentage',
                'reward' => 15.00,
                'usage_limit' => 500,
                'expiry_date' => null,
                'status' => true,
            ],
            [
                'code' => 'HEALTHY5',
                'type' => 'fixed',
                'reward' => 5.00,
                'usage_limit' => 1000,
                'expiry_date' => Carbon::now()->addYears(2),
                'status' => true,
            ],
            [
                'code' => 'FLASH50',
                'type' => 'percentage',
                'reward' => 50.00,
                'usage_limit' => 10,
                'expiry_date' => Carbon::now()->addDays(2),
                'status' => true,
            ],
            [
                'code' => 'AYURVEDA10',
                'type' => 'percentage',
                'reward' => 10.00,
                'usage_limit' => null,
                'expiry_date' => null,
                'status' => true,
            ],
            [
                'code' => 'EXPIRED_CODE',
                'type' => 'fixed',
                'reward' => 20.00,
                'usage_limit' => 100,
                'expiry_date' => Carbon::now()->subMonth(),
                'status' => false,
            ],
        ];

        foreach ($promoCodes as $code) {
            PromoCode::updateOrCreate(['code' => $code['code']], $code);
        }
    }
}

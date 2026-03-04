<?php

namespace Database\Seeders;

use App\Models\HomepageSetting;
use Illuminate\Database\Seeder;

class FinanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomepageSetting::updateOrCreate(
            ['key' => 'client_registration_fee'],
            [
                'value' => '0',
                'type' => 'number',
                'section' => 'finance',
            ]
        );
    }
}

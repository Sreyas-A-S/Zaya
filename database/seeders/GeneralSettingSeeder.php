<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'admin_email',
                'value' => 'admin@zaya.com',
                'type' => 'text',
                'section' => 'general',
                'max_length' => 100
            ],
            [
                'key' => 'support_email',
                'value' => 'support@zaya.com',
                'type' => 'text',
                'section' => 'general',
                'max_length' => 100
            ],
            [
                'key' => 'contact_phone',
                'value' => '+91 1234567890',
                'type' => 'text',
                'section' => 'general',
                'max_length' => 20
            ],
            [
                'key' => 'contact_address',
                'value' => 'Sample Address, India',
                'type' => 'textarea',
                'section' => 'general',
                'max_length' => 255
            ],
            [
                'key' => 'reminder_mail_advance',
                'value' => '1440', // 24 hours in minutes
                'type' => 'number',
                'section' => 'general',
                'max_length' => null
            ],
            [
                'key' => 'reminder_mail_one_hour',
                'value' => '60', // 1 hour in minutes
                'type' => 'number',
                'section' => 'general',
                'max_length' => null
            ],
            [
                'key' => 'reminder_mail_final',
                'value' => '10', // 10 minutes
                'type' => 'number',
                'section' => 'general',
                'max_length' => null
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

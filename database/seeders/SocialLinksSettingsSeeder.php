<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class SocialLinksSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'section' => 'social_links',
                'key' => 'facebook_url',
                'value' => 'https://facebook.com',
                'type' => 'text',
            ],
            [
                'section' => 'social_links',
                'key' => 'instagram_url',
                'value' => 'https://instagram.com',
                'type' => 'text',
            ],
            [
                'section' => 'social_links',
                'key' => 'whatsapp_number',
                'value' => '+911234567890',
                'type' => 'text',
            ],
            [
                'section' => 'social_links',
                'key' => 'website_url',
                'value' => 'https://zaya.com',
                'type' => 'text',
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

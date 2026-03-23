<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'gallery_hero_title',
                'value' => 'A Visual Journey Into Stillness',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'footer_link_gallery',
                'value' => 'Gallery',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
            [
                'key' => 'about_us_nav_title',
                'value' => 'About Us',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
            [
                'key' => 'nav_login',
                'value' => 'Login',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
            [
                'key' => 'nav_find_practitioner',
                'value' => 'Find Practitioner',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
            [
                'key' => 'gallery_hero_subtitle',
                'value' => 'Step inside the world of Zaya. Explore the spaces, rituals and moments of connection that define our path to holistic harmony.',
                'type' => 'textarea',
                'section' => 'gallery_page',
                'max_length' => 300
            ],
            [
                'key' => 'gallery_sanctuary_title',
                'value' => 'The Sanctuary',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'gallery_movement_title',
                'value' => 'Sacred Movement',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'gallery_rituals_title',
                'value' => 'Ayurvedic Rituals',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'gallery_retreats_title',
                'value' => 'Community Retreats',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'gallery_cta_title',
                'value' => 'Begin Your Journey to Stillness',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100
            ],
            [
                'key' => 'gallery_cta_subtitle',
                'value' => 'Experience the profound healing of Zaya Wellness Sanctuary.',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 200
            ],
            [
                'key' => 'gallery_cta_button_1',
                'value' => 'Book a Practitioner',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
            [
                'key' => 'gallery_cta_button_2',
                'value' => 'Explore Our Services',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key'], 'language' => 'en'],
                $setting
            );
        }
    }
}

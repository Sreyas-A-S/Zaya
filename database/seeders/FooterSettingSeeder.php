<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class FooterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Newsletter Section
            [
                'key' => 'newsletter_title',
                'value' => 'Join our newsletter for weekly wellness tips.',
                'type' => 'text',
                'section' => 'newsletter',
                'max_length' => 100,
            ],
            [
                'key' => 'newsletter_placeholder',
                'value' => 'Your email...',
                'type' => 'text',
                'section' => 'newsletter',
                'max_length' => 100,
            ],
            // General Section (Description)
            [
                'key' => 'footer_description',
                'value' => 'Empowering your wellness journey through ancient wisdom and modern science.',
                'type' => 'textarea',
                'section' => 'general',
                'max_length' => 250,
            ],
            [
                'key' => 'copyright_text',
                'value' => 'All rights reserved. © 2026 Zaya Wellness',
                'type' => 'text',
                'section' => 'general',
                'max_length' => 100,
            ],
            // Headings
            [
                'key' => 'quick_links_heading',
                'value' => 'Quick Links',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 50,
            ],

            [
                'key' => 'pincode_heading',
                'value' => 'Save your pincode & find nearby care.',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 100,
            ],
            [
                'key' => 'pincode_placeholder',
                'value' => 'Enter Pincode',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 50,
            ],

            // Quick Links
            [
                'key' => 'footer_link_home',
                'value' => 'Home',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_who_we_are',
                'value' => 'Who we are',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_what_we_do',
                'value' => 'What we do',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_our_team',
                'value' => 'Our Team',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_gallery',
                'value' => 'Gallery',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_blog',
                'value' => 'Blog',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_link_contact_us',
                'value' => 'Contact Us',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
            ],



            // Legal Links
            [
                'key' => 'footer_privacy_policy',
                'value' => 'Privacy Policy',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_cookie_policy',
                'value' => 'Cookie Policy',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
            ],
            [
                'key' => 'footer_terms_conditions',
                'value' => 'Terms & Conditions',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
            ],

            // Social Links
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
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

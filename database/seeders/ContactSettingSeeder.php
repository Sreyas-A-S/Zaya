<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Banner
            [
                'key' => 'contact_banner_title',
                'value' => 'Get in Touch',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],
            [
                'key' => 'contact_banner_subtitle',
                'value' => 'We are here to help you on your wellness journey.',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 100
            ],
            [
                'key' => 'contact_banner_image',
                'value' => 'frontend/assets/contact-banner.png',
                'type' => 'image',
                'section' => 'contact'
            ],

            // Contact Information
            [
                'key' => 'contact_info_address',
                'value' => '123 Wellness Street, Holistic City, IN 45678',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200
            ],
            [
                'key' => 'contact_info_email',
                'value' => 'support@zaya.com',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],
            [
                'key' => 'contact_info_phone',
                'value' => '+1 (234) 567-8900',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 20
            ],
            [
                'key' => 'contact_info_working_hours',
                'value' => 'Mon - Fri: 9:00 AM - 6:00 PM',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],

            // Message Form
            [
                'key' => 'contact_form_title',
                'value' => 'Send Us a Message',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],
            [
                'key' => 'contact_form_subtitle',
                'value' => 'Fill out the form below and we will get back to you shortly.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200
            ],

            // Support Desk
            [
                'key' => 'contact_support_title',
                'value' => 'Professional Support',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],
            [
                'key' => 'contact_support_description',
                'value' => 'Need technical assistance or have questions about our services?',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200
            ],
            [
                'key' => 'contact_support_button_text',
                'value' => 'Visit Help Center',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 30
            ],

            // FAQs Section
            [
                'key' => 'contact_faq_title',
                'value' => 'Frequently Asked Questions',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50
            ],
            [
                'key' => 'contact_faq_subtitle',
                'value' => 'Find quick answers to common questions about Zaya.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200
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

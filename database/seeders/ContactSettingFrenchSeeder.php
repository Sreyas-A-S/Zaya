<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class ContactSettingFrenchSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Banner
            [
                'key' => 'contact_banner_title',
                'value' => 'Contactez-nous',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_banner_subtitle',
                'value' => 'Nous sommes là pour vous aider dans votre parcours bien-être.',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_banner_image',
                'value' => 'frontend/assets/contact-banner.png',
                'type' => 'image',
                'section' => 'contact',
                'language' => 'fr',
            ],

            // Contact Information
            [
                'key' => 'contact_info_address',
                'value' => '123 rue Wellness, Holistic City, IN 45678',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_info_email',
                'value' => 'support@zaya.com',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_info_phone',
                'value' => '+1 (234) 567-8900',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 20,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_info_working_hours',
                'value' => 'Lun - Ven : 9 h 00 - 18 h 00',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],

            // Message Form
            [
                'key' => 'contact_form_title',
                'value' => 'Envoyez-nous un message',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_form_subtitle',
                'value' => 'Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
                'language' => 'fr',
            ],

            // Support Desk
            [
                'key' => 'contact_support_title',
                'value' => 'Assistance professionnelle',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_support_description',
                'value' => 'Besoin d’aide technique ou de renseignements sur nos services ?',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_support_button_text',
                'value' => 'Visiter le centre d’aide',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 30,
                'language' => 'fr',
            ],

            // FAQs Section
            [
                'key' => 'contact_faq_title',
                'value' => 'Questions fréquentes',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'contact_faq_subtitle',
                'value' => 'Trouvez des réponses rapides aux questions courantes sur Zaya.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
                'language' => 'fr',
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key'], 'language' => 'fr'],
                $setting
            );
        }
    }
}

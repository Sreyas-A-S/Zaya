<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class ServicesPageSeeder extends Seeder
{
    public function run()
    {
        $languages = ['en', 'fr'];
        
        foreach ($languages as $lang) {
            $settings = [
                // General Settings
                [
                    'key' => 'services_page_badge',
                    'value' => $lang === 'en' ? 'Our Services' : 'Nos Services',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 30,
                    'language' => $lang
                ],
                [
                    'key' => 'services_page_title',
                    'value' => $lang === 'en' ? "Embrace Holistic \n Wellness" : "Embrasser le bien-être \n holistique",
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 100,
                    'language' => $lang
                ],
                [
                    'key' => 'services_page_subtitle',
                    'value' => $lang === 'en' ? 'Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.' : 'Des conseils détaillés pour votre voyage vers la vitalité physique, la clarté mentale et l\'harmonie spirituelle.',
                    'type' => 'textarea',
                    'section' => 'services_page',
                    'max_length' => 255,
                    'language' => $lang
                ],
                [
                    'key' => 'services_page_description',
                    'value' => $lang === 'en' ? 'ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in traditional Indian wisdom. Every service offered on our platform is provided by a practitioner whose background in Ayurveda, Yoga, or holistic health has been rigorously reviewed by our Approval Commission.' : 'ZAYA Wellness sert de pont mondial pour ceux qui recherchent des soins authentiques et dirigés par des experts ancrés dans la sagesse indienne traditionnelle.',
                    'type' => 'textarea',
                    'section' => 'services_page',
                    'max_length' => 1000,
                    'language' => $lang
                ],
                [
                    'key' => 'services_page_image',
                    'value' => 'frontend/assets/services-page-bg.png',
                    'type' => 'image',
                    'section' => 'services_page',
                    'max_length' => null,
                    'language' => $lang
                ],

                // Statistics Settings
                [
                    'key' => 'services_stat_1_count',
                    'value' => '300',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 10,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_1_label',
                    'value' => $lang === 'en' ? 'Sessions Completed' : 'Sessions terminées',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_2_count',
                    'value' => '50+',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 10,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_2_label',
                    'value' => $lang === 'en' ? 'Certified Practitioners' : 'Praticiens certifiés',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_3_count',
                    'value' => '99%',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 10,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_3_label',
                    'value' => $lang === 'en' ? 'Positive Feedbacks' : 'Commentaires positifs',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_4_count',
                    'value' => '10',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 10,
                    'language' => $lang
                ],
                [
                    'key' => 'services_stat_4_label',
                    'value' => $lang === 'en' ? 'Years of Tradition' : 'Années de tradition',
                    'type' => 'text',
                    'section' => 'services_page',
                    'max_length' => 50,
                    'language' => $lang
                ],

                // Service Detail Page Global Settings
                [
                    'key' => 'service_detail_book_button_text',
                    'value' => $lang === 'en' ? 'Book a Session' : 'Réserver une séance',
                    'type' => 'text',
                    'section' => 'service_detail_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'service_detail_share_button_text',
                    'value' => $lang === 'en' ? 'Share' : 'Partager',
                    'type' => 'text',
                    'section' => 'service_detail_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'service_detail_sidebar_title',
                    'value' => $lang === 'en' ? 'Other Services' : 'Autres services',
                    'type' => 'text',
                    'section' => 'service_detail_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
                [
                    'key' => 'service_detail_cta_title',
                    'value' => $lang === 'en' ? 'Ready to restore your natural rhythm?' : 'Prêt à restaurer votre rythme naturel ?',
                    'type' => 'text',
                    'section' => 'service_detail_page',
                    'max_length' => 100,
                    'language' => $lang
                ],
                [
                    'key' => 'service_detail_cta_description',
                    'value' => $lang === 'en' ? 'Join a global community committed to authentic, expert-led wellness.' : 'Rejoignez une communauté mondiale engagée dans un bien-être authentique et dirigé par des experts.',
                    'type' => 'textarea',
                    'section' => 'service_detail_page',
                    'max_length' => 255,
                    'language' => $lang
                ],
                [
                    'key' => 'service_detail_cta_button_text',
                    'value' => $lang === 'en' ? 'Book Your Sessions Now' : 'Réservez vos séances maintenant',
                    'type' => 'text',
                    'section' => 'service_detail_page',
                    'max_length' => 50,
                    'language' => $lang
                ],
            ];

            foreach ($settings as $item) {
                HomepageSetting::updateOrCreate(
                    ['key' => $item['key'], 'language' => $item['language']],
                    $item
                );
            }
        }

    }
}

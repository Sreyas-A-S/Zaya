<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class FooterSettingFrenchSeeder extends Seeder
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
                'value' => 'Inscrivez-vous à notre newsletter pour des conseils bien-être hebdomadaires.',
                'type' => 'text',
                'section' => 'newsletter',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'newsletter_placeholder',
                'value' => 'Votre email...',
                'type' => 'text',
                'section' => 'newsletter',
                'max_length' => 100,
                'language' => 'fr',
            ],
            // General Section (Description)
            [
                'key' => 'footer_description',
                'value' => 'Favoriser votre parcours de bien-être grâce à la sagesse ancienne et à la science moderne.',
                'type' => 'textarea',
                'section' => 'general',
                'max_length' => 250,
                'language' => 'fr',
            ],
            [
                'key' => 'copyright_text',
                'value' => 'Tous droits réservés. © 2026 Zaya Wellness',
                'type' => 'text',
                'section' => 'general',
                'max_length' => 100,
                'language' => 'fr',
            ],
            // Headings
            [
                'key' => 'quick_links_heading',
                'value' => 'Liens rapides',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 50,
                'language' => 'fr',
            ],

            [
                'key' => 'pincode_heading',
                'value' => 'Enregistrez votre code postal et trouvez des soins à proximité.',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'pincode_placeholder',
                'value' => 'Entrez le code postal',
                'type' => 'text',
                'section' => 'headings',
                'max_length' => 50,
                'language' => 'fr',
            ],

            // Quick Links
            [
                'key' => 'footer_link_home',
                'value' => 'Accueil',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_who_we_are',
                'value' => 'Qui sommes-nous',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_what_we_do',
                'value' => 'Ce que nous faisons',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_our_team',
                'value' => 'Notre équipe',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_gallery',
                'value' => 'Galerie',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_blog',
                'value' => 'Blog',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_contact_us',
                'value' => 'Contactez-nous',
                'type' => 'text',
                'section' => 'quick_links',
                'max_length' => 50,
                'language' => 'fr',
            ],



            // Legal Links
            [
                'key' => 'footer_privacy_policy',
                'value' => 'Politique de confidentialité',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_cookie_policy',
                'value' => 'Politique relative aux cookies',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_terms_conditions',
                'value' => 'Conditions générales',
                'type' => 'text',
                'section' => 'legal',
                'max_length' => 50,
                'language' => 'fr',
            ],

            // Social Links
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
                'language' => 'fr',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
                'language' => 'fr',
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
                'language' => 'fr',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com',
                'type' => 'text',
                'section' => 'social_links',
                'max_length' => 255,
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

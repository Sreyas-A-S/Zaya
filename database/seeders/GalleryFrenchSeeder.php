<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class GalleryFrenchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'gallery_hero_title',
                'value' => 'Un voyage visuel vers la sérénité',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'footer_link_gallery',
                'value' => 'Galerie',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'about_us_nav_title',
                'value' => 'À propos',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'nav_login',
                'value' => 'Connexion',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'nav_find_practitioner',
                'value' => 'Trouver un praticien',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_hero_subtitle',
                'value' => 'Entrez dans l\'univers de Zaya. Explorez les espaces, les rituels et les moments de connexion qui définissent notre chemin vers l\'harmonie holistique.',
                'type' => 'textarea',
                'section' => 'gallery_page',
                'max_length' => 300,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_sanctuary_title',
                'value' => 'Le Sanctuaire',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_movement_title',
                'value' => 'Mouvement Sacré',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_rituals_title',
                'value' => 'Rituels Ayurvédiques',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_retreats_title',
                'value' => 'Retraites Communautaires',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_cta_title',
                'value' => 'Commencez votre voyage vers la sérénité',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 100,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_cta_subtitle',
                'value' => 'Découvrez la guérison profonde du Sanctuaire de Bien-être Zaya.',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 200,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_cta_button_1',
                'value' => 'Réserver un praticien',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
                'language' => 'fr',
            ],
            [
                'key' => 'gallery_cta_button_2',
                'value' => 'Explorer nos services',
                'type' => 'text',
                'section' => 'gallery_page',
                'max_length' => 50,
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

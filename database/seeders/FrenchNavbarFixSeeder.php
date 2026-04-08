<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class FrenchNavbarFixSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Navbar / Header (FR)
            ['key' => 'footer_link_home', 'value' => 'Accueil'],
            ['key' => 'about_us_nav_title', 'value' => 'À propos'],
            ['key' => 'footer_link_who_we_are', 'value' => 'Qui sommes-nous ?'],
            ['key' => 'footer_link_what_we_do', 'value' => 'Ce que nous faisons'],
            ['key' => 'footer_link_our_team', 'value' => 'Notre équipe'],
            ['key' => 'footer_link_gallery', 'value' => 'Galerie'],
            ['key' => 'footer_link_blog', 'value' => 'Blog'],
            ['key' => 'footer_link_contact_us', 'value' => 'Contactez-nous'],
            ['key' => 'services_page_badge', 'value' => 'Services'],
            ['key' => 'services_title', 'value' => 'Nos spécialités'],
            ['key' => 'nav_login', 'value' => 'Connexion'],
            ['key' => 'nav_find_practitioner', 'value' => 'Trouver un praticien'],
        ];

        foreach ($settings as $row) {
            HomepageSetting::updateOrCreate(
                ['key' => $row['key'], 'language' => 'fr'],
                [
                    'value' => $row['value'],
                    'type' => 'text',
                    'section' => 'navbar',
                    'max_length' => 255,
                ]
            );
        }
    }
}


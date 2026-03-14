<?php

namespace Database\Seeders;

use App\Models\HomepageSetting;
use Illuminate\Database\Seeder;

class FindPractitionerPageFrenchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Page Settings for "Find Practitioner" in French
        $settings = [
            [
                'key' => 'find_practitioner_title',
                'value' => 'Experts dans votre quartier',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_subtitle',
                'value' => 'Des praticiens vérifiés prêts à soutenir votre parcours',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 150
            ],
            [
                'key' => 'find_practitioner_description',
                'value' => "Trouvez le soutien dont vous avez besoin, directement dans votre communauté. Chaque praticien répertorié ici fait partie du réseau ZAYA dirigé par des praticiens, engagé dans des soins éthiques et une guérison holistique.",
                'type' => 'textarea',
                'section' => 'find_practitioner_page',
                'max_length' => 500
            ],
            [
                'key' => 'find_practitioner_search_placeholder',
                'value' => 'Praticiens, traitements...',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_pincode_placeholder',
                'value' => 'Entrez le code postal',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_service_placeholder',
                'value' => 'Sélectionnez un service',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_mode_placeholder',
                'value' => 'Sélectionnez un mode',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_results_heading',
                'value' => 'Résultats de recherche basés sur',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_all_label',
                'value' => 'Tous les praticiens',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_no_results',
                'value' => 'Aucun praticien trouvé',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_no_results_sub',
                'value' => 'Essayez d’ajuster vos filtres ou de chercher dans une autre zone.',
                'type' => 'textarea',
                'section' => 'find_practitioner_page',
                'max_length' => 200
            ],
            [
                'key' => 'find_practitioner_service_ayurveda',
                'value' => 'Ayurveda & Panchakarma',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_mindfulness',
                'value' => 'Praticien de pleine conscience',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_yoga',
                'value' => 'Thérapie par le yoga',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_art',
                'value' => 'Art-thérapie',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_clinical',
                'value' => 'Psychologie clinique',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_sound',
                'value' => 'Thérapie par le son',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_hypno',
                'value' => 'Hypnothérapie',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_mode_online',
                'value' => 'En ligne',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_mode_offline',
                'value' => 'En personne',
                'type' => 'text',
                'section' => 'find_practitioner_page'
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

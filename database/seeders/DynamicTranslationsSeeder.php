<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\DB;

class DynamicTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            [
                'key' => 'cta_community_text',
                'section' => 'cta',
                'en_value' => 'Join a global community committed to authentic, expert-led wellness.',
                'fr_value' => 'Rejoignez une communauté mondiale engagée dans un bien-être authentique et dirigé par des experts.',
            ],
            [
                'key' => 'cta_book_session_btn',
                'section' => 'cta',
                'en_value' => 'Book a Session',
                'fr_value' => 'Réserver une séance',
            ],
            [
                'key' => 'cta_restore_rhythm_text',
                'section' => 'cta',
                'en_value' => 'Ready to restore your natural rhythm?',
                'fr_value' => 'Prêt à restaurer votre rythme naturel ?',
            ],
            [
                'key' => 'view_full_profile_btn',
                'section' => 'practitioner',
                'en_value' => 'View Full Profile',
                'fr_value' => 'Voir le profil complet',
            ],
            [
                'key' => 'read_more_link',
                'section' => 'general',
                'en_value' => 'Read More...',
                'fr_value' => 'Lire la suite...',
            ]
        ];

        DB::transaction(function () use ($translations) {
            foreach ($translations as $translation) {
                // English version
                HomepageSetting::updateOrCreate(
                    ['key' => $translation['key'], 'language' => 'en'],
                    [
                        'value' => $translation['en_value'],
                        'type' => 'text',
                        'section' => $translation['section'],
                    ]
                );

                // French version
                HomepageSetting::updateOrCreate(
                    ['key' => $translation['key'], 'language' => 'fr'],
                    [
                        'value' => $translation['fr_value'],
                        'type' => 'text',
                        'section' => $translation['section'],
                    ]
                );
            }
        });
    }
}

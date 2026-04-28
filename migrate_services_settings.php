<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$langs = ['en', 'fr'];

foreach ($langs as $lang) {
    $data = [
        [
            'key' => 'services_desc_ayurveda',
            'value' => ($lang == 'en' ? 'Restore your natural state of health through personalized Ayurvedic routines. Find the wellness path that completely aligns with your physical health.' : 'Retrouvez votre état de santé naturel grâce à des routines ayurvédiques personnalisées. Trouvez le chemin du bien-être qui correspond parfaitement à votre santé physique.'),
            'type' => 'textarea',
            'section' => 'services_page',
            'max_length' => 200,
            'language' => $lang
        ],
        [
            'key' => 'services_desc_yoga',
            'value' => ($lang == 'en' ? 'Realign your body and energetic pathways with our expert yoga guidance and therapeutic healing sessions.' : 'Réalignez votre corps et vos voies énergétiques grâce aux conseils de nos experts en yoga et à nos séances de guérison thérapeutique.'),
            'type' => 'textarea',
            'section' => 'services_page',
            'max_length' => 200,
            'language' => $lang
        ],
        [
            'key' => 'services_desc_counselling',
            'value' => ($lang == 'en' ? 'Nurture your mental well-being with our holistic counselling approaches designed to heal and strengthen.' : 'Prenez soin de votre bien-être mental grâce à nos approches de conseil holistique conçues pour guérir et renforcer.'),
            'type' => 'textarea',
            'section' => 'services_page',
            'max_length' => 200,
            'language' => $lang
        ],
        [
            'key' => 'services_desc_packages',
            'value' => ($lang == 'en' ? 'Comprehensive holistic wellness journeys tailored perfectly to your individual lifestyle and needs.' : 'Des parcours de bien-être holistiques complets, parfaitement adaptés à votre mode de vie et à vos besoins individuels.'),
            'type' => 'textarea',
            'section' => 'services_page',
            'max_length' => 200,
            'language' => $lang
        ],
        [
            'key' => 'services_search_placeholder',
            'value' => ($lang == 'en' ? 'Search services or conditions...' : 'Rechercher des services ou des pathologies...'),
            'type' => 'text',
            'section' => 'services_page',
            'max_length' => 100,
            'language' => $lang
        ]
    ];

    foreach ($data as $d) {
        \App\Models\HomepageSetting::updateOrCreate(
            ['key' => $d['key'], 'language' => $d['language']],
            $d
        );
    }
}

echo "Settings migrated successfully.\n";

<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$langs = ['en', 'fr'];

foreach ($langs as $lang) {
    $data = [
        [
            'key' => 'contact_info_location_label',
            'value' => ($lang == 'en' ? 'Location' : 'Localisation'),
            'type' => 'text',
            'section' => 'contact',
            'max_length' => 50,
            'language' => $lang
        ],
        [
            'key' => 'contact_info_phone_label',
            'value' => ($lang == 'en' ? 'Contact' : 'Contact'),
            'type' => 'text',
            'section' => 'contact',
            'max_length' => 50,
            'language' => $lang
        ],
        [
            'key' => 'contact_info_email_label',
            'value' => ($lang == 'en' ? 'Email' : 'Email'),
            'type' => 'text',
            'section' => 'contact',
            'max_length' => 50,
            'language' => $lang
        ],
        [
            'key' => 'contact_info_working_hours_label',
            'value' => ($lang == 'en' ? 'Working Hours' : 'Heures de Travail'),
            'type' => 'text',
            'section' => 'contact',
            'max_length' => 50,
            'language' => $lang
        ],
        [
            'key' => 'contact_form_consent_text',
            'value' => ($lang == 'en' 
                ? 'I give consent to Zaya for processing my personal data in accordance with GDPR' 
                : 'Je consens à ce que Zaya traite mes données personnelles conformément au RGPD'),
            'type' => 'textarea',
            'section' => 'contact',
            'max_length' => 255,
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

echo "Contact settings migrated successfully.\n";

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HomepageSetting;

$updates = [
    'services_page_badge' => 'Nos services',
    'services_title' => 'Nos services'
];

foreach ($updates as $key => $value) {
    $setting = HomepageSetting::where('key', $key)->where('language', 'fr')->first();
    if ($setting) {
        $setting->value = $value;
        $setting->save();
        echo "Updated $key for 'fr' to: $value\n";
    } else {
        // Create if it doesn't exist but exists for 'en'
        $enSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
        if ($enSetting) {
            HomepageSetting::create([
                'key' => $key,
                'value' => $value,
                'language' => 'fr',
                'type' => $enSetting->type,
                'section' => $enSetting->section,
                'max_length' => $enSetting->max_length
            ]);
            echo "Created $key for 'fr' with value: $value\n";
        }
    }
}

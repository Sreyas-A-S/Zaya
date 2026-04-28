<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HomepageSetting;

$keys = ['services_page_badge', 'services_title'];
$languages = ['en', 'fr'];

foreach ($languages as $lang) {
    echo "Language: $lang\n";
    foreach ($keys as $key) {
        $setting = HomepageSetting::where('key', $key)->where('language', $lang)->first();
        if ($setting) {
            echo "  $key: " . $setting->value . "\n";
        } else {
            echo "  $key: NOT FOUND\n";
        }
    }
}

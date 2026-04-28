<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HomepageSetting;

$settings = HomepageSetting::where('key', 'services_page_badge')->get();
foreach ($settings as $s) {
    echo "Key: {$s->key}, Lang: {$s->language}, Value: {$s->value}\n";
}

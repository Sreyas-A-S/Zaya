<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$marmaServices = \App\Models\Service::where('title', 'LIKE', '%Marma%')->pluck('id');
$userServiceCount = \App\Models\UserService::whereIn('service_id', $marmaServices)->count();
echo "Total UserServices with Marma Therapy: " . $userServiceCount . "\n";

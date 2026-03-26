<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\UserService;
use App\Models\User;

$user = User::where('role', 'practitioner')->first();
if (!$user) {
    echo "No practitioner found.\n";
    exit;
}

echo "Testing for User ID: {$user->id} ({$user->name})\n";

$myServices = UserService::with('service')
    ->where('user_id', $user->id)
    ->get()
    ->groupBy('service_id');

echo "User currently has " . $myServices->count() . " unique services.\n";

$availableServices = Service::whereNotIn('id', $myServices->keys())
    ->where('status', true)
    ->get();

echo "Available services count: " . $availableServices->count() . "\n";
foreach ($availableServices->take(5) as $s) {
    echo "- ID: {$s->id}, Title: {$s->title}\n";
}

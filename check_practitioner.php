<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Practitioner;

$slug = 'john-doe-61';
$p = Practitioner::where('slug', $slug)->first();

if (!$p) {
    echo "Practitioner not found\n";
    exit;
}

echo "ID: " . $p->id . "\n";
echo "Name: " . $p->first_name . " " . $p->last_name . "\n";
echo "Status: " . $p->status . "\n";
echo "User ID: " . $p->user_id . "\n";
if ($p->user) {
    echo "User Name: " . $p->user->name . "\n";
    $services = $p->user->userServices()->with('service')->get();
    echo "Total User Services: " . $services->count() . "\n";
    foreach ($services as $us) {
        echo " - Service: " . ($us->service->title ?? 'N/A') . " (Status: " . $us->status . ")\n";
    }
} else {
    echo "No associated user found\n";
}
echo "Average Rating: " . $p->average_rating . "\n";
echo "City: " . $p->city . "\n";

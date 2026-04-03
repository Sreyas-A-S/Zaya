<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = Illuminate\Http\Request::create('/api/referrable-practitioners', 'GET', ['roles' => ['practitioner']]);
$req->setUserResolver(function() { return App\Models\User::first(); });
echo json_encode(app(App\Http\Controllers\BookingController::class)->fetchReferrablePractitioners($req)->getData());

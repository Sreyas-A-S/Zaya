<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::whereIn('role', ['admin', 'super-admin'])->get(['id', 'national_id', 'languages']);
echo json_encode($users->toArray(), JSON_PRETTY_PRINT);

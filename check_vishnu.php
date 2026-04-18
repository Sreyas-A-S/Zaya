<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'vishnu@gmail.com')->first();
if ($user) {
    echo "User: " . $user->name . " (Role: " . $user->role . ")" . PHP_EOL;
    $d = $user->doctor;
    if ($d) {
        echo "Doctor profile found." . PHP_EOL;
        echo "reg_cert: " . ($d->reg_certificate_path ?? 'NULL') . PHP_EOL;
        echo "sig: " . ($d->digital_signature_path ?? 'NULL') . PHP_EOL;
        echo "pan: " . ($d->pan_upload_path ?? 'NULL') . PHP_EOL;
        echo "cheque: " . ($d->cancelled_cheque_path ?? 'NULL') . PHP_EOL;
    } else {
        echo "Doctor profile NOT found." . PHP_EOL;
    }
} else {
    echo "User vishnu@gmail.com NOT found." . PHP_EOL;
}

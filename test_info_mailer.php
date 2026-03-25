<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOTPMail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send sample mail via 'info' mailer...\n";
    echo "Host: " . config('mail.mailers.info.host') . "\n";
    echo "Username: " . config('mail.mailers.info.username') . "\n";
    echo "From: " . config('mail.mailers.info.from.address') . "\n";
    
    Mail::mailer('info')->to('sreyasas25@gmail.com')->send(new AdminOTPMail('123456'));

    echo "SUCCESS: Sample mail sent successfully.\n";
} catch (\Exception $e) {
    echo "FAILURE: Could not send mail.\n";
    echo "Error Message: " . $e->getMessage() . "\n";
}

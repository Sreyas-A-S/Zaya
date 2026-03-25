<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send test mail...\n";
    
    // Check current mailer settings
    echo "Mailer: " . config('mail.default') . "\n";
    echo "Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "Username: " . config('mail.mailers.smtp.username') . "\n";
    
    Mail::raw('This is a test email sent from Zaya Wellness local environment.', function ($message) {
        $message->to(['sreyasas25@gmail.com'])
                ->subject('Local SMTP Test');
    });

    echo "SUCCESS: Test mail sent successfully.\n";
} catch (\Exception $e) {
    echo "FAILURE: Could not send mail.\n";
    echo "Error Message: " . $e->getMessage() . "\n";
    echo "Exception Class: " . get_class($e) . "\n";
}

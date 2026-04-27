<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;

$booking = Booking::with('user')->find(36);
if ($booking) {
    echo "Booking 36 found.\n";
    echo "User ID: " . ($booking->user_id ?? 'NULL') . "\n";
    echo "User object: " . ($booking->user ? 'Exists' : 'NULL') . "\n";
    if ($booking->user) {
        echo "User email: " . $booking->user->email . "\n";
    }
} else {
    echo "Booking 36 not found.\n";
}

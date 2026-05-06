<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ReferralRequest;
use App\Models\User;

$user = User::where('role', 'practitioner')->first();
if (!$user) {
    echo "No practitioner found to test.\n";
    exit;
}

echo "Testing query for User ID: {$user->id}\n";

try {
    $pendingReferralRequests = ReferralRequest::with(['requester', 'booking.user'])
        ->where('recipient_id', $user->id)
        ->where('status', 'pending')
        ->latest()
        ->get();
    echo "Query successful. Found " . $pendingReferralRequests->count() . " requests.\n";
} catch (\Exception $e) {
    echo "Query failed: " . $e->getMessage() . "\n";
}

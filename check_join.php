<?php

use App\Models\User;
use App\Models\Doctor;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $userCount = User::where('role', 'doctor')->count();
    $doctorProfileCount = Doctor::count();
    
    echo "Users with role 'doctor': $userCount\n";
    echo "Total Doctor profiles: $doctorProfileCount\n";
    
    $joinedCount = User::where('role', 'doctor')
        ->join('doctors', 'users.id', '=', 'doctors.user_id')
        ->count();
        
    echo "Joined Count (Inner Join): $joinedCount\n";
    
    if ($joinedCount === 0 && $userCount > 0) {
        $sampleUser = User::where('role', 'doctor')->first();
        echo "Sample Doctor User ID: " . $sampleUser->id . "\n";
        $sampleProfile = Doctor::where('user_id', $sampleUser->id)->first();
        echo "Sample Profile for this User ID: " . ($sampleProfile ? 'Found (ID: '.$sampleProfile->id.')' : 'NOT FOUND') . "\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$patients = \App\Models\Patient::all();
foreach ($patients as $p) {
    try {
        $p->toArray();
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        $raw = $p->getRawOriginal('consultation_preferences');
        echo "Patient ID {$p->id} has invalid encryption. Raw value: '{$raw}'\n";
        
        // Fix it: set it to null or an empty array (properly encrypted) using DB facade to bypass model decryption
        try {
            \Illuminate\Support\Facades\DB::table('patients')
                ->where('id', $p->id)
                ->update(['consultation_preferences' => encrypt([])]);
            echo "Fixed Patient ID {$p->id} by re-encrypting empty array using DB facade.\n";
        } catch (\Exception $ex) {
            echo "Failed to fix Patient ID {$p->id}: " . $ex->getMessage() . "\n";
        }
    }
}

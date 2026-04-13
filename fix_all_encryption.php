<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$modelsToFix = [
    \App\Models\Patient::class => 'patients',
    \App\Models\Booking::class => 'bookings',
    \App\Models\ClinicalDocument::class => 'clinical_documents',
    \App\Models\ConsultationForm::class => 'consultation_forms',
];

foreach ($modelsToFix as $class => $table) {
    echo "Checking $class...\n";
    $items = $class::all();
    $model = new $class;
    $casts = $model->getCasts();
    
    foreach ($items as $item) {
        foreach ($casts as $field => $cast) {
            if (str_contains($cast, 'encrypted')) {
                try {
                    $item->$field;
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $raw = $item->getRawOriginal($field);
                    echo "Table $table, ID {$item->id}, Field $field has invalid encryption. Raw: '$raw'\n";
                    
                    $defaultValue = null;
                    if (str_contains($cast, 'json') || str_contains($cast, 'array')) {
                        $defaultValue = [];
                    }
                    
                    \Illuminate\Support\Facades\DB::table($table)
                        ->where('id', $item->id)
                        ->update([$field => $defaultValue !== null ? encrypt($defaultValue) : null]);
                        
                    echo "Fixed $field for ID {$item->id}.\n";
                }
            }
        }
    }
}
echo "Done.\n";

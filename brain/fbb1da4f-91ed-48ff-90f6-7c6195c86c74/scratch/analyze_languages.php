<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translator;

$natives = Translator::distinct()->pluck('native_language')->toArray();
$sources = Translator::all()->pluck('source_languages')->flatten()->unique()->values()->toArray();
$targets = Translator::all()->pluck('target_languages')->flatten()->unique()->values()->toArray();

print_r([
    'natives' => $natives,
    'sources' => $sources,
    'targets' => $targets
]);

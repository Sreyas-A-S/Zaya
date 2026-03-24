<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $langs = DB::table('languages')->select('code', 'name')->orderBy('name')->get();
    foreach ($langs as $l) {
        echo $l->code . " => " . $l->name . PHP_EOL;
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}

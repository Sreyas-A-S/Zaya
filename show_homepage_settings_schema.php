<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$result = DB::select("SHOW CREATE TABLE homepage_settings");
foreach ($result as $row) {
    echo $row->{'Create Table'} . PHP_EOL;
}

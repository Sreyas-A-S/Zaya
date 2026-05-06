<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tableName = 'referral_requests';
if (Schema::hasTable($tableName)) {
    echo "Table '$tableName' exists.\n";
} else {
    echo "Table '$tableName' does NOT exist.\n";
}

$dbName = DB::getDatabaseName();
echo "Current database: $dbName\n";

try {
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        echo "- " . current((array)$table) . "\n";
    }
} catch (\Exception $e) {
    echo "Error listing tables: " . $e->getMessage() . "\n";
}

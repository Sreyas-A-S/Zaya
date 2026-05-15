<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tableName = 'referral_requests';
$columns = Schema::getColumnListing($tableName);
echo "Columns in '$tableName':\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

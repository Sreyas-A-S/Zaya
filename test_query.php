<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

$query = User::where('role', 'translator')
    ->leftJoin('translators', 'users.id', '=', 'translators.user_id')
    ->select(['users.id as user_id', 'users.name', 'translators.country']);

$query->where(function ($q) {
    $tableName = $q->getQuery()->from;
    echo "Table Name: " . $tableName . PHP_EOL;
});

echo "SQL: " . $query->toSql() . PHP_EOL;

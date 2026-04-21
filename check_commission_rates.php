<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ReferralCommissionRate;

$rates = ReferralCommissionRate::all();
foreach ($rates as $rate) {
    echo "ID: {$rate->id}, Country: {$rate->country_id}, Type: {$rate->type}, Referred: {$rate->referred_role}, Referrer: {$rate->referrer_role}, Zaya: {$rate->company_commission_percent}, Referrer%: {$rate->referrer_commission_percent}\n";
}

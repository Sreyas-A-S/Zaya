<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'vishnu@gmail.com')->first();
$profile = $user->profile;

echo "Role: " . $user->role . PHP_EOL;
echo "Field: reg_certificate_path" . PHP_EOL;
echo "Value: " . $profile->reg_certificate_path . PHP_EOL;
echo "Empty: " . (empty($profile->reg_certificate_path) ? 'Yes' : 'No') . PHP_EOL;
echo "Set: " . (isset($profile->reg_certificate_path) ? 'Yes' : 'No') . PHP_EOL;

echo "Field: registration_certificate_path (accessor)" . PHP_EOL;
echo "Value: " . $profile->registration_certificate_path . PHP_EOL;
echo "Empty: " . (empty($profile->registration_certificate_path) ? 'Yes' : 'No') . PHP_EOL;

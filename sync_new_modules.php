<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

$modules = [
    'Newsletters' => ['view', 'delete', 'update-status'],
    'Coins Management' => ['view', 'edit'],
    'Referral Commissions' => ['view', 'edit'],
    'Financial' => ['view', 'show', 'download'],
    'Gallery Page' => ['view', 'edit'],
    'Footer Page' => ['view', 'edit'],
    'Admin Panel Settings' => ['view', 'edit'],
    'Client Panel Settings' => ['view', 'edit'],
    'Invoice Settings' => ['view', 'edit'],
    'Invoices' => ['view', 'preview'],
    'Email Logs' => ['view', 'delete'],
];

foreach ($modules as $module => $actions) {
    foreach ($actions as $action) {
        Permission::updateOrCreate([
            'slug' => Str::slug($module . ' ' . $action)
        ], [
            'name' => ucfirst($action) . ' ' . $module,
            'group' => $module,
        ]);
    }
}

// 1. Super Admin
$superAdmin = Role::where('name', 'Super Admin')->first();
if ($superAdmin) {
    $superAdmin->permissions()->sync(Permission::all());
    echo "Super Admin updated.\n";
}

// 2. Admin
$adminRole = Role::where('name', 'Admin')->first();
if ($adminRole) {
    $adminRole->permissions()->syncWithoutDetaching(
        Permission::whereIn('group', [
            'Newsletters', 'Financial', 'Invoices', 'Email Logs', 'Gallery Page', 'Footer Page',
            'Admin Panel Settings', 'Client Panel Settings', 'Invoice Settings'
        ])->get()
    );
    echo "Admin updated.\n";
}

// 3. Country Admin
$countryAdmin = Role::where('name', 'Country Admin')->first();
if ($countryAdmin) {
    $countryAdmin->permissions()->syncWithoutDetaching(
        Permission::whereNotIn('group', ['Roles', 'Settings', 'Admin Panel Settings', 'Client Panel Settings'])->get()
    );
    echo "Country Admin updated.\n";
}

// 4. Financial Manager
$financialManager = Role::where('name', 'Financial Manager')->first();
if ($financialManager) {
    $financialManager->permissions()->syncWithoutDetaching(
        Permission::whereIn('group', [
            'Financial', 'Invoices', 'Coins Management', 'Referral Commissions', 'Invoice Settings'
        ])->get()
    );
    echo "Financial Manager updated.\n";
}

// 5. Content Manager
$contentManager = Role::where('name', 'Content Manager')->first();
if ($contentManager) {
    $contentManager->permissions()->syncWithoutDetaching(
        Permission::whereIn('group', [
            'Gallery Page', 'Footer Page', 'Admin Panel Settings'
        ])->get()
    );
    echo "Content Manager updated.\n";
}

// 6. User Manager
$userManager = Role::where('name', 'User Manager')->first();
if ($userManager) {
    $userManager->permissions()->syncWithoutDetaching(
        Permission::whereIn('group', [
            'Newsletters', 'Email Logs'
        ])->get()
    );
    echo "User Manager updated.\n";
}

echo "All roles synchronized.\n";

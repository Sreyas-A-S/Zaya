<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Dashboard' => ['view'],
            'Users' => ['view', 'create', 'edit', 'delete'],
            'Doctors' => ['view', 'create', 'edit', 'delete'],
            'Practitioners' => ['view', 'create', 'edit', 'delete'],
            'Clients' => ['view', 'create', 'edit', 'delete'],
            'Roles' => ['view', 'create', 'edit', 'delete'],
            'Settings' => ['view', 'edit'],
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

        // Create some roles
        $adminRole = Role::updateOrCreate(['name' => 'Super Admin']);
        $adminRole->permissions()->sync(Permission::all());

        $managerRole = Role::updateOrCreate(['name' => 'Manager']);
        $managerRole->permissions()->sync(Permission::whereIn('group', ['Dashboard', 'Users', 'Doctors'])->get());

        Role::updateOrCreate(['name' => 'Editor']);
        Role::updateOrCreate(['name' => 'Viewer']);
    }
}
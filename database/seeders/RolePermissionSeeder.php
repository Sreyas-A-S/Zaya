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
            'Doctors' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Practitioners' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Mindfulness Practitioners' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Yoga Therapists' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Translators' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Clients' => ['view', 'create', 'edit', 'delete', 'status-toggle'],
            'Roles' => ['view', 'create', 'edit', 'delete'],
            'Services' => ['view', 'create', 'edit', 'delete', 'assign-engineer'],
            'Master Data' => ['view', 'create', 'edit', 'delete'],
            'Testimonials' => ['view', 'create', 'edit', 'delete'],
            'Practitioner Reviews' => ['view', 'delete'],
            'Settings' => ['view', 'edit'],
            'Home Page' => ['view', 'edit'],
            'About Page' => ['view', 'edit'],
            'Services Page' => ['view', 'edit'],
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

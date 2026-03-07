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
            'Admins' => ['view', 'create', 'edit', 'delete'],
            'Doctors' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Practitioners' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Mindfulness Practitioners' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Yoga Therapists' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Translators' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Clients' => ['view', 'create', 'edit', 'delete', 'status-toggle'],
            'Roles' => ['view', 'create', 'edit', 'delete'],
            'Services' => ['view', 'create', 'edit', 'delete', 'assign-engineer'],
            'Packages' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Other Fees' => ['view', 'create', 'edit', 'delete', 'update-status'],
            'Credentials' => ['view', 'edit'],
            'Master Data' => ['view', 'create', 'edit', 'delete'],
            'Testimonials' => ['view', 'create', 'edit', 'delete'],
            'Practitioner Reviews' => ['view', 'delete', 'status'],
            'Settings' => ['view', 'edit'],
            'Home Page' => ['view', 'edit'],
            'About Page' => ['view', 'edit'],
            'Services Page' => ['view', 'edit'],
            'Contact Messages' => ['view', 'delete'],
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

        $roleNames = [
            'Super Admin',
            'Admin',
            'Country Admin',
            'Financial Manager',
            'Content Manager',
            'User Manager'
        ];

        // Remove any roles not in the specified list
        Role::whereNotIn('name', $roleNames)->delete();

        // 1. Super Admin - Bypasses checks (has everything)
        $superAdmin = Role::updateOrCreate(['name' => 'Super Admin']);
        $superAdmin->permissions()->sync(Permission::all());

        // 2. Admin - Highly restricted to demonstrate RBAC
        $adminRole = Role::updateOrCreate(['name' => 'Admin']);
        $adminRole->permissions()->sync(
            Permission::whereIn('group', ['Dashboard', 'Users', 'Services', 'Admins'])->get()
        );

        // 3. Country Admin - Almost everything except roles and system settings
        $countryAdmin = Role::updateOrCreate(['name' => 'Country Admin']);
        $countryAdmin->permissions()->sync(
            Permission::whereNotIn('group', ['Roles', 'Settings'])->get()
        );

        // 4. Financial Manager - Focus on Packages, Other Fees
        $financialManager = Role::updateOrCreate(['name' => 'Financial Manager']);
        $financialManager->permissions()->sync(
            Permission::whereIn('group', ['Dashboard', 'Packages', 'Other Fees'])->get()
        );

        // 5. Content Manager - Services, Master Data, CMS
        $contentManager = Role::updateOrCreate(['name' => 'Content Manager']);
        $contentManager->permissions()->sync(
            Permission::whereIn('group', [
                'Dashboard', 'Services', 'Master Data', 'Testimonials', 
                'Home Page', 'About Page', 'Services Page'
            ])->get()
        );

        // 6. User Manager - Users, Doctors, Practitioners, etc.
        $userManager = Role::updateOrCreate(['name' => 'User Manager']);
        $userManager->permissions()->sync(
            Permission::whereIn('group', [
                'Dashboard', 'Users', 'Doctors', 'Practitioners', 
                'Mindfulness Practitioners', 'Yoga Therapists', 
                'Translators', 'Clients', 'Credentials', 'Practitioner Reviews', 'Admins', 'Contact Messages'
            ])->get()
        );
    }
}

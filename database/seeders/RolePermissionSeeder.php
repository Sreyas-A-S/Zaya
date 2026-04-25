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
            'Dashboard' => ['actions' => ['view'], 'category' => 'General'],
            'Users' => ['actions' => ['view', 'create', 'edit', 'delete'], 'category' => 'Users'],
            'Admins' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Backend Users'],
            'Finance Managers' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Backend Users'],
            'Content Managers' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Backend Users'],
            'User Managers' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Backend Users'],
            'Doctors' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Practitioners' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Mindfulness Practitioners' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Yoga Therapists' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Translators' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Clients' => ['actions' => ['view', 'create', 'edit', 'delete', 'status-toggle'], 'category' => 'Users'],
            'Forms' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
            'Roles' => ['actions' => ['view', 'create', 'edit', 'delete'], 'category' => 'Backend Users'],
            'Services' => ['actions' => ['view', 'create', 'edit', 'delete', 'assign-engineer'], 'category' => 'Services'],
            'Packages' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Finance'],
            'Promo Codes' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Finance'],
            'Other Fees' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Finance'],
            'Credentials' => ['actions' => ['view', 'edit'], 'category' => 'Backend Users'],
            'Countries' => ['actions' => ['view', 'create', 'edit', 'delete', 'status'], 'category' => 'General'],
            'Languages' => ['actions' => ['view', 'create', 'edit', 'delete', 'status'], 'category' => 'General'],
            'Master Data' => ['actions' => ['view', 'create', 'edit', 'delete', 'status'], 'category' => 'Services'],
            'Testimonials' => ['actions' => ['view', 'create', 'edit', 'delete'], 'category' => 'Public Site'],
            'Practitioner Reviews' => ['actions' => ['view', 'delete', 'status'], 'category' => 'Users'],
            'Settings' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Home Page' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'About Page' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Services Page' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Contact Messages' => ['actions' => ['view', 'delete'], 'category' => 'Public Site'],
            'Newsletters' => ['actions' => ['view', 'delete', 'update-status'], 'category' => 'Public Site'],
            'Coins Management' => ['actions' => ['view', 'edit'], 'category' => 'Finance'],
            'Referral Commissions' => ['actions' => ['view', 'edit'], 'category' => 'Finance'],
            'Financial' => ['actions' => ['view', 'show', 'download'], 'category' => 'Finance'],
            'Gallery Page' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Footer Page' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Admin Panel Settings' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Client Panel Settings' => ['actions' => ['view', 'edit'], 'category' => 'Page Settings'],
            'Invoice Settings' => ['actions' => ['view', 'edit'], 'category' => 'Finance'],
            'Invoices' => ['actions' => ['view', 'preview'], 'category' => 'Finance'],
            'Email Logs' => ['actions' => ['view', 'delete'], 'category' => 'Public Site'],
            'Bookings' => ['actions' => ['view', 'create', 'edit', 'delete', 'update-status'], 'category' => 'Users'],
        ];

        foreach ($modules as $module => $data) {
            foreach ($data['actions'] as $action) {
                Permission::updateOrCreate([
                    'slug' => Str::slug($module . ' ' . $action)
                ], [
                    'name' => ucfirst($action) . ' ' . $module,
                    'group' => $module,
                    'category' => $data['category'],
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
            Permission::whereIn('group', [
                'Dashboard', 'Users', 'Services', 'Admins', 'Finance Managers', 'Content Managers', 'User Managers',
                'Doctors', 'Practitioners', 'Mindfulness Practitioners', 'Yoga Therapists', 'Translators', 'Clients',
                'Forms', 'Newsletters', 'Financial', 'Invoices', 'Email Logs', 'Gallery Page', 'Footer Page',
                'Admin Panel Settings', 'Client Panel Settings', 'Invoice Settings', 'Countries', 'Languages', 'Bookings'
            ])->get()
        );

        // 3. Country Admin - Almost everything except roles and system settings
        $countryAdmin = Role::updateOrCreate(['name' => 'Country Admin']);
        $countryAdmin->permissions()->sync(
            Permission::whereNotIn('group', ['Roles', 'Settings', 'Admin Panel Settings', 'Client Panel Settings'])->get()
        );

        // 4. Financial Manager - Focus on Packages, Other Fees, Financial
        $financialManager = Role::updateOrCreate(['name' => 'Financial Manager']);
        $financialManager->permissions()->sync(
            Permission::whereIn('group', [
                'Dashboard', 'Packages', 'Promo Codes', 'Other Fees',
                'Doctors', 'Practitioners', 'Mindfulness Practitioners', 'Yoga Therapists', 'Translators',
                'Financial', 'Invoices', 'Coins Management', 'Referral Commissions', 'Invoice Settings', 'Bookings', 'Finance Managers'
            ])->get()
        );

        // 5. Content Manager - Services, Master Data, CMS
        $contentManager = Role::updateOrCreate(['name' => 'Content Manager']);
        $contentManager->permissions()->sync(
            Permission::whereIn('group', [
                'Dashboard', 'Services', 'Master Data', 'Testimonials', 
                'Home Page', 'About Page', 'Services Page',
                'Doctors', 'Practitioners', 'Mindfulness Practitioners', 'Yoga Therapists', 'Translators',
                'Gallery Page', 'Footer Page', 'Admin Panel Settings', 'Content Managers', 'Countries', 'Languages'
            ])->get()
        );

        // 6. User Manager - Users, Doctors, Practitioners, etc.
        $userManager = Role::updateOrCreate(['name' => 'User Manager']);
        $userManager->permissions()->sync(
            Permission::whereIn('group', [
                'Dashboard', 'Users', 'Doctors', 'Practitioners', 
                'Mindfulness Practitioners', 'Yoga Therapists', 
                'Translators', 'Clients', 'Forms', 'Credentials', 'Practitioner Reviews', 'Admins', 'Contact Messages',
                'Newsletters', 'Email Logs', 'User Managers', 'Bookings'
            ])->get()
        );
    }
}

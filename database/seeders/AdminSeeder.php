<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Country Admin',
                'email' => 'countryadmin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'country-admin',
            ],
            [
                'name' => 'Financial Manager',
                'email' => 'finance@admin.com',
                'password' => Hash::make('password'),
                'role' => 'financial-manager',
            ],
            [
                'name' => 'Content Manager',
                'email' => 'content@admin.com',
                'password' => Hash::make('password'),
                'role' => 'content-manager',
            ],
            [
                'name' => 'User Manager',
                'email' => 'usermanager@admin.com',
                'password' => Hash::make('password'),
                'role' => 'user-manager',
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}

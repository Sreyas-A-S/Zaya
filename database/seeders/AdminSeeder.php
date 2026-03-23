<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['name' => 'Super Admin', 'first_name' => 'Super', 'last_name' => 'Admin', 'email' => 'superadmin@admin.com', 'role' => 'super-admin'],
            ['name' => 'Primary Admin', 'first_name' => 'Admin', 'last_name' => 'User', 'email' => 'admin@admin.com', 'role' => 'admin'],
            ['name' => 'Primary Country Admin', 'first_name' => 'Country', 'last_name' => 'Admin', 'email' => 'countryadmin@admin.com', 'role' => 'country-admin'],
            ['name' => 'Sarah Johnson', 'first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'admin1@zaya.com', 'role' => 'admin'],
            ['name' => 'Michael Chen', 'first_name' => 'Michael', 'last_name' => 'Chen', 'email' => 'admin2@zaya.com', 'role' => 'admin'],
            ['name' => 'Elena Rodriguez', 'first_name' => 'Elena', 'last_name' => 'Rodriguez', 'email' => 'admin3@zaya.com', 'role' => 'admin'],
            ['name' => 'David Smith', 'first_name' => 'David', 'last_name' => 'Smith', 'email' => 'admin4@zaya.com', 'role' => 'admin'],
            ['name' => 'Aisha Petrova', 'first_name' => 'Aisha', 'last_name' => 'Petrova', 'email' => 'admin5@zaya.com', 'role' => 'admin'],
            ['name' => 'Marcus Weber', 'first_name' => 'Marcus', 'last_name' => 'Weber', 'email' => 'admin6@zaya.com', 'role' => 'admin'],
            ['name' => 'Yuki Tanaka', 'first_name' => 'Yuki', 'last_name' => 'Tanaka', 'email' => 'admin7@zaya.com', 'role' => 'admin'],
            ['name' => 'Isabella Rossi', 'first_name' => 'Isabella', 'last_name' => 'Rossi', 'email' => 'admin8@zaya.com', 'role' => 'admin'],
            ['name' => 'Liam O\'Connor', 'first_name' => 'Liam', 'last_name' => 'O\'Connor', 'email' => 'admin9@zaya.com', 'role' => 'admin'],
            ['name' => 'Sofia Muller', 'first_name' => 'Sofia', 'last_name' => 'Muller', 'email' => 'admin10@zaya.com', 'role' => 'admin'],
            ['name' => 'Omar Hassan', 'first_name' => 'Omar', 'last_name' => 'Hassan', 'email' => 'admin11@zaya.com', 'role' => 'admin'],
            ['name' => 'Emma Larsson', 'first_name' => 'Emma', 'last_name' => 'Larsson', 'email' => 'admin12@zaya.com', 'role' => 'admin'],
            ['name' => 'Lucas Silva', 'first_name' => 'Lucas', 'last_name' => 'Silva', 'email' => 'admin13@zaya.com', 'role' => 'admin'],
            ['name' => 'Amelie Dubois', 'first_name' => 'Amelie', 'last_name' => 'Dubois', 'email' => 'admin14@zaya.com', 'role' => 'admin'],
            ['name' => 'Noah Williams', 'first_name' => 'Noah', 'last_name' => 'Williams', 'email' => 'admin15@zaya.com', 'role' => 'admin'],
            ['name' => 'Fatima Al-Sayed', 'first_name' => 'Fatima', 'last_name' => 'Al-Sayed', 'email' => 'admin16@zaya.com', 'role' => 'admin'],
            ['name' => 'Mateo Garcia', 'first_name' => 'Mateo', 'last_name' => 'Garcia', 'email' => 'admin17@zaya.com', 'role' => 'admin'],
            ['name' => 'Chloe Bennett', 'first_name' => 'Chloe', 'last_name' => 'Bennett', 'email' => 'admin18@zaya.com', 'role' => 'admin'],
            ['name' => 'Sven Lindholm', 'first_name' => 'Sven', 'last_name' => 'Lindholm', 'email' => 'admin19@zaya.com', 'role' => 'admin'],
            ['name' => 'Ji-won Park', 'first_name' => 'Ji-won', 'last_name' => 'Park', 'email' => 'admin20@zaya.com', 'role' => 'admin'],
            ['name' => 'Rafael Moreno', 'first_name' => 'Rafael', 'last_name' => 'Moreno', 'email' => 'admin21@zaya.com', 'role' => 'admin'],
            ['name' => 'Layla Roberts', 'first_name' => 'Layla', 'last_name' => 'Roberts', 'email' => 'admin22@zaya.com', 'role' => 'admin'],
            ['name' => 'Hans Schmidt', 'first_name' => 'Hans', 'last_name' => 'Schmidt', 'email' => 'admin23@zaya.com', 'role' => 'admin'],
            ['name' => 'Zoe Papadopoulos', 'first_name' => 'Zoe', 'last_name' => 'Papadopoulos', 'email' => 'admin24@zaya.com', 'role' => 'admin'],
            ['name' => 'Ivan Petrov', 'first_name' => 'Ivan', 'last_name' => 'Petrov', 'email' => 'admin25@zaya.com', 'role' => 'admin'],
            ['name' => 'Mia Kim', 'first_name' => 'Mia', 'last_name' => 'Kim', 'email' => 'admin26@zaya.com', 'role' => 'admin'],
            ['name' => 'Arjun Gupta', 'first_name' => 'Arjun', 'last_name' => 'Gupta', 'email' => 'admin27@zaya.com', 'role' => 'admin'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                array_merge($admin, [
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'phone' => '1234567890'
                ])
            );
        }
    }
}

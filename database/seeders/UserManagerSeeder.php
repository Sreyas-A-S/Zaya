<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserManagerSeeder extends Seeder
{
    public function run(): void
    {
        $managers = [
            ['name' => 'Ursula User', 'first_name' => 'Ursula', 'last_name' => 'User', 'email' => 'usermanager@admin.com'],
            ['name' => 'Xander Harris', 'first_name' => 'Xander', 'last_name' => 'Harris', 'email' => 'usermanager1@zaya.com'],
            ['name' => 'Yvonne Strahovski', 'first_name' => 'Yvonne', 'last_name' => 'Strahovski', 'email' => 'usermanager2@zaya.com'],
            ['name' => 'Zane Grey', 'first_name' => 'Zane', 'last_name' => 'Grey', 'email' => 'usermanager3@zaya.com'],
            ['name' => 'Abigail Adams', 'first_name' => 'Abigail', 'last_name' => 'Adams', 'email' => 'usermanager4@zaya.com'],
            ['name' => 'Barney Stinson', 'first_name' => 'Barney', 'last_name' => 'Stinson', 'email' => 'usermanager5@zaya.com'],
            ['name' => 'Claire Dunphy', 'first_name' => 'Claire', 'last_name' => 'Dunphy', 'email' => 'usermanager6@zaya.com'],
            ['name' => 'Dwight Schrute', 'first_name' => 'Dwight', 'last_name' => 'Schrute', 'email' => 'usermanager7@zaya.com'],
            ['name' => 'Elaine Benes', 'first_name' => 'Elaine', 'last_name' => 'Benes', 'email' => 'usermanager8@zaya.com'],
            ['name' => 'Frank Reynolds', 'first_name' => 'Frank', 'last_name' => 'Reynolds', 'email' => 'usermanager9@zaya.com'],
            ['name' => 'Gloria Pritchett', 'first_name' => 'Gloria', 'last_name' => 'Pritchett', 'email' => 'usermanager10@zaya.com'],
            ['name' => 'Hank Hill', 'first_name' => 'Hank', 'last_name' => 'Hill', 'email' => 'usermanager11@zaya.com'],
            ['name' => 'Iris West', 'first_name' => 'Iris', 'last_name' => 'West', 'email' => 'usermanager12@zaya.com'],
            ['name' => 'Jerry Seinfeld', 'first_name' => 'Jerry', 'last_name' => 'Seinfeld', 'email' => 'usermanager13@zaya.com'],
            ['name' => 'Kimmy Schmidt', 'first_name' => 'Kimmy', 'last_name' => 'Schmidt', 'email' => 'usermanager14@zaya.com'],
            ['name' => 'Leslie Knope', 'first_name' => 'Leslie', 'last_name' => 'Knope', 'email' => 'usermanager15@zaya.com'],
            ['name' => 'Michael Scott', 'first_name' => 'Michael', 'last_name' => 'Scott', 'email' => 'usermanager16@zaya.com'],
            ['name' => 'Ned Stark', 'first_name' => 'Ned', 'last_name' => 'Stark', 'email' => 'usermanager17@zaya.com'],
            ['name' => 'Oscar Martinez', 'first_name' => 'Oscar', 'last_name' => 'Martinez', 'email' => 'usermanager18@zaya.com'],
            ['name' => 'Pam Beesly', 'first_name' => 'Pam', 'last_name' => 'Beesly', 'email' => 'usermanager19@zaya.com'],
            ['name' => 'Quinn Fabray', 'first_name' => 'Quinn', 'last_name' => 'Fabray', 'email' => 'usermanager20@zaya.com'],
            ['name' => 'Ron Swanson', 'first_name' => 'Ron', 'last_name' => 'Swanson', 'email' => 'usermanager21@zaya.com'],
            ['name' => 'Sherlock Holmes', 'first_name' => 'Sherlock', 'last_name' => 'Holmes', 'email' => 'usermanager22@zaya.com'],
            ['name' => 'Tyrion Lannister', 'first_name' => 'Tyrion', 'last_name' => 'Lannister', 'email' => 'usermanager23@zaya.com'],
            ['name' => 'Ugly Betty', 'first_name' => 'Ugly', 'last_name' => 'Betty', 'email' => 'usermanager24@zaya.com'],
            ['name' => 'Vince Masuka', 'first_name' => 'Vince', 'last_name' => 'Masuka', 'email' => 'usermanager25@zaya.com'],
            ['name' => 'Walter White', 'first_name' => 'Walter', 'last_name' => 'White', 'email' => 'usermanager26@zaya.com'],
            ['name' => 'Xena Warrior', 'first_name' => 'Xena', 'last_name' => 'Warrior', 'email' => 'usermanager27@zaya.com'],
            ['name' => 'Yoda Grandmaster', 'first_name' => 'Yoda', 'last_name' => 'Grandmaster', 'email' => 'usermanager28@zaya.com'],
            ['name' => 'Zelda Hyrule', 'first_name' => 'Zelda', 'last_name' => 'Hyrule', 'email' => 'usermanager29@zaya.com'],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                array_merge($manager, [
                    'password' => Hash::make('password'),
                    'role' => 'user-manager',
                    'status' => 'active',
                    'phone' => '5544332211'
                ])
            );
        }
    }
}

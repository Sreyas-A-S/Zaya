<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContentManagerSeeder extends Seeder
{
    public function run(): void
    {
        $managers = [
            ['name' => 'John Content', 'first_name' => 'John', 'last_name' => 'Content', 'email' => 'content@admin.com'],
            ['name' => 'Alice Thompson', 'first_name' => 'Alice', 'last_name' => 'Thompson', 'email' => 'content1@zaya.com'],
            ['name' => 'Robert Wilson', 'first_name' => 'Robert', 'last_name' => 'Wilson', 'email' => 'content2@zaya.com'],
            ['name' => 'Maria Silva', 'first_name' => 'Maria', 'last_name' => 'Silva', 'email' => 'content3@zaya.com'],
            ['name' => 'Kenji Yamamoto', 'first_name' => 'Kenji', 'last_name' => 'Yamamoto', 'email' => 'content4@zaya.com'],
            ['name' => 'Svetlana Ivanova', 'first_name' => 'Svetlana', 'last_name' => 'Ivanova', 'email' => 'content5@zaya.com'],
            ['name' => 'Ahmed Mansour', 'first_name' => 'Ahmed', 'last_name' => 'Mansour', 'email' => 'content6@zaya.com'],
            ['name' => 'Isabelle Laurent', 'first_name' => 'Isabelle', 'last_name' => 'Laurent', 'email' => 'content7@zaya.com'],
            ['name' => 'Thomas Becker', 'first_name' => 'Thomas', 'last_name' => 'Becker', 'email' => 'content8@zaya.com'],
            ['name' => 'Sunita Reddy', 'first_name' => 'Sunita', 'last_name' => 'Reddy', 'email' => 'content9@zaya.com'],
            ['name' => 'Diego Fernandez', 'first_name' => 'Diego', 'last_name' => 'Fernandez', 'email' => 'content10@zaya.com'],
            ['name' => 'Grace O\'Malley', 'first_name' => 'Grace', 'last_name' => 'O\'Malley', 'email' => 'content11@zaya.com'],
            ['name' => 'Hiroshi Sato', 'first_name' => 'Hiroshi', 'last_name' => 'Sato', 'email' => 'content12@zaya.com'],
            ['name' => 'Elena Popescu', 'first_name' => 'Elena', 'last_name' => 'Popescu', 'email' => 'content13@zaya.com'],
            ['name' => 'William Wright', 'first_name' => 'William', 'last_name' => 'Wright', 'email' => 'content14@zaya.com'],
            ['name' => 'Amira El-Fassi', 'first_name' => 'Amira', 'last_name' => 'El-Fassi', 'email' => 'content15@zaya.com'],
            ['name' => 'Lars Andersen', 'first_name' => 'Lars', 'last_name' => 'Andersen', 'email' => 'content16@zaya.com'],
            ['name' => 'Yasmine Habibi', 'first_name' => 'Yasmine', 'last_name' => 'Habibi', 'email' => 'content17@zaya.com'],
            ['name' => 'Oliver Schmidt', 'first_name' => 'Oliver', 'last_name' => 'Schmidt', 'email' => 'content18@zaya.com'],
            ['name' => 'Zoe Mitchell', 'first_name' => 'Zoe', 'last_name' => 'Mitchell', 'email' => 'content19@zaya.com'],
            ['name' => 'Paolo Rossi', 'first_name' => 'Paolo', 'last_name' => 'Rossi', 'email' => 'content20@zaya.com'],
            ['name' => 'Lin Wei', 'first_name' => 'Lin', 'last_name' => 'Wei', 'email' => 'content21@zaya.com'],
            ['name' => 'Freya Johansson', 'first_name' => 'Freya', 'last_name' => 'Johansson', 'email' => 'content22@zaya.com'],
            ['name' => 'Luca Ferrari', 'first_name' => 'Luca', 'last_name' => 'Ferrari', 'email' => 'content23@zaya.com'],
            ['name' => 'Nina Petrova', 'first_name' => 'Nina', 'last_name' => 'Petrova', 'email' => 'content24@zaya.com'],
            ['name' => 'Oscar Wilde', 'first_name' => 'Oscar', 'last_name' => 'Wilde', 'email' => 'content25@zaya.com'],
            ['name' => 'Zara Ali', 'first_name' => 'Zara', 'last_name' => 'Ali', 'email' => 'content26@zaya.com'],
            ['name' => 'Leo Kim', 'first_name' => 'Leo', 'last_name' => 'Kim', 'email' => 'content27@zaya.com'],
            ['name' => 'Sophie Martin', 'first_name' => 'Sophie', 'last_name' => 'Martin', 'email' => 'content28@zaya.com'],
            ['name' => 'Xavier Blanc', 'first_name' => 'Xavier', 'last_name' => 'Blanc', 'email' => 'content29@zaya.com'],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                array_merge($manager, [
                    'password' => Hash::make('password'),
                    'role' => 'content-manager',
                    'status' => 'active',
                    'phone' => '9876543210'
                ])
            );
        }
    }
}

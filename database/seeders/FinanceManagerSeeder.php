<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FinanceManagerSeeder extends Seeder
{
    public function run(): void
    {
        $managers = [
            ['name' => 'Frank Finance', 'first_name' => 'Frank', 'last_name' => 'Finance', 'email' => 'finance@admin.com'],
            ['name' => 'Benjamin Franklin', 'first_name' => 'Benjamin', 'last_name' => 'Franklin', 'email' => 'finance1@zaya.com'],
            ['name' => 'Victoria Sterling', 'first_name' => 'Victoria', 'last_name' => 'Sterling', 'email' => 'finance2@zaya.com'],
            ['name' => 'George Banks', 'first_name' => 'George', 'last_name' => 'Banks', 'email' => 'finance3@zaya.com'],
            ['name' => 'Penny Wise', 'first_name' => 'Penny', 'last_name' => 'Wise', 'email' => 'finance4@zaya.com'],
            ['name' => 'Richie Rich', 'first_name' => 'Richie', 'last_name' => 'Rich', 'email' => 'finance5@zaya.com'],
            ['name' => 'Alexander Hamilton', 'first_name' => 'Alexander', 'last_name' => 'Hamilton', 'email' => 'finance6@zaya.com'],
            ['name' => 'Catherine Cash', 'first_name' => 'Catherine', 'last_name' => 'Cash', 'email' => 'finance7@zaya.com'],
            ['name' => 'David Dollar', 'first_name' => 'David', 'last_name' => 'Dollar', 'email' => 'finance8@zaya.com'],
            ['name' => 'Elena Euro', 'first_name' => 'Elena', 'last_name' => 'Euro', 'email' => 'finance9@zaya.com'],
            ['name' => 'Gregory Gold', 'first_name' => 'Gregory', 'last_name' => 'Gold', 'email' => 'finance10@zaya.com'],
            ['name' => 'Hannah Hedge', 'first_name' => 'Hannah', 'last_name' => 'Hedge', 'email' => 'finance11@zaya.com'],
            ['name' => 'Isaac Investment', 'first_name' => 'Isaac', 'last_name' => 'Investment', 'email' => 'finance12@zaya.com'],
            ['name' => 'Julia Ledger', 'first_name' => 'Julia', 'last_name' => 'Ledger', 'email' => 'finance13@zaya.com'],
            ['name' => 'Kevin Krugerrand', 'first_name' => 'Kevin', 'last_name' => 'Krugerrand', 'email' => 'finance14@zaya.com'],
            ['name' => 'Laura Loan', 'first_name' => 'Laura', 'last_name' => 'Loan', 'email' => 'finance15@zaya.com'],
            ['name' => 'Michael Mint', 'first_name' => 'Michael', 'last_name' => 'Mint', 'email' => 'finance16@zaya.com'],
            ['name' => 'Nancy Nickel', 'first_name' => 'Nancy', 'last_name' => 'Nickel', 'email' => 'finance17@zaya.com'],
            ['name' => 'Oscar Ounce', 'first_name' => 'Oscar', 'last_name' => 'Ounce', 'email' => 'finance18@zaya.com'],
            ['name' => 'Peter Pound', 'first_name' => 'Peter', 'last_name' => 'Pound', 'email' => 'finance19@zaya.com'],
            ['name' => 'Quincy Quid', 'first_name' => 'Quincy', 'last_name' => 'Quid', 'email' => 'finance20@zaya.com'],
            ['name' => 'Rachel Revenue', 'first_name' => 'Rachel', 'last_name' => 'Revenue', 'email' => 'finance21@zaya.com'],
            ['name' => 'Samuel Stock', 'first_name' => 'Samuel', 'last_name' => 'Stock', 'email' => 'finance22@zaya.com'],
            ['name' => 'Tina Tax', 'first_name' => 'Tina', 'last_name' => 'Tax', 'email' => 'finance23@zaya.com'],
            ['name' => 'Ulysses Usury', 'first_name' => 'Ulysses', 'last_name' => 'Usury', 'email' => 'finance24@zaya.com'],
            ['name' => 'Victor Vault', 'first_name' => 'Victor', 'last_name' => 'Vault', 'email' => 'finance25@zaya.com'],
            ['name' => 'Wendy Wallet', 'first_name' => 'Wendy', 'last_name' => 'Wallet', 'email' => 'finance26@zaya.com'],
            ['name' => 'Xavier Xetra', 'first_name' => 'Xavier', 'last_name' => 'Xetra', 'email' => 'finance27@zaya.com'],
            ['name' => 'Yolanda Yen', 'first_name' => 'Yolanda', 'last_name' => 'Yen', 'email' => 'finance28@zaya.com'],
            ['name' => 'Zack Zero', 'first_name' => 'Zack', 'last_name' => 'Zero', 'email' => 'finance29@zaya.com'],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                array_merge($manager, [
                    'password' => Hash::make('password'),
                    'role' => 'financial-manager',
                    'status' => 'active',
                    'phone' => '1122334455'
                ])
            );
        }
    }
}

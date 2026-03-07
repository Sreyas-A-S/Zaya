<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class FinanceManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            User::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'national_id' => [rand(1,5)],
                'languages' => [rand(1,3)],
                'role' => 'finance_manager',
                'status' => 'active',
                'phone' => '1234567890'
            ]);

        }

    }
}

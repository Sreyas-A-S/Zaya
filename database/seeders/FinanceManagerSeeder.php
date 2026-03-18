<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Language;
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
        $countryIds = Country::query()->pluck('id')->all();
        $languageIds = Language::query()->pluck('id')->all();

        for ($i = 0; $i < 20; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            User::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'national_id' => !empty($countryIds) ? [$faker->randomElement($countryIds)] : [],
                'languages' => !empty($languageIds) ? [$faker->randomElement($languageIds)] : [],
                'role' => 'financial-manager',
                'phone' => $faker->numerify('##########'),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Sample Client 1
        $user1 = User::updateOrCreate(
            ['email' => 'client@zaya.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Client',
                'name' => 'John Client',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        Patient::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'client_id' => 'CL-' . strtoupper(Str::random(6)),
                'phone' => '9876543210',
                'mobile_country_code' => '+91',
                'dob' => '1990-01-01',
                'age' => 34,
                'gender' => 'Male',
                'occupation' => 'Software Engineer',
                'address_line_1' => '123 Main St',
                'address_line_2' => 'Tech City',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'zip_code' => '560001',
                'country' => 'India',
                'consultation_preferences' => ['General Consultation', 'Diet & Nutrition'],
                'languages_spoken' => ['English', 'Hindi'],
                'referral_type' => 'Social Media',
                'referrer_name' => 'Instagram',
                'profile_photo_path' => null,
                'status' => 'active',
            ]
        );

        // Sample Client 2
        $user2 = User::updateOrCreate(
            ['email' => 'jane@zaya.com'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        Patient::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'client_id' => 'CL-' . strtoupper(Str::random(6)),
                'phone' => '8765432109',
                'mobile_country_code' => '+1',
                'dob' => '1985-05-15',
                'age' => 39,
                'gender' => 'Female',
                'occupation' => 'Teacher',
                'address_line_1' => '456 Oak Avenue',
                'address_line_2' => 'Education Town',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'zip_code' => '411001',
                'country' => 'India',
                'consultation_preferences' => ['Stress Management', 'Women\'s Health'],
                'languages_spoken' => ['English', 'Spanish'],
                'referral_type' => 'Friend or Family',
                'referrer_name' => 'Sarah Connor',
                'profile_photo_path' => null,
                'status' => 'active',
            ]
        );

        // Loop for more random clients
        for ($i = 0; $i < 5; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->unique()->safeEmail;

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'client',
            ]);

            $dob = $faker->date('Y-m-d', '2000-01-01');
            $age = Carbon::parse($dob)->age;

            Patient::create([
                'user_id' => $user->id,
                'client_id' => 'CL-' . strtoupper(Str::random(6)),
                'phone' => $faker->numerify('##########'),
                'mobile_country_code' => '+91',
                'dob' => $dob,
                'age' => $age,
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'occupation' => $faker->jobTitle,
                'address_line_1' => $faker->streetAddress,
                'address_line_2' => $faker->streetName,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->postcode,
                'country' => 'India',
                'consultation_preferences' => $faker->randomElements(['General Consultation', 'Diet & Nutrition', 'Stress Management', 'Mental Health'], 2),
                'languages_spoken' => $faker->randomElements(['English', 'Hindi', 'Marathi'], 2),
                'referral_type' => $faker->randomElement(['Social Media', 'Website', 'Friend or Family']),
                'referrer_name' => $faker->name,
                'profile_photo_path' => null,
                'status' => 'active',
            ]);
        }
    }
}

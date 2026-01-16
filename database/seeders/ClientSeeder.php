<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Sample Client 1
        $user1 = \App\Models\User::create([
            'name' => 'John Client',
            'email' => 'client@zaya.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'client',
        ]);

        \App\Models\Patient::create([
            'user_id' => $user1->id,
            'client_id' => 'CL-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'phone' => '9876543210',
            'mobile_country_code' => '+91',
            'dob' => '1990-01-01',
            'age' => 34,
            'gender' => 'Male',
            'occupation' => 'Software Engineer',
            'address' => '123 Main St, Tech City',
            'consultation_preferences' => ['General Consultation', 'Diet & Nutrition'],
            'languages_spoken' => ['English', 'Hindi'],
            'referral_type' => 'Social Media',
            'referrer_name' => 'Instagram',
            'profile_photo_path' => null,
        ]);

        // Sample Client 2
        $user2 = \App\Models\User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@zaya.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'client',
        ]);

        \App\Models\Patient::create([
            'user_id' => $user2->id,
            'client_id' => 'CL-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'phone' => '8765432109',
            'mobile_country_code' => '+1',
            'dob' => '1985-05-15',
            'age' => 39,
            'gender' => 'Female',
            'occupation' => 'Teacher',
            'address' => '456 Oak Avenue, Education Town',
            'consultation_preferences' => ['Stress Management', 'Women\'s Health'],
            'languages_spoken' => ['English', 'Spanish'],
            'referral_type' => 'Friend or Family',
            'referrer_name' => 'Sarah Connor',
            'profile_photo_path' => null,
        ]);

        // Loop for more random clients
        for ($i = 0; $i < 5; $i++) {
            $user = \App\Models\User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'client',
            ]);

            $dob = $faker->date('Y-m-d', '2000-01-01');
            $age = \Carbon\Carbon::parse($dob)->age;

            \App\Models\Patient::create([
                'user_id' => $user->id,
                'client_id' => 'CL-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'phone' => $faker->numerify('##########'),
                'mobile_country_code' => '+91',
                'dob' => $dob,
                'age' => $age,
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'occupation' => $faker->jobTitle,
                'address' => $faker->address,
                'consultation_preferences' => $faker->randomElements(['General Consultation', 'Diet & Nutrition', 'Stress Management', 'Mental Health'], 2),
                'languages_spoken' => $faker->randomElements(['English', 'Hindi', 'Marathi'], 2),
                'referral_type' => $faker->randomElement(['Social Media', 'Website', 'Friend or Family']),
                'referrer_name' => $faker->name,
                'profile_photo_path' => null,
            ]);
        }
    }
}

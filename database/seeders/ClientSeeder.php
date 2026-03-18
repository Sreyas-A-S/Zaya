<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            ['country' => 'India', 'code' => '+91', 'lang' => ['Hindi', 'English']],
            ['country' => 'United Kingdom', 'code' => '+44', 'lang' => ['English']],
            ['country' => 'United States', 'code' => '+1', 'lang' => ['English', 'Spanish']],
            ['country' => 'France', 'code' => '+33', 'lang' => ['French', 'English']],
            ['country' => 'Germany', 'code' => '+49', 'lang' => ['German', 'English']],
            ['country' => 'United Arab Emirates', 'code' => '+971', 'lang' => ['Arabic', 'English']],
            ['country' => 'Australia', 'code' => '+61', 'lang' => ['English']],
            ['country' => 'Canada', 'code' => '+1', 'lang' => ['English', 'French']],
            ['country' => 'Singapore', 'code' => '+65', 'lang' => ['English', 'Mandarin']],
            ['country' => 'Netherlands', 'code' => '+31', 'lang' => ['Dutch', 'English']],
        ];

        $occupations = ['Software Engineer', 'Teacher', 'Architect', 'Doctor', 'Entrepreneur', 'Designer', 'Student', 'Artist', 'Lawyer', 'Manager'];
        $genders = ['male', 'female'];
        $referralTypes = ['Social Media', 'Friend/Family', 'Google Search', 'Advertisement', 'Healthcare Professional'];

        for ($i = 1; $i <= 30; $i++) {
            $index = ($i - 1) % count($nationalities);
            $nat = $nationalities[$index];
            $gender = $genders[($i - 1) % 2];
            $firstName = $gender === 'male' ? 'John' . $i : 'Jane' . $i;
            $lastName = 'Doe' . $i;
            $email = 'client' . $i . '@zaya.com';
            $clientId = 'CL-' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $dob = Carbon::create(1980 + ($i % 25), ($i % 12) + 1, ($i % 28) + 1);
            $age = $dob->age;

            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'patient',
                'gender' => $gender,
                'phone' => $nat['code'] . '9876543' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            Patient::create([
                'user_id' => $user->id,
                'client_id' => $clientId,
                'dob' => $dob->toDateString(),
                'age' => $age,
                'gender' => $gender,
                'occupation' => $occupations[($i - 1) % count($occupations)],
                'phone' => $user->phone,
                'mobile_country_code' => $nat['code'],
                'address_line_1' => $i . ' Main Street',
                'address_line_2' => 'Apartment ' . $i,
                'city' => 'City' . $i,
                'state' => 'State' . $i,
                'zip_code' => '100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'country' => $nat['country'],
                'consultation_preferences' => ['Video', 'Audio'],
                'languages_spoken' => $nat['lang'],
                'referral_type' => $referralTypes[($i - 1) % count($referralTypes)],
                'referrer_name' => 'Referrer ' . $i,
                'status' => 'active',
            ]);
        }
    }
}

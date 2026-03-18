<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\YogaTherapist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class YogaTherapistSeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = ['India', 'USA', 'UK', 'Germany', 'France', 'UAE', 'Canada', 'Australia', 'Japan', 'Singapore'];
        $cities = ['Mumbai', 'New York', 'London', 'Berlin', 'Paris', 'Dubai', 'Toronto', 'Sydney', 'Tokyo', 'Singapore'];
        $languages = ['Hindi', 'English', 'English', 'German', 'French', 'Arabic', 'English', 'English', 'Japanese', 'Mandarin'];

        for ($i = 1; $i <= 30; $i++) {
            $index = ($i - 1) % 10;
            $country = $nationalities[$index];
            $city = $cities[$index];
            $lang = $languages[$index];

            $firstName = "Yoga";
            $lastName = "Therapist" . $i;
            $fullName = $firstName . " " . $lastName;

            $user = User::updateOrCreate(
                ['email' => "yoga{$i}@zaya.com"],
                [
                    'name' => $fullName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make('password'),
                    'role' => 'yoga_therapist',
                    'gender' => ($i % 2 == 0) ? 'Female' : 'Male',
                    'phone' => '+91987654' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );

            YogaTherapist::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'dob' => '1985-06-' . str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT),
                    'address_line_1' => ($i * 5) . ' Yoga Lane',
                    'address_line_2' => 'Apartment ' . $i,
                    'city' => $city,
                    'state' => $city . ' Province',
                    'zip_code' => '200' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'country' => $country,
                    'yoga_therapist_type' => 'Certified Yoga Therapist',
                    'years_of_experience' => ($i % 20) + 3,
                    'current_organization' => 'Yoga Wellness Center ' . $city,
                    'workplace_address' => ($i * 5) . ' Yoga Lane, ' . $city,
                    'website_social_links' => ['instagram' => 'https://instagram.com/yoga' . $i, 'facebook' => 'https://facebook.com/yoga' . $i],
                    'certification_details' => 'IAYT Certified Yoga Therapist',
                    'additional_certifications' => 'Advanced Pranayama Certification',
                    'registration_number' => 'YT-' . (1000 + $i),
                    'affiliated_body' => 'International Association of Yoga Therapists',
                    'areas_of_expertise' => ["Stress / Anxiety Management", "Back Pain / Spine Care"],
                    'consultation_modes' => ['Video', 'Audio'],
                    'languages_spoken' => [$lang, 'English'],
                    'short_bio' => 'Passionate yoga therapist focusing on holistic health and alignment.',
                    'therapy_approach' => 'Vinyasa flow integrated with therapeutic techniques.',
                    'gov_id_type' => 'Passport',
                    'pan_number' => 'FGHIJ' . (5678 + $i) . 'K',
                    'bank_holder_name' => $fullName,
                    'bank_name' => 'Global Bank ' . $country,
                    'account_number' => '1122334455' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'ifsc_code' => 'GLBK000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );
        }
    }
}

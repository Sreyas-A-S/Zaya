<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MindfulnessPractitioner;
use App\Models\MindfulnessService;
use App\Models\ClientConcern;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MindfulnessPractitionerSeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = ['India', 'USA', 'UK', 'Germany', 'France', 'UAE', 'Canada', 'Australia', 'Japan', 'Singapore'];
        $cities = ['Mumbai', 'New York', 'London', 'Berlin', 'Paris', 'Dubai', 'Toronto', 'Sydney', 'Tokyo', 'Singapore'];
        $languages = ['Hindi', 'English', 'English', 'German', 'French', 'Arabic', 'English', 'English', 'Japanese', 'Mandarin'];

        $services = MindfulnessService::pluck('name')->toArray() ?: ['Mindfulness Meditation', 'Stress Reduction', 'Emotional Intelligence'];
        $concerns = ClientConcern::pluck('name')->toArray() ?: ['Anxiety', 'Sleep Issues', 'Work Stress'];

        for ($i = 1; $i <= 30; $i++) {
            $index = ($i - 1) % 10;
            $country = $nationalities[$index];
            $city = $cities[$index];
            $lang = $languages[$index];

            $firstName = "Mindfulness";
            $lastName = "Expert" . $i;
            $fullName = $firstName . " " . $lastName;

            $user = User::updateOrCreate(
                ['email' => "mindfulness{$i}@zaya.com"],
                [
                    'name' => $fullName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make('password'),
                    'role' => 'mindfulness_practitioner',
                    'gender' => ($i % 2 == 0) ? 'Female' : 'Male',
                    'phone' => '+12345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );

            MindfulnessPractitioner::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'active',
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $user->gender,
                    'dob' => '1980-01-' . str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT),
                    'phone' => $user->phone,
                    'practitioner_type' => 'Mindfulness Coach',
                    'years_of_experience' => ($i % 15) + 5,
                    'current_workplace' => 'Mindfulness Center ' . $city,
                    'website_social_links' => ['website' => 'https://mindfulness' . $i . '.com', 'linkedin' => 'https://linkedin.com/in/mindfulness' . $i],
                    'highest_education' => 'Masters in Psychology',
                    'mindfulness_training_details' => 'Advanced MBSR Training Program',
                    'additional_certifications' => 'Yoga Teacher Training 200h',
                    'services_offered' => array_slice($services, 0, 3),
                    'client_concerns' => array_slice($concerns, 0, 2),
                    'consultation_modes' => ['Video', 'Audio'],
                    'languages_spoken' => [$lang, 'English'],
                    'gov_id_type' => 'Passport',
                    'pan_number' => 'ABCDE' . (1234 + $i) . 'F',
                    'bank_holder_name' => $fullName,
                    'bank_name' => 'International Bank of ' . $country,
                    'account_number' => '9876543210' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'ifsc_code' => 'IBNK000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'short_bio' => 'Experienced mindfulness practitioner dedicated to helping clients find inner peace.',
                    'coaching_style' => 'Empathetic and evidence-based approach.',
                    'target_audience' => 'Corporate professionals and students.',
                    'address_line_1' => ($i * 10) . ' Peace Avenue',
                    'address_line_2' => 'Suite ' . $i,
                    'city' => $city,
                    'state' => $city . ' State',
                    'zip_code' => '100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'country' => $country,
                ]
            );
        }
    }
}

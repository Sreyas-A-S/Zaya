<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Practitioner;
use App\Models\PractitionerQualification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            'Indian', 'British', 'American', 'French', 'German', 
            'Emirati', 'Australian', 'Canadian', 'Singaporean', 'Dutch'
        ];

        $countries = [
            'India', 'United Kingdom', 'United States', 'France', 'Germany', 
            'United Arab Emirates', 'Australia', 'Canada', 'Singapore', 'Netherlands'
        ];

        $genders = ['male', 'female'];
        $consultations = ['Ayurveda Consultation', 'Wellness Coaching', 'Life Coaching', 'Nutritional Counseling'];
        $bodyTherapies = ['Abhyanga', 'Shirodhara', 'Panchakarma', 'Deep Tissue Massage', 'Thai Massage'];
        $modalities = ['Sound Healing', 'Reiki', 'Crystal Healing', 'Pranic Healing', 'Aromatherapy'];
        $languages = ['English', 'Hindi', 'French', 'German', 'Spanish', 'Arabic'];

        for ($i = 1; $i <= 30; $i++) {
            $gender = $genders[($i - 1) % 2];
            $firstName = $gender === 'male' ? 'Practitioner' . $i : 'Prac' . $i;
            $lastName = 'User' . $i;
            $email = 'practitioner' . $i . '@zaya.com';
            $dob = Carbon::create(1975 + ($i % 20), ($i % 12) + 1, ($i % 28) + 1);
            $countryIndex = ($i - 1) % count($countries);

            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'practitioner',
                'gender' => $gender,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $practitioner = Practitioner::create([
                'user_id' => $user->id,
                'status' => 'approved',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $gender,
                'dob' => $dob->toDateString(),
                'nationality' => $nationalities[$countryIndex],
                'residential_address' => $i . ' Professional Blvd',
                'zip_code' => 'PRACT' . $i,
                'phone' => '+1234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'website_url' => 'https://practitioner' . $i . '.com',
                'consultations' => [$consultations[($i - 1) % count($consultations)]],
                'body_therapies' => [$bodyTherapies[($i - 1) % count($bodyTherapies)]],
                'other_modalities' => [$modalities[($i - 1) % count($modalities)]],
                'additional_courses' => 'Advanced certification in ' . $modalities[($i - 1) % count($modalities)],
                'languages_spoken' => [$languages[($i - 1) % count($languages)], 'English'],
                'can_translate_english' => true,
                'profile_bio' => 'Experienced practitioner specializing in holistic wellness and traditional therapies. Dedicated to providing personalized care for over ' . ($i % 15 + 5) . ' years.',
                'address_line_1' => $i . ' Professional Blvd',
                'address_line_2' => 'Suite ' . ($i + 100),
                'city' => 'City' . $i,
                'state' => 'State' . $i,
                'country' => $countries[$countryIndex],
                'status' => 'active',
            ]);

            // Add Qualifications
            PractitionerQualification::create([
                'practitioner_id' => $practitioner->id,
                'year_of_passing' => (2000 + ($i % 20)),
                'institute_name' => 'Global Wellness Institute ' . (($i % 5) + 1),
                'training_diploma_title' => 'Master of ' . $consultations[($i - 1) % count($consultations)],
                'training_duration_online_hours' => '200',
                'training_duration_contact_hours' => '300',
                'institute_postal_address' => '123 Education Way, City ' . $i,
                'institute_contact_details' => 'contact@wellnessedu' . (($i % 5) + 1) . '.com',
            ]);

            if ($i % 2 == 0) {
                PractitionerQualification::create([
                    'practitioner_id' => $practitioner->id,
                    'year_of_passing' => (2005 + ($i % 15)),
                    'institute_name' => 'Holistic Arts Academy ' . (($i % 3) + 1),
                    'training_diploma_title' => 'Specialization in ' . $bodyTherapies[($i - 1) % count($bodyTherapies)],
                    'training_duration_online_hours' => '100',
                    'training_duration_contact_hours' => '150',
                    'institute_postal_address' => '456 Healing St, City ' . $i,
                    'institute_contact_details' => 'info@holisticacademy.com',
                ]);
            }
        }
    }
}

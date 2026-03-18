<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = [
            ['country' => 'India', 'lang' => ['Hindi', 'English'], 'state' => 'Maharashtra', 'city' => 'Mumbai'],
            ['country' => 'USA', 'lang' => ['English'], 'state' => 'California', 'city' => 'Los Angeles'],
            ['country' => 'UK', 'lang' => ['English'], 'state' => 'London', 'city' => 'London'],
            ['country' => 'Germany', 'lang' => ['German', 'English'], 'state' => 'Bavaria', 'city' => 'Munich'],
            ['country' => 'France', 'lang' => ['French'], 'state' => 'Île-de-France', 'city' => 'Paris'],
            ['country' => 'UAE', 'lang' => ['Arabic', 'English'], 'state' => 'Dubai', 'city' => 'Dubai'],
            ['country' => 'Canada', 'lang' => ['English', 'French'], 'state' => 'Ontario', 'city' => 'Toronto'],
            ['country' => 'Australia', 'lang' => ['English'], 'state' => 'NSW', 'city' => 'Sydney'],
            ['country' => 'Japan', 'lang' => ['Japanese'], 'state' => 'Tokyo', 'city' => 'Tokyo'],
            ['country' => 'Singapore', 'lang' => ['English', 'Mandarin'], 'state' => 'Singapore', 'city' => 'Singapore'],
        ];

        for ($i = 1; $i <= 30; $i++) {
            $nat = $nationalities[($i - 1) % 10];
            $name = "Doctor " . $i;
            $email = "doctor{$i}@zaya.com";

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Dr. " . $name,
                    'first_name' => "Dr.",
                    'last_name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                    'status' => 'active',
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $name,
                    'gender' => $i % 2 == 0 ? 'Female' : 'Male',
                    'dob' => '1980-01-01',
                    'phone' => '+919876543' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'address_line_1' => "Clinic Address " . $i,
                    'city' => $nat['city'],
                    'state' => $nat['state'],
                    'zip_code' => '4000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'country' => $nat['country'],
                    'ayush_registration_number' => "AYU-" . (10000 + $i),
                    'state_ayurveda_council_name' => $nat['state'] . " Council",
                    'primary_qualification' => 'BAMS',
                    'post_graduation' => 'MD Ayurveda',
                    'specialization' => ['Kayachikitsa', 'Panchakarma'],
                    'years_of_experience' => 5 + ($i % 20),
                    'current_workplace_clinic_name' => "Zaya Wellness Center " . $nat['city'],
                    'consultation_expertise' => ['Prakriti Analysis', 'Ayurvedic Diet Planning'],
                    'health_conditions_treated' => ['Digestive Issues', 'Stress'],
                    'panchakarma_consultation' => true,
                    'panchakarma_procedures' => ['Vamana', 'Virechana'],
                    'external_therapies' => ['Abhyanga', 'Shirodhara'],
                    'consultation_modes' => ['Video', 'Chat'],
                    'languages_spoken' => $nat['lang'],
                    'status' => 'approved',
                    'ayush_registration_confirmed' => true,
                    'ayush_guidelines_agreed' => true,
                    'document_verification_consented' => true,
                    'policies_agreed' => true,
                    'prescription_understanding_agreed' => true,
                    'confidentiality_consented' => true,
                    'social_links' => ['website' => 'https://zaya.com'],
                ]
            );
        }
    }
}

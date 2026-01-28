<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YogaTherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Specific demo user
        if (!\App\Models\User::where('email', 'priya.yoga@example.com')->exists()) {
            $user = \App\Models\User::create([
                'name' => 'Priya Sharma',
                'email' => 'priya.yoga@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'yoga_therapist',
            ]);

            \App\Models\YogaTherapist::create([
                'user_id' => $user->id,
                'phone' => '9876543210',
                'gender' => 'female',
                'dob' => '1988-07-15',
                'address' => '123, Wellness Road, Bangalore, Karnataka',
                'profile_photo_path' => null,

                'yoga_therapist_type' => 'Certified Yoga Therapist',
                'years_of_experience' => 12,
                'current_organization' => 'Prana Wellness Center',
                'workplace_address' => '45, Indiranagar, Bangalore',
                'website_social_links' => [
                    'website' => 'https://www.priyayoga.com',
                    'instagram' => 'https://instagram.com/priya_yoga',
                    'linkedin' => 'https://linkedin.com/in/priya_yoga',
                    'youtube' => 'https://youtube.com/@priya_yoga',
                ],

                'certification_details' => 'IAYT Certified, MSc into Yoga Therapy from SVYASA.',
                'certificates_path' => ['sample_cert_1.pdf'],
                'additional_certifications' => 'Prenatal Yoga Specialist',

                'registration_number' => 'IAYT-2023-889',
                'affiliated_body' => 'International Association of Yoga Therapists',
                'registration_proof_path' => 'reg_proof.pdf',

                'areas_of_expertise' => [
                    "Stress / Anxiety Management",
                    "Back Pain / Spine Care",
                    "Women’s Health (PCOS, Menstrual Health)",
                    "Prenatal / Postnatal Yoga Therapy"
                ],
                'consultation_modes' => ["Video", "Group Sessions"],
                'languages_spoken' => ["English", "Hindi", "Kannada"],

                'short_bio' => 'Dedicated Yoga Therapist with over a decade of experience.',
                'therapy_approach' => 'Holistic approach combining Asanas, Pranayama, and Meditation.',

                'gov_id_type' => 'Aadhaar Card',
                'gov_id_upload_path' => 'aadhaar.jpg',
                'pan_number' => 'ABCDE1234F',
                'bank_holder_name' => 'Priya Sharma',
                'bank_name' => 'HDFC Bank',
                'account_number' => '50100123456789',
                'ifsc_code' => 'HDFC0000123',
                'upi_id' => 'priya@hdfc',
                'cancelled_cheque_path' => 'cheque.jpg',
                'status' => 'active',
            ]);
        }

        // Generate 20 random users
        for ($i = 0; $i < 20; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = strtolower($firstName . '.' . $lastName . $faker->randomNumber(3) . '@example.com');

            $user = \App\Models\User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'yoga_therapist',
            ]);

            \App\Models\YogaTherapist::create([
                'user_id' => $user->id,
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'dob' => $faker->date('Y-m-d', '2000-01-01'),
                'address' => $faker->address,
                'profile_photo_path' => null,

                'yoga_therapist_type' => $faker->randomElement(['Certified Yoga Therapist', 'Yoga Instructor', 'Ayurvedic Yoga Therapist']),
                'years_of_experience' => $faker->numberBetween(1, 25),
                'current_organization' => $faker->company,
                'workplace_address' => $faker->address,
                'website_social_links' => [
                    'website' => $faker->url,
                    'instagram' => 'https://instagram.com/' . $firstName,
                    'linkedin' => 'https://linkedin.com/in/' . $firstName,
                    'youtube' => 'https://youtube.com/@' . $firstName,
                ],

                'certification_details' => $faker->sentence(6),
                'certificates_path' => null,
                'additional_certifications' => $faker->sentence(3),

                'registration_number' => strtoupper($faker->bothify('REG-##-??')),
                'affiliated_body' => $faker->company,
                'registration_proof_path' => null,

                'areas_of_expertise' => $faker->randomElements([
                    "Stress / Anxiety Management",
                    "Sleep Improvement",
                    "Weight Management",
                    "Back Pain / Spine Care",
                    "Women’s Health"
                ], $faker->numberBetween(1, 4)),
                'consultation_modes' => $faker->randomElements(["Video", "Audio", "Chat"], $faker->numberBetween(1, 2)),
                'languages_spoken' => $faker->randomElements(["English", "Hindi", "Spanish", "French"], $faker->numberBetween(1, 3)),

                'short_bio' => $faker->paragraph(2),
                'therapy_approach' => $faker->sentence(10),

                'gov_id_type' => $faker->randomElement(['Passport', 'Aadhaar', 'License']),
                'gov_id_upload_path' => null,
                'pan_number' => strtoupper($faker->bothify('?????####?')),
                'bank_holder_name' => $user->name,
                'bank_name' => $faker->company . ' Bank',
                'account_number' => $faker->bankAccountNumber,
                'ifsc_code' => strtoupper($faker->bothify('????0######')),
                'upi_id' => $faker->userName . '@upi',
                'cancelled_cheque_path' => null,

                'status' => $faker->randomElement(['active', 'pending']),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Practitioner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $consultationsList = ['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'];
        $therapiesList = ['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy'];
        $modalitiesList = ['Yoga Sessions', 'Yoga Therapy', 'Ayurvedic Cooking'];

        foreach (range(1, 10) as $index) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'practitioner',
                'email_verified_at' => now(),
            ]);

            $practitioner = Practitioner::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $faker->date('Y-m-d', '-25 years'),
                'nationality' => 'Indian',
                'residential_address' => $faker->address(),
                'zip_code' => $faker->postcode(),
                'phone' => $faker->phoneNumber(),
                'social_links' => [
                    'website' => $faker->url(),
                    'instagram' => 'https://instagram.com/' . $firstName,
                    'linkedin' => 'https://linkedin.com/in/' . $firstName,
                    'youtube' => 'https://youtube.com/@' . $firstName,
                ],
                'consultations' => $faker->randomElements($consultationsList, 2),
                'body_therapies' => $faker->randomElements($therapiesList, 3),
                'other_modalities' => $faker->randomElements($modalitiesList, 1),
                'languages_spoken' => $faker->randomElements(['English', 'Hindi', 'Marathi'], 2),
                'can_translate_english' => $faker->boolean(),
                'profile_bio' => $faker->paragraph(),
            ]);

            // Add some qualifications
            $practitioner->qualifications()->create([
                'year_of_passing' => $faker->year(),
                'institute_name' => $faker->company() . ' Institute',
                'training_diploma_title' => 'Diploma in Ayurvedic ' . $faker->word(),
                'training_duration_online_hours' => $faker->numberBetween(20, 100),
                'training_duration_contact_hours' => $faker->numberBetween(50, 200),
                'institute_postal_address' => $faker->address(),
                'institute_contact_details' => $faker->phoneNumber(),
            ]);
        }
    }
}

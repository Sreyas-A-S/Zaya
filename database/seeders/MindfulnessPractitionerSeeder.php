<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MindfulnessPractitioner;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MindfulnessPractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $services = [
            "Guided Meditation",
            "Mindfulness Coaching",
            "Group Meditation Sessions",
            "Breathwork Sessions",
            "Stress & Anxiety Management Program",
            "Sleep Improvement Sessions",
            "Emotional Regulation Coaching",
            "Trauma-Informed Mindfulness",
            "Corporate Wellness",
            "Mindful Parenting",
            "Teen Mindfulness",
            "Sound Healing",
            "Mantra Meditation"
        ];

        foreach ($services as $service) {
            \App\Models\MindfulnessService::firstOrCreate(['name' => $service], ['status' => 1]);
        }

        $concerns = [
            "Stress",
            "Anxiety",
            "Sleep issues",
            "Burnout",
            "Low confidence",
            "Emotional imbalance",
            "Focus & productivity",
            "Lifestyle discipline",
            "Relationships",
            "Grief support",
            "Other"
        ];

        foreach ($concerns as $concern) {
            \App\Models\ClientConcern::firstOrCreate(['name' => $concern], ['status' => 1]);
        }

        $languages = ["English", "Hindi", "Malayalam"];

        for ($i = 0; $i < 10; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->unique()->safeEmail;

            $user = User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'mindfulness_practitioner',
            ]);

            MindfulnessPractitioner::create([
                'user_id' => $user->id,
                'status' => $faker->randomElement(['active', 'pending']),
                // 'full_name' removed as per new schema, handled by User
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $faker->date(),
                'phone' => $faker->mobileNumber,
                'address' => $faker->address,
                // 'profile_photo_path' => null, // Left empty or add a placeholder path if needed

                'practitioner_type' => 'Mindfulness Coach',
                'years_of_experience' => $faker->numberBetween(1, 15),
                'current_workplace' => $faker->company,
                'website_social_links' => [
                    'website' => $faker->url,
                    'instagram' => 'https://instagram.com/' . $firstName,
                    'linkedin' => 'https://linkedin.com/in/' . $firstName,
                    'youtube' => 'https://youtube.com/@' . $firstName,
                ],

                'highest_education' => 'Masters in Psychology',
                'mindfulness_training_details' => 'Certified Mindfulness Teacher Training, 200 Hours',
                'additional_certifications' => 'Trauma Informed Care Certificate',

                'services_offered' => $faker->randomElements($services, 3),
                'client_concerns' => $faker->randomElements($concerns, 2),
                'consultation_modes' => ['Video', 'Audio'],
                'languages_spoken' => $faker->randomElements($languages, 2),

                'gov_id_type' => 'Aadhaar',
                'pan_number' => strtoupper($faker->bothify('?????####?')),
                'bank_holder_name' => "$firstName $lastName",
                'bank_name' => 'HDFC Bank',
                'account_number' => $faker->bankAccountNumber,
                'ifsc_code' => 'HDFC0001234',

                'short_bio' => $faker->paragraph,
                'coaching_style' => 'Compassionate and evidence-based approach.',
                'target_audience' => 'Corporate professionals and students.',
            ]);
        }
    }
}

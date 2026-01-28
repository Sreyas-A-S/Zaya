<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('en_IN');

        $services = [
            "Document Translation",
            "Website / App Localization",
            "Live Interpretation (Voice / Video)",
            "Subtitles / Captions",
            "Transcription + Translation",
            "Proofreading / Editing",
            "Voice-over Script Translation",
            "Other"
        ];

        foreach ($services as $service) {
            \App\Models\TranslatorService::firstOrCreate(['name' => $service], ['status' => 1]);
        }

        $specializations = [
            "Medical",
            "Legal",
            "Technical",
            "Financial",
            "Literary",
            "Marketing",
            "Scientific",
            "General"
        ];

        foreach ($specializations as $spec) {
            \App\Models\TranslatorSpecialization::firstOrCreate(['name' => $spec], ['status' => 1]);
        }

        $languages = ["English", "Hindi", "Malayalam", "Tamil", "French", "German", "Spanish"];

        // Seed 5 Translators
        for ($i = 0; $i < 5; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->unique()->safeEmail;

            $user = \App\Models\User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'translator',
            ]);

            \App\Models\Translator::create([
                'user_id' => $user->id,
                'status' => $faker->randomElement(['active', 'pending']),
                'full_name' => "$firstName $lastName",
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $faker->date(),
                'phone' => $faker->mobileNumber,
                'address_line_1' => $faker->streetAddress,
                'address_line_2' => $faker->streetName,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->postcode,
                'country' => 'India',
                'native_language' => $faker->randomElement($languages),
                'source_languages' => $faker->randomElements($languages, 2),
                'target_languages' => $faker->randomElements($languages, 2),
                'additional_languages' => $faker->randomElements($languages, 1),
                'translator_type' => $faker->randomElement(['Freelance', 'Agency']),
                'years_of_experience' => $faker->numberBetween(1, 20),
                'fields_of_specialization' => $faker->randomElements($specializations, 2),
                'previous_clients_projects' => $faker->sentence,
                'portfolio_link' => $faker->url,
                'highest_education' => 'Bachelor of Arts in Linguistics',
                'certification_details' => 'Certified Translator',
                'services_offered' => $faker->randomElements($services, 3),
                'gov_id_type' => 'Aadhaar',
                'pan_number' => strtoupper($faker->bothify('?????####?')),
                'bank_holder_name' => "$firstName $lastName",
                'bank_name' => 'SBI',
                'account_number' => $faker->bankAccountNumber,
                'ifsc_code' => 'SBIN0001234',
            ]);
        }
    }
}

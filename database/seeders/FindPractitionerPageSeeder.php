<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Practitioner;
use App\Models\HomepageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FindPractitionerPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Seed Page Settings for "Find Practitioner"
        $settings = [
            [
                'key' => 'find_practitioner_title',
                'value' => 'Experts in Your Neighborhood',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_subtitle',
                'value' => 'Verified practitioners ready to support your journey',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 150
            ],
            [
                'key' => 'find_practitioner_description',
                'value' => "Find the support you need, right in your community. Every practitioner listed here is part of ZAYA's practitioner-led network, committed to ethical care and holistic healing.",
                'type' => 'textarea',
                'section' => 'find_practitioner_page',
                'max_length' => 500
            ],
            [
                'key' => 'find_practitioner_search_placeholder',
                'value' => 'Practitioners, Treatments...',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_pincode_placeholder',
                'value' => 'Enter Pincode',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_service_placeholder',
                'value' => 'Select Service',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_mode_placeholder',
                'value' => 'Select Mode',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 50
            ],
            [
                'key' => 'find_practitioner_results_heading',
                'value' => 'Search Results Based on',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_all_label',
                'value' => 'All Practitioners',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_no_results',
                'value' => 'No practitioners found',
                'type' => 'text',
                'section' => 'find_practitioner_page',
                'max_length' => 100
            ],
            [
                'key' => 'find_practitioner_no_results_sub',
                'value' => 'Try adjusting your filters or searching in a different area.',
                'type' => 'textarea',
                'section' => 'find_practitioner_page',
                'max_length' => 200
            ],
            [
                'key' => 'find_practitioner_service_ayurveda',
                'value' => 'Ayurveda & Panchakarma',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_mindfulness',
                'value' => 'Mindfulness Practitioner',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_yoga',
                'value' => 'Yoga Therapy',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_art',
                'value' => 'Art Therapy',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_clinical',
                'value' => 'Clinical Psychology',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_sound',
                'value' => 'Sound Therapy',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_service_hypno',
                'value' => 'Hypnotherapy',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_mode_online',
                'value' => 'Online',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
            [
                'key' => 'find_practitioner_mode_offline',
                'value' => 'In-person',
                'type' => 'text',
                'section' => 'find_practitioner_page'
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key'], 'language' => 'en'],
                $setting
            );
        }

        // 2. Seed High-Quality Practitioner Results
        $practitionersData = [
            [
                'first_name' => 'Aditi',
                'last_name' => 'Sharma',
                'modalities' => ['Ayurveda & Panchakarma'],
                'city' => 'Kochi',
                'state' => 'Kerala',
                'zip' => '682001',
                'bio' => 'Passionate about traditional Ayurvedic healing with over 12 years of experience in Panchakarma and herbal medicine.',
            ],
            [
                'first_name' => 'Rohan',
                'last_name' => 'Verma',
                'modalities' => ['Yoga Therapy'],
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'zip' => '400001',
                'bio' => 'Specializing in therapeutic yoga for chronic pain management and stress relief through mindful movement.',
            ],
            [
                'first_name' => 'Sneha',
                'last_name' => 'Kapoor',
                'modalities' => ['Mindfulness Practitioner'],
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'zip' => '560001',
                'bio' => 'Helping individuals find inner peace and emotional balance through integrated mindfulness and meditation techniques.',
            ],
            [
                'first_name' => 'Arjun',
                'last_name' => 'Nair',
                'modalities' => ['Sound Therapy'],
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'zip' => '411001',
                'bio' => 'Ancient sound healing techniques using Tibetan bowls and frequencies to promote cellular regeneration and deep relaxation.',
            ],
            [
                'first_name' => 'Meera',
                'last_name' => 'Iyer',
                'modalities' => ['Ayurvedic Dietetics'],
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'zip' => '600001',
                'bio' => 'Nutrition expert focusing on Prakriti-based diet plans to enhance vitality and gut health.',
            ],
            [
                'first_name' => 'Vikram',
                'last_name' => 'Singh',
                'modalities' => ['Clinical Psychology'],
                'city' => 'Delhi',
                'state' => 'Delhi',
                'zip' => '110001',
                'bio' => 'Experienced clinical psychologist combining modern cognitive therapies with holistic mindfulness approaches.',
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Desai',
                'modalities' => ['Art Therapy'],
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'zip' => '380001',
                'bio' => 'Using creative expression as a tool for healing trauma and discovering self-awareness.',
            ],
            [
                'first_name' => 'Rahul',
                'last_name' => 'Gupta',
                'modalities' => ['Hypnotherapy'],
                'city' => 'Kolkata',
                'state' => 'West Bengal',
                'zip' => '700001',
                'bio' => 'Certified hypnotherapist helping clients overcome phobias and addictive patterns through subconscious reprogramming.',
            ],
        ];

        foreach ($practitionersData as $data) {
            $email = strtolower($data['first_name'] . '.' . $data['last_name'] . '@zaya-example.com');
            
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'practitioner',
                    'email_verified_at' => now(),
                    'phone' => $faker->phoneNumber(),
                ]
            );

            Practitioner::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $faker->randomElement(['male', 'female']),
                    'dob' => '1985-05-15',
                    'nationality' => 'Indian',
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => 'India',
                    'zip_code' => $data['zip'],
                    'profile_bio' => $data['bio'],
                    'other_modalities' => $data['modalities'],
                    'consultations' => [$data['modalities'][0]],
                    'status' => 'active',
                ]
            );
        }
    }
}

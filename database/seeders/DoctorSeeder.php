<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_IN');

        $specializationsList = ['Kayachikitsa', 'Panchakarma', 'Shalya Tantra', 'Shalakya Tantra', 'Prasuti & Stri Roga', 'Kaumarabhritya', 'Agada Tantra', 'Swasthavritta', 'Yoga'];
        $expertiseList = ['Prakriti Analysis', 'Vikriti Analysis', 'Samprapti Writing', 'Dosha Imbalance Correction Plans', 'Agni / Ama Assessment', 'Lifestyle & Dinacharya Planning', 'Ayurvedic Diet Planning'];
        $conditionsList = ['Digestive Issues', 'Skin Issues', 'Joint Pains', 'PCOS / Menstrual Disorders', 'Thyroid Management Support', 'Diabetes / Metabolic Disorder Support', 'Stress / Anxiety / Sleep Issues', 'Weight Management', 'Hair Fall / Dandruff', 'Respiratory Issues', 'Sexual Wellness / Infertility Support', 'Chronic Disease Management'];
        $proceduresList = ['Vamana', 'Virechana', 'Basti', 'Nasya', 'Raktamokshana'];
        $therapiesList = ['Abhyanga', 'Shirodhara', 'Swedana', 'Udwartana', 'Pinda Sweda', 'Kati / Janu / Greeva Basti'];
        $modesList = ['Video', 'Audio', 'Chat'];
        $languagesList = ['English', 'Hindi', 'Marathi', 'Gujarati', 'Tamil', 'Kannada'];

        foreach (range(1, 20) as $index) {
            $name = $faker->name();
            $user = User::create([
                'name' => 'Dr. ' . $name,
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'doctor',
                'email_verified_at' => now(),
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'full_name' => $name,
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $faker->date('Y-m-d', '-30 years'),
                'phone' => $faker->phoneNumber(),
                'city_state' => $faker->city() . ', ' . $faker->state(),
                
                // Medical Registration
                'ayush_registration_number' => strtoupper($faker->bothify('AYU-#####-??')),
                'state_ayurveda_council_name' => $faker->state() . ' Council of Indian Medicine',
                
                // Qualifications
                'primary_qualification' => 'BAMS',
                'post_graduation' => $faker->randomElement(['MD Ayurveda', 'MS Ayurveda', null]),
                'specialization' => $faker->randomElements($specializationsList, $faker->numberBetween(1, 3)),
                'years_of_experience' => $faker->numberBetween(2, 25),
                'current_workplace_clinic_name' => $faker->company() . ' Ayurvedic Clinic',
                'clinic_address' => $faker->address(),
                
                // Expertise
                'consultation_expertise' => $faker->randomElements($expertiseList, $faker->numberBetween(3, 5)),
                'health_conditions_treated' => $faker->randomElements($conditionsList, $faker->numberBetween(3, 6)),
                'panchakarma_consultation' => $faker->boolean(80),
                'panchakarma_procedures' => $faker->randomElements($proceduresList, $faker->numberBetween(2, 4)),
                'external_therapies' => $faker->randomElements($therapiesList, $faker->numberBetween(2, 4)),
                
                // Setup
                'consultation_modes' => $faker->randomElements($modesList, $faker->numberBetween(1, 3)),
                'languages_spoken' => $faker->randomElements($languagesList, $faker->numberBetween(1, 3)),
                
                // KYC
                'pan_number' => strtoupper($faker->bothify('?????####?')),
                'bank_account_holder_name' => $name,
                'bank_name' => $faker->company() . ' Bank',
                'account_number' => $faker->bankAccountNumber(),
                'ifsc_code' => strtoupper($faker->bothify('????0######')),
                'upi_id' => strtolower(Str::slug($name)) . '@okicici',
                
                // Profile
                'short_doctor_bio' => $faker->paragraph(2),
                'key_expertise' => $faker->sentence(10),
                'services_offered' => $faker->sentence(15),
                'awards_recognitions' => $faker->boolean(30) ? 'Best Practitioner Award ' . $faker->year() : null,
                'social_links' => [
                    'website' => $faker->url(),
                    'instagram' => 'https://instagram.com/' . Str::slug($name),
                    'linkedin' => 'https://linkedin.com/in/' . Str::slug($name),
                ],
                
                // Status & Consents
                'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
                'ayush_registration_confirmed' => true,
                'ayush_guidelines_agreed' => true,
                'document_verification_consented' => true,
                'policies_agreed' => true,
                'prescription_understanding_agreed' => true,
                'confidentiality_consented' => true,
            ]);
        }
    }
}
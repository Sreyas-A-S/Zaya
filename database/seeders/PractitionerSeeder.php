<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Practitioner;
use Illuminate\Support\Facades\Hash;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $practitioners = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'practitioner1@zaya.com', 'dob' => '1980-01-01', 'gender' => 'Male', 'nationality' => 'USA', 'languages' => ['English'], 'phone' => '+12025550199'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'practitioner2@zaya.com', 'dob' => '1982-05-12', 'gender' => 'Female', 'nationality' => 'Canada', 'languages' => ['English', 'French'], 'phone' => '+14165550198'],
            ['first_name' => 'Robert', 'last_name' => 'Brown', 'email' => 'practitioner3@zaya.com', 'dob' => '1975-09-20', 'gender' => 'Male', 'nationality' => 'UK', 'languages' => ['English'], 'phone' => '+442079460197'],
            ['first_name' => 'Ana', 'last_name' => 'Garcia', 'email' => 'practitioner4@zaya.com', 'dob' => '1988-03-04', 'gender' => 'Female', 'nationality' => 'Mexico', 'languages' => ['Spanish'], 'phone' => '+525555550196'],
            ['first_name' => 'Carlos', 'last_name' => 'Martinez', 'email' => 'practitioner5@zaya.com', 'dob' => '1985-07-19', 'gender' => 'Male', 'nationality' => 'Spain', 'languages' => ['Spanish'], 'phone' => '+34912345695'],
            ['first_name' => 'Hans', 'last_name' => 'Müller', 'email' => 'practitioner6@zaya.com', 'dob' => '1970-11-30', 'gender' => 'Male', 'nationality' => 'Germany', 'languages' => ['German'], 'phone' => '+493012345694'],
            ['first_name' => 'Marie', 'last_name' => 'Dubois', 'email' => 'practitioner7@zaya.com', 'dob' => '1992-02-14', 'gender' => 'Female', 'nationality' => 'France', 'languages' => ['French'], 'phone' => '+33123456793'],
            ['first_name' => 'Ken', 'last_name' => 'Sato', 'email' => 'practitioner8@zaya.com', 'dob' => '1979-06-25', 'gender' => 'Male', 'nationality' => 'Japan', 'languages' => ['Japanese'], 'phone' => '+81312345692'],
            ['first_name' => 'Giulia', 'last_name' => 'Rossi', 'email' => 'practitioner9@zaya.com', 'dob' => '1983-09-08', 'gender' => 'Female', 'nationality' => 'Italy', 'languages' => ['Italian'], 'phone' => '+390612345691'],
            ['first_name' => 'Ricardo', 'last_name' => 'Santos', 'email' => 'practitioner10@zaya.com', 'dob' => '1977-01-12', 'gender' => 'Male', 'nationality' => 'Brazil', 'languages' => ['Portuguese'], 'phone' => '+551112345690'],
            ['first_name' => 'Jan', 'last_name' => 'Van den Berg', 'email' => 'practitioner11@zaya.com', 'dob' => '1981-04-20', 'gender' => 'Male', 'nationality' => 'Netherlands', 'languages' => ['Dutch'], 'phone' => '+312012345689'],
            ['first_name' => 'Erik', 'last_name' => 'Johansson', 'email' => 'practitioner12@zaya.com', 'dob' => '1984-10-05', 'gender' => 'Male', 'nationality' => 'Sweden', 'languages' => ['Swedish'], 'phone' => '+46812345688'],
            ['first_name' => 'Karen', 'last_name' => 'Hansen', 'email' => 'practitioner13@zaya.com', 'dob' => '1986-05-22', 'gender' => 'Female', 'nationality' => 'Denmark', 'languages' => ['Danish'], 'phone' => '+45312345687'],
            ['first_name' => 'Lars', 'last_name' => 'Larsen', 'email' => 'practitioner14@zaya.com', 'dob' => '1983-08-15', 'gender' => 'Male', 'nationality' => 'Norway', 'languages' => ['Norwegian'], 'phone' => '+47212345686'],
            ['first_name' => 'Anna', 'last_name' => 'Virtanen', 'email' => 'practitioner15@zaya.com', 'dob' => '1990-11-28', 'gender' => 'Female', 'nationality' => 'Finland', 'languages' => ['Finnish'], 'phone' => '+358912345685'],
            ['first_name' => 'Nikos', 'last_name' => 'Pappas', 'email' => 'practitioner16@zaya.com', 'dob' => '1978-02-10', 'gender' => 'Male', 'nationality' => 'Greece', 'languages' => ['Greek'], 'phone' => '+302112345684'],
            ['first_name' => 'Emre', 'last_name' => 'Yilmaz', 'email' => 'practitioner17@zaya.com', 'dob' => '1987-03-04', 'gender' => 'Male', 'nationality' => 'Turkey', 'languages' => ['Turkish'], 'phone' => '+902121234583'],
            ['first_name' => 'David', 'last_name' => 'Cohen', 'email' => 'practitioner18@zaya.com', 'dob' => '1986-12-18', 'gender' => 'Male', 'nationality' => 'Israel', 'languages' => ['Hebrew'], 'phone' => '+972312345682'],
            ['first_name' => 'Li', 'last_name' => 'Zhang', 'email' => 'practitioner19@zaya.com', 'dob' => '1980-06-30', 'gender' => 'Male', 'nationality' => 'China', 'languages' => ['Chinese'], 'phone' => '+861012345681'],
            ['first_name' => 'Kim', 'last_name' => 'Lee', 'email' => 'practitioner20@zaya.com', 'dob' => '1985-09-25', 'gender' => 'Female', 'nationality' => 'South Korea', 'languages' => ['Korean'], 'phone' => '+82212345680'],
            ['first_name' => 'Amit', 'last_name' => 'Sharma', 'email' => 'practitioner21@zaya.com', 'dob' => '1978-04-12', 'gender' => 'Male', 'nationality' => 'India', 'languages' => ['Hindi'], 'phone' => '+919876543281'],
            ['first_name' => 'Sunita', 'last_name' => 'Patel', 'email' => 'practitioner22@zaya.com', 'dob' => '1982-08-18', 'gender' => 'Female', 'nationality' => 'India', 'languages' => ['Gujarati'], 'phone' => '+919876543282'],
            ['first_name' => 'Rajesh', 'last_name' => 'Gupta', 'email' => 'practitioner23@zaya.com', 'dob' => '1975-05-25', 'gender' => 'Male', 'nationality' => 'India', 'languages' => ['Bengali'], 'phone' => '+919876543283'],
            ['first_name' => 'Ahmed', 'last_name' => 'Zahra', 'email' => 'practitioner24@zaya.com', 'dob' => '1983-01-24', 'gender' => 'Male', 'nationality' => 'UAE', 'languages' => ['Arabic'], 'phone' => '+971412345684'],
            ['first_name' => 'Mandla', 'last_name' => 'Mokoena', 'email' => 'practitioner25@zaya.com', 'dob' => '1979-10-15', 'gender' => 'Male', 'nationality' => 'South Africa', 'languages' => ['Zulu'], 'phone' => '+271112345685'],
            ['first_name' => 'Bruce', 'last_name' => 'Taylor', 'email' => 'practitioner26@zaya.com', 'dob' => '1974-07-22', 'gender' => 'Male', 'nationality' => 'Australia', 'languages' => ['English'], 'phone' => '+61212345686'],
            ['first_name' => 'Alice', 'last_name' => 'Wilson', 'email' => 'practitioner27@zaya.com', 'dob' => '1992-03-14', 'gender' => 'Female', 'nationality' => 'New Zealand', 'languages' => ['English'], 'phone' => '+64312345687'],
            ['first_name' => 'Igor', 'last_name' => 'Petrov', 'email' => 'practitioner28@zaya.com', 'dob' => '1981-09-21', 'gender' => 'Male', 'nationality' => 'Russia', 'languages' => ['Russian'], 'phone' => '+749512345688'],
            ['first_name' => 'Franz', 'last_name' => 'Schmidt', 'email' => 'practitioner29@zaya.com', 'dob' => '1970-04-05', 'gender' => 'Male', 'nationality' => 'Austria', 'languages' => ['German'], 'phone' => '+43112345689'],
            ['first_name' => 'Paulo', 'last_name' => 'Silva', 'email' => 'practitioner30@zaya.com', 'dob' => '1984-06-18', 'gender' => 'Male', 'nationality' => 'Portugal', 'languages' => ['Portuguese'], 'phone' => '+351211234580'],
        ];

        foreach ($practitioners as $practitioner) {
            $user = User::updateOrCreate(
                ['email' => $practitioner['email']],
                [
                    'name' => $practitioner['first_name'] . ' ' . $practitioner['last_name'],
                    'first_name' => $practitioner['first_name'],
                    'last_name' => $practitioner['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'practitioner',
                    'status' => 'active',
                ]
            );

            $profile = Practitioner::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'active',
                    'first_name' => $practitioner['first_name'],
                    'last_name' => $practitioner['last_name'],
                    'gender' => $practitioner['gender'],
                    'dob' => $practitioner['dob'],
                    'nationality' => $practitioner['nationality'],
                    'country' => $practitioner['nationality'],
                    'zip_code' => '500' . substr($practitioner['email'], 12, 2),
                    'phone' => $practitioner['phone'],
                    'languages_spoken' => $practitioner['languages'],
                    'can_translate_english' => in_array('English', $practitioner['languages']),
                    'profile_bio' => 'A dedicated health practitioner with expertise in services provided in ' . $practitioner['nationality'],
                    'address_line_1' => 'Suite ' . substr($practitioner['email'], 12, 2),
                    'city' => 'City ' . $practitioner['nationality'],
                    'state' => 'State ' . $practitioner['nationality'],
                    'consultations' => ['Ayurveda Nutrition Advisor', 'Lifestyle Advice'],
                    'body_therapies' => ['Abhyanga', 'Marma Therapy'],
                    'other_modalities' => ['Yoga Sessions'],
                    'social_links' => [
                        'website' => 'https://zaya.com/' . strtolower($practitioner['first_name']),
                        'instagram' => 'https://instagram.com/' . strtolower($practitioner['first_name']),
                        'linkedin' => 'https://linkedin.com/in/' . strtolower($practitioner['first_name']),
                    ]
                ]
            );

            // Add one qualification
            $profile->qualifications()->updateOrCreate(
                ['institute_name' => 'Global Health Institute'],
                [
                    'year_of_passing' => '2010',
                    'training_diploma_title' => 'Advanced Diploma in Integrative Health',
                    'training_duration_online_hours' => 100,
                    'training_duration_contact_hours' => 200,
                    'institute_postal_address' => '123 Education Lane, ' . $practitioner['nationality'],
                    'institute_contact_details' => $practitioner['phone'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['first_name' => 'Emma', 'last_name' => 'Smith', 'email' => 'client@zaya.com', 'client_id' => 'CL-00001', 'dob' => '1990-05-15', 'age' => 34, 'gender' => 'Female', 'country' => 'USA', 'languages' => ['English'], 'occupation' => 'Engineer', 'phone' => '+12025550101'],
            ['first_name' => 'Liam', 'last_name' => 'Wilson', 'email' => 'client2@zaya.com', 'client_id' => 'CL-00002', 'dob' => '1985-08-22', 'age' => 38, 'gender' => 'Male', 'country' => 'Canada', 'languages' => ['English', 'French'], 'occupation' => 'Manager', 'phone' => '+14165550102'],
            ['first_name' => 'Olivia', 'last_name' => 'Brown', 'email' => 'client3@zaya.com', 'client_id' => 'CL-00003', 'dob' => '1992-12-10', 'age' => 31, 'gender' => 'Female', 'country' => 'UK', 'languages' => ['English'], 'occupation' => 'Teacher', 'phone' => '+442079460103'],
            ['first_name' => 'Noah', 'last_name' => 'Garcia', 'email' => 'client4@zaya.com', 'client_id' => 'CL-00004', 'dob' => '1988-03-04', 'age' => 36, 'gender' => 'Male', 'country' => 'Mexico', 'languages' => ['Spanish'], 'occupation' => 'Architect', 'phone' => '+525555550104'],
            ['first_name' => 'Ava', 'last_name' => 'Martinez', 'email' => 'client5@zaya.com', 'client_id' => 'CL-00005', 'dob' => '1995-07-19', 'age' => 28, 'gender' => 'Female', 'country' => 'Spain', 'languages' => ['Spanish'], 'occupation' => 'Artist', 'phone' => '+34912345605'],
            ['first_name' => 'William', 'last_name' => 'Müller', 'email' => 'client6@zaya.com', 'client_id' => 'CL-00006', 'dob' => '1982-11-30', 'age' => 41, 'gender' => 'Male', 'country' => 'Germany', 'languages' => ['German'], 'occupation' => 'Scientist', 'phone' => '+493012345606'],
            ['first_name' => 'Sophia', 'last_name' => 'Dubois', 'email' => 'client7@zaya.com', 'client_id' => 'CL-00007', 'dob' => '1998-02-14', 'age' => 26, 'gender' => 'Female', 'country' => 'France', 'languages' => ['French'], 'occupation' => 'Designer', 'phone' => '+33123456707'],
            ['first_name' => 'James', 'last_name' => 'Sato', 'email' => 'client8@zaya.com', 'client_id' => 'CL-00008', 'dob' => '1979-06-25', 'age' => 44, 'gender' => 'Male', 'country' => 'Japan', 'languages' => ['Japanese'], 'occupation' => 'Accountant', 'phone' => '+81312345608'],
            ['first_name' => 'Isabella', 'last_name' => 'Rossi', 'email' => 'client9@zaya.com', 'client_id' => 'CL-00009', 'dob' => '1993-09-08', 'age' => 30, 'gender' => 'Female', 'country' => 'Italy', 'languages' => ['Italian'], 'occupation' => 'Chef', 'phone' => '+390612345609'],
            ['first_name' => 'Lucas', 'last_name' => 'Santos', 'email' => 'client10@zaya.com', 'client_id' => 'CL-00010', 'dob' => '1987-01-12', 'age' => 37, 'gender' => 'Male', 'country' => 'Brazil', 'languages' => ['Portuguese'], 'occupation' => 'Lawyer', 'phone' => '+551112345610'],
            ['first_name' => 'Mia', 'last_name' => 'Van den Berg', 'email' => 'client11@zaya.com', 'client_id' => 'CL-00011', 'dob' => '1991-04-20', 'age' => 33, 'gender' => 'Female', 'country' => 'Netherlands', 'languages' => ['Dutch'], 'occupation' => 'Analyst', 'phone' => '+312012345611'],
            ['first_name' => 'Ethan', 'last_name' => 'Johansson', 'email' => 'client12@zaya.com', 'client_id' => 'CL-00012', 'dob' => '1984-10-05', 'age' => 39, 'gender' => 'Male', 'country' => 'Sweden', 'languages' => ['Swedish'], 'occupation' => 'Writer', 'phone' => '+46812345612'],
            ['first_name' => 'Charlotte', 'last_name' => 'Hansen', 'email' => 'client13@zaya.com', 'client_id' => 'CL-00013', 'dob' => '1996-05-22', 'age' => 27, 'gender' => 'Female', 'country' => 'Denmark', 'languages' => ['Danish'], 'occupation' => 'Journalist', 'phone' => '+45312345613'],
            ['first_name' => 'Benjamin', 'last_name' => 'Larsen', 'email' => 'client14@zaya.com', 'client_id' => 'CL-00014', 'dob' => '1983-08-15', 'age' => 40, 'gender' => 'Male', 'country' => 'Norway', 'languages' => ['Norwegian'], 'occupation' => 'Doctor', 'phone' => '+47212345614'],
            ['first_name' => 'Amelia', 'last_name' => 'Virtanen', 'email' => 'client15@zaya.com', 'client_id' => 'CL-00015', 'dob' => '1994-11-28', 'age' => 29, 'gender' => 'Female', 'country' => 'Finland', 'languages' => ['Finnish'], 'occupation' => 'Pilot', 'phone' => '+358912345615'],
            ['first_name' => 'Alexander', 'last_name' => 'Pappas', 'email' => 'client16@zaya.com', 'client_id' => 'CL-00016', 'dob' => '1981-02-10', 'age' => 43, 'gender' => 'Male', 'country' => 'Greece', 'languages' => ['Greek'], 'occupation' => 'Consultant', 'phone' => '+302112345616'],
            ['first_name' => 'Chloe', 'last_name' => 'Yilmaz', 'email' => 'client17@zaya.com', 'client_id' => 'CL-00017', 'dob' => '1997-03-04', 'age' => 27, 'gender' => 'Female', 'country' => 'Turkey', 'languages' => ['Turkish'], 'occupation' => 'Researcher', 'phone' => '+902121234517'],
            ['first_name' => 'Samuel', 'last_name' => 'Cohen', 'email' => 'client18@zaya.com', 'client_id' => 'CL-00018', 'dob' => '1986-12-18', 'age' => 37, 'gender' => 'Male', 'country' => 'Israel', 'languages' => ['Hebrew'], 'occupation' => 'Musician', 'phone' => '+972312345618'],
            ['first_name' => 'Daniel', 'last_name' => 'Zhang', 'email' => 'client19@zaya.com', 'client_id' => 'CL-00019', 'dob' => '1990-06-30', 'age' => 33, 'gender' => 'Male', 'country' => 'China', 'languages' => ['Chinese'], 'occupation' => 'Programmer', 'phone' => '+861012345619'],
            ['first_name' => 'Grace', 'last_name' => 'Lee', 'email' => 'client20@zaya.com', 'client_id' => 'CL-00020', 'dob' => '1995-09-25', 'age' => 28, 'gender' => 'Female', 'country' => 'South Korea', 'languages' => ['Korean'], 'occupation' => 'Editor', 'phone' => '+82212345620'],
            ['first_name' => 'Arjun', 'last_name' => 'Sharma', 'email' => 'client21@zaya.com', 'client_id' => 'CL-00021', 'dob' => '1988-04-12', 'age' => 35, 'gender' => 'Male', 'country' => 'India', 'languages' => ['Hindi'], 'occupation' => 'Salesman', 'phone' => '+919876543221'],
            ['first_name' => 'Priya', 'last_name' => 'Patel', 'email' => 'client22@zaya.com', 'client_id' => 'CL-00022', 'dob' => '1992-08-18', 'age' => 31, 'gender' => 'Female', 'country' => 'India', 'languages' => ['Gujarati'], 'occupation' => 'Nurse', 'phone' => '+919876543222'],
            ['first_name' => 'Rahul', 'last_name' => 'Gupta', 'email' => 'client23@zaya.com', 'client_id' => 'CL-00023', 'dob' => '1985-05-25', 'age' => 38, 'gender' => 'Male', 'country' => 'India', 'languages' => ['Bengali'], 'occupation' => 'Marketing', 'phone' => '+919876543223'],
            ['first_name' => 'Fatima', 'last_name' => 'Zahra', 'email' => 'client24@zaya.com', 'client_id' => 'CL-00024', 'dob' => '1993-01-24', 'age' => 31, 'gender' => 'Female', 'country' => 'UAE', 'languages' => ['Arabic'], 'occupation' => 'Secretary', 'phone' => '+971412345624'],
            ['first_name' => 'Thabo', 'last_name' => 'Mokoena', 'email' => 'client25@zaya.com', 'client_id' => 'CL-00025', 'dob' => '1989-10-15', 'age' => 34, 'gender' => 'Male', 'country' => 'South Africa', 'languages' => ['Zulu'], 'occupation' => 'Technician', 'phone' => '+271112345625'],
            ['first_name' => 'Jack', 'last_name' => 'Taylor', 'email' => 'client26@zaya.com', 'client_id' => 'CL-00026', 'dob' => '1984-07-22', 'age' => 39, 'gender' => 'Male', 'country' => 'Australia', 'languages' => ['English'], 'occupation' => 'Farmer', 'phone' => '+61212345626'],
            ['first_name' => 'Sophie', 'last_name' => 'Wilson', 'email' => 'client27@zaya.com', 'client_id' => 'CL-00027', 'dob' => '1996-03-14', 'age' => 28, 'gender' => 'Female', 'country' => 'New Zealand', 'languages' => ['English'], 'occupation' => 'Clerk', 'phone' => '+64312345627'],
            ['first_name' => 'Elena', 'last_name' => 'Petrov', 'email' => 'client28@zaya.com', 'client_id' => 'CL-00028', 'dob' => '1991-09-21', 'age' => 32, 'gender' => 'Female', 'country' => 'Russia', 'languages' => ['Russian'], 'occupation' => 'Translator', 'phone' => '+749512345628'],
            ['first_name' => 'Hans', 'last_name' => 'Schmidt', 'email' => 'client29@zaya.com', 'client_id' => 'CL-00029', 'dob' => '1980-04-05', 'age' => 44, 'gender' => 'Male', 'country' => 'Austria', 'languages' => ['German'], 'occupation' => 'Baker', 'phone' => '+43112345629'],
            ['first_name' => 'Maria', 'last_name' => 'Silva', 'email' => 'client30@zaya.com', 'client_id' => 'CL-00030', 'dob' => '1994-06-18', 'age' => 29, 'gender' => 'Female', 'country' => 'Portugal', 'languages' => ['Portuguese'], 'occupation' => 'Librarian', 'phone' => '+351211234530'],
        ];

        foreach ($clients as $client) {
            $user = User::updateOrCreate(
                ['email' => $client['email']],
                [
                    'name' => $client['first_name'] . ' ' . $client['last_name'],
                    'first_name' => $client['first_name'],
                    'last_name' => $client['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'status' => 'active',
                ]
            );

            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'client_id' => $client['client_id'],
                    'dob' => $client['dob'],
                    'age' => $client['age'],
                    'gender' => $client['gender'],
                    'occupation' => $client['occupation'],
                    'address_line_1' => 'Street ' . $client['client_id'],
                    'city' => 'City ' . $client['country'],
                    'state' => 'State ' . $client['country'],
                    'zip_code' => '100' . substr($client['client_id'], -2),
                    'country' => $client['country'],
                    'phone' => $client['phone'],
                    'languages_spoken' => $client['languages'],
                    'consultation_preferences' => ['Online', 'In-person'],
                    'status' => 'active',
                ]
            );
        }
    }
}

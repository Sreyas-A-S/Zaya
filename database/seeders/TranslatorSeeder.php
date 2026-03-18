<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Translator;
use App\Models\TranslatorService;
use App\Models\TranslatorSpecialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TranslatorSeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = ['India', 'USA', 'UK', 'Germany', 'France', 'UAE', 'Canada', 'Australia', 'Japan', 'Singapore'];
        $cities = ['Mumbai', 'New York', 'London', 'Berlin', 'Paris', 'Dubai', 'Toronto', 'Sydney', 'Tokyo', 'Singapore'];
        $languages = ['Hindi', 'English', 'English', 'German', 'French', 'Arabic', 'English', 'English', 'Japanese', 'Mandarin'];

        $services = TranslatorService::pluck('name')->toArray() ?: ['Translation', 'Interpretation', 'Localization'];
        $specializations = TranslatorSpecialization::pluck('name')->toArray() ?: ['Medical', 'Legal', 'Technical'];

        for ($i = 1; $i <= 30; $i++) {
            $index = ($i - 1) % 10;
            $country = $nationalities[$index];
            $city = $cities[$index];
            $lang = $languages[$index];

            $firstName = "Translator";
            $lastName = "Expert" . $i;
            $fullName = $firstName . " " . $lastName;

            $user = User::updateOrCreate(
                ['email' => "translator{$i}@zaya.com"],
                [
                    'name' => $fullName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make('password'),
                    'role' => 'translator',
                    'gender' => ($i % 2 == 0) ? 'Female' : 'Male',
                    'phone' => '+447890' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );

            Translator::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'active',
                    'full_name' => $fullName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $user->gender,
                    'dob' => '1990-03-' . str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT),
                    'phone' => $user->phone,
                    'native_language' => $lang,
                    'source_languages' => [$lang, 'English'],
                    'target_languages' => ['English', $lang],
                    'translator_type' => 'Freelance',
                    'years_of_experience' => ($i % 12) + 2,
                    'fields_of_specialization' => array_slice($specializations, 0, 2),
                    'highest_education' => 'Bachelor in Linguistics',
                    'certification_details' => 'Certified Professional Translator',
                    'services_offered' => array_slice($services, 0, 2),
                    'gov_id_type' => 'National ID',
                    'pan_number' => 'KLMNO' . (9012 + $i) . 'P',
                    'bank_holder_name' => $fullName,
                    'bank_name' => 'Central Bank of ' . $country,
                    'account_number' => '5566778899' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'ifsc_code' => 'CNBK000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'address_line_1' => ($i * 15) . ' Language Street',
                    'city' => $city,
                    'state' => $city . ' Region',
                    'zip_code' => '300' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'country' => $country,
                ]
            );
        }
    }
}

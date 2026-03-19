<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            DoctorSeeder::class,
            PractitionerSeeder::class,
            LanguageSeeder::class,
            DoctorMasterDataSeeder::class,
            PractitionerMasterDataSeeder::class,
            ClientConsultationPreferenceSeeder::class,
            YogaExpertiseSeeder::class,
            ServiceCategorySeeder::class,
            ClientSeeder::class,
            MindfulnessPractitionerSeeder::class,
            TranslatorSeeder::class,
            YogaTherapistSeeder::class,
            TestimonialSeeder::class,
            ServiceSeeder::class,
            ServiceSeederFrench::class,
            PractitionerReviewSeeder::class,
            CountriesSeeder::class,
            GeneralSettingSeeder::class,
            SocialLinksSettingsSeeder::class,
            FinanceSettingSeeder::class,
            HomepageSettingSeeder::class,
            HomepageSettingFrenchSeeder::class,
            ContactSettingSeeder::class,
            ContactSettingFrenchSeeder::class,
            AdminPanelSettingSeeder::class,
            AdminPanelSettingFrenchSeeder::class,
            FinanceManagerSeeder::class,
            ContentManagerSeeder::class,
            UserManagerSeeder::class,
            FooterSettingSeeder::class,
            FooterSettingFrenchSeeder::class,
            BookingSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '1234567890',
            ]
        );
    }
}

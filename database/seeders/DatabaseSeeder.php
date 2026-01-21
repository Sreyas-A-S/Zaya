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
            DoctorSeeder::class,
            PractitionerSeeder::class,
            LanguageSeeder::class,
            DoctorMasterDataSeeder::class,
            PractitionerMasterDataSeeder::class,
            ClientConsultationPreferenceSeeder::class,
            ClientSeeder::class,
            MindfulnessCounsellorSeeder::class,
            TranslatorSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}

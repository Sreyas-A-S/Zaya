<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Practitioner;
use App\Models\MindfulnessPractitioner;
use App\Models\YogaTherapist;
use App\Models\Patient;
use App\Models\Translator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = [
            [
                'email' => 'testdoctor@gmail.com',
                'role' => 'doctor',
                'name' => 'Test Doctor',
                'first_name' => 'Test',
                'last_name' => 'Doctor',
            ],
            [
                'email' => 'testpractitioner@gmail.com',
                'role' => 'practitioner',
                'name' => 'Test Practitioner',
                'first_name' => 'Test',
                'last_name' => 'Practitioner',
            ],
            [
                'email' => 'testmindfulnesscounsellor@gmail.com',
                'role' => 'mindfulness_practitioner',
                'name' => 'Test Mindfulness',
                'first_name' => 'Test',
                'last_name' => 'Mindfulness',
            ],
            [
                'email' => 'testyogatherapist@gmail.com',
                'role' => 'yoga_therapist',
                'name' => 'Test Yoga',
                'first_name' => 'Test',
                'last_name' => 'Yoga',
            ],
            [
                'email' => 'testclient@gmail.com',
                'role' => 'client',
                'name' => 'Test Client',
                'first_name' => 'Test',
                'last_name' => 'Client',
            ],
            [
                'email' => 'testtranslator@gmail.com',
                'role' => 'translator',
                'name' => 'Test Translator',
                'first_name' => 'Test',
                'last_name' => 'Translator',
            ],
        ];

        foreach ($users as $userData) {
            // Check if user exists to avoid duplicates
            if (User::where('email', $userData['email'])->exists()) {
                continue;
            }

            $user = User::create([
                'name' => $userData['name'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('Password@123'),
                'role' => $userData['role'],
                'status' => 'active',
            ]);

            $profileData = [
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'status' => 'active',
            ];

            try {
                match ($userData['role']) {
                    'doctor' => $user->doctor()->create($profileData),
                    'practitioner' => $user->practitioner()->create($profileData),
                    'mindfulness_practitioner' => $user->mindfulnessPractitioner()->create($profileData),
                    'yoga_therapist' => $user->yogaTherapist()->create($profileData),
                    'client' => $user->patient()->create($profileData),
                    'translator' => $user->translator()->create($profileData),
                };
            } catch (\Exception $e) {
                Log::error("Failed to create profile for {$userData['email']}: " . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $emails = [
            'testdoctor@gmail.com',
            'testpractitioner@gmail.com',
            'testmindfulnesscounsellor@gmail.com',
            'testyogatherapist@gmail.com',
            'testclient@gmail.com',
            'testtranslator@gmail.com',
        ];

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Delete profiles
                $user->doctor()?->delete();
                $user->practitioner()?->delete();
                $user->mindfulnessPractitioner()?->delete();
                $user->yogaTherapist()?->delete();
                $user->patient()?->delete();
                $user->translator()?->delete();
                
                $user->delete();
            }
        }
    }
};

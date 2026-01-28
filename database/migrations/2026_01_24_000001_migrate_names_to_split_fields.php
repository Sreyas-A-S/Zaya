<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $parts = explode(' ', $user->name, 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';

            DB::table('users')->where('id', $user->id)->update([
                'first_name' => $firstName,
                'last_name' => $lastName
            ]);
        }

        // 2. Doctors
        if (Schema::hasTable('doctors')) {
            $doctors = DB::table('doctors')->get();
            foreach ($doctors as $doctor) {
                // Check if full_name column exists in the row object (it should if table has it)
                $fullName = $doctor->full_name ?? '';
                if (empty($fullName)) {
                    // Fallback to user
                    $user = DB::table('users')->where('id', $doctor->user_id)->first();
                    $fullName = $user ? $user->name : '';
                }

                $parts = explode(' ', $fullName, 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '';

                DB::table('doctors')->where('id', $doctor->id)->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ]);
            }
        }

        // 3. Mindfulness Counsellors
        if (Schema::hasTable('mindfulness_counsellors')) {
            $counsellors = DB::table('mindfulness_counsellors')->get();
            foreach ($counsellors as $counsellor) {
                $fullName = $counsellor->full_name ?? '';
                if (empty($fullName)) {
                    $user = DB::table('users')->where('id', $counsellor->user_id)->first();
                    $fullName = $user ? $user->name : '';
                }

                $parts = explode(' ', $fullName, 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '';

                DB::table('mindfulness_counsellors')->where('id', $counsellor->id)->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ]);
            }
        }

        // 4. Translators
        if (Schema::hasTable('translators')) {
            $translators = DB::table('translators')->get();
            foreach ($translators as $translator) {
                $fullName = $translator->full_name ?? '';
                if (empty($fullName)) {
                    $user = DB::table('users')->where('id', $translator->user_id)->first();
                    $fullName = $user ? $user->name : '';
                }

                $parts = explode(' ', $fullName, 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '';

                DB::table('translators')->where('id', $translator->id)->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ]);
            }
        }

        // 5. Yoga Therapists
        if (Schema::hasTable('yoga_therapists')) {
            $therapists = DB::table('yoga_therapists')->get();
            foreach ($therapists as $therapist) {
                // Yoga therapists don't have full_name column (based on previous check), so rely on User
                $user = DB::table('users')->where('id', $therapist->user_id)->first();
                $fullName = $user ? $user->name : '';

                $parts = explode(' ', $fullName, 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '';

                DB::table('yoga_therapists')->where('id', $therapist->id)->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};

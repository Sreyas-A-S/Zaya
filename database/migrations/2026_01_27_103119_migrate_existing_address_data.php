<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Doctors
        if (Schema::hasTable('doctors')) {
            DB::table('doctors')->get()->each(function ($row) {
                $update = [];
                // Check possible old columns
                if (isset($row->residential_address)) $update['address_line_1'] = $row->residential_address;
                elseif (isset($row->address)) $update['address_line_1'] = $row->address;
                elseif (isset($row->clinic_address)) $update['address_line_1'] = $row->clinic_address;

                if (isset($row->zip_code) && !empty($row->zip_code) && !isset($update['zip_code'])) {
                    // If zip_code was already there, it might be the same field or new. 
                    // Since we just added it, if it's not empty it might be from the old table if zip_code existed.
                }

                if (!empty($update)) {
                    DB::table('doctors')->where('id', $row->id)->update($update);
                }
            });
        }

        // 2. Practitioners
        if (Schema::hasTable('practitioners')) {
            DB::table('practitioners')->get()->each(function ($row) {
                $update = [];
                if (isset($row->residential_address)) $update['address_line_1'] = $row->residential_address;
                if (isset($row->zip_code)) $update['zip_code'] = $row->zip_code;
                if (!empty($update)) {
                    DB::table('practitioners')->where('id', $row->id)->update($update);
                }
            });
        }

        // 3. Mindfulness Practitioners
        if (Schema::hasTable('mindfulness_practitioners')) {
            DB::table('mindfulness_practitioners')->get()->each(function ($row) {
                if (isset($row->address)) {
                    DB::table('mindfulness_practitioners')->where('id', $row->id)->update(['address_line_1' => $row->address]);
                }
            });
        }

        // 4. Yoga Therapists
        if (Schema::hasTable('yoga_therapists')) {
            DB::table('yoga_therapists')->get()->each(function ($row) {
                if (isset($row->address)) {
                    DB::table('yoga_therapists')->where('id', $row->id)->update(['address_line_1' => $row->address]);
                }
            });
        }

        // 5. Patients
        if (Schema::hasTable('patients')) {
            DB::table('patients')->get()->each(function ($row) {
                $update = [];
                if (isset($row->address)) $update['address_line_1'] = $row->address;
                if (isset($row->city_state)) $update['city'] = $row->city_state;
                if (!empty($update)) {
                    DB::table('patients')->where('id', $row->id)->update($update);
                }
            });
        }

        // 6. Translators
        if (Schema::hasTable('translators')) {
            DB::table('translators')->get()->each(function ($row) {
                if (isset($row->address)) {
                    DB::table('translators')->where('id', $row->id)->update(['address_line_1' => $row->address]);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};

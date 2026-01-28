<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            Schema::table('doctors', function (Blueprint $table) {
                if (Schema::hasColumn('doctors', 'city_state')) {
                    $table->dropColumn('city_state');
                }
                if (Schema::hasColumn('doctors', 'clinic_address')) {
                    $table->dropColumn('clinic_address');
                }
            });
        }

        // 2. Practitioners
        if (Schema::hasTable('practitioners')) {
            Schema::table('practitioners', function (Blueprint $table) {
                if (Schema::hasColumn('practitioners', 'residential_address')) {
                    $table->dropColumn('residential_address');
                }
            });
        }

        // 3. Mindfulness Practitioners
        if (Schema::hasTable('mindfulness_practitioners')) {
            Schema::table('mindfulness_practitioners', function (Blueprint $table) {
                if (Schema::hasColumn('mindfulness_practitioners', 'address')) {
                    $table->dropColumn('address');
                }
            });
        }

        // 4. Yoga Therapists
        if (Schema::hasTable('yoga_therapists')) {
            Schema::table('yoga_therapists', function (Blueprint $table) {
                if (Schema::hasColumn('yoga_therapists', 'address')) {
                    $table->dropColumn('address');
                }
            });
        }

        // 5. Patients
        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                if (Schema::hasColumn('patients', 'address')) {
                    $table->dropColumn('address');
                }
                if (Schema::hasColumn('patients', 'city_state')) {
                    $table->dropColumn('city_state');
                }
            });
        }

        // 6. Translators
        if (Schema::hasTable('translators')) {
            Schema::table('translators', function (Blueprint $table) {
                if (Schema::hasColumn('translators', 'address')) {
                    $table->dropColumn('address');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-adding dropped columns would be complex without knowing types perfectly, avoiding for now as it's a destructive step requested.
    }
};

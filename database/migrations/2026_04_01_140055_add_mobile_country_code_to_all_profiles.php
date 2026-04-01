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
        $tables = [
            'practitioners',
            'doctors',
            'mindfulness_practitioners',
            'yoga_therapists',
            'translators'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'mobile_country_code')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('mobile_country_code', 10)->nullable()->after('phone');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'practitioners',
            'doctors',
            'mindfulness_practitioners',
            'yoga_therapists',
            'translators'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'mobile_country_code')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('mobile_country_code');
                });
            }
        }
    }
};

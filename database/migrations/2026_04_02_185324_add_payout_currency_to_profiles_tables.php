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
            'doctors',
            'practitioners',
            'mindfulness_practitioners',
            'yoga_therapists',
            'translators',
            'patients'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('payout_currency', 10)->default('INR')->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'doctors',
            'practitioners',
            'mindfulness_practitioners',
            'yoga_therapists',
            'translators',
            'patients'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('payout_currency');
            });
        }
    }
};

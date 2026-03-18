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
            'employees',
            'mindfulness_practitioners',
            'patients',
            'practitioners',
            'translators',
            'yoga_therapists'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('address_line_1', 500)->nullable()->change();
                $table->string('address_line_2', 500)->nullable()->change();
                $table->string('country', 255)->nullable()->change();
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
            'employees',
            'mindfulness_practitioners',
            'patients',
            'practitioners',
            'translators',
            'yoga_therapists'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Return to previous default length of 191
                $table->string('address_line_1', 191)->nullable()->change();
                $table->string('address_line_2', 191)->nullable()->change();
                $table->string('country', 100)->nullable()->change();
            });
        }
    }
};

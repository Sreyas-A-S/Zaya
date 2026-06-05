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
        $tables = ['practitioners', 'doctors', 'mindfulness_practitioners', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('reminder_lead_time', 255)->default('60')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['practitioners', 'doctors', 'mindfulness_practitioners', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->integer('reminder_lead_time')->default(60)->change();
            });
        }
    }
};

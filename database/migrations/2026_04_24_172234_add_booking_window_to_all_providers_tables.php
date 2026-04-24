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
        $tables = ['doctors', 'mindfulness_practitioners', 'yoga_therapists', 'translators'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    if (!Schema::hasColumn($table, 'booking_window_days')) {
                        $t->integer('booking_window_days')->default(14);
                    }
                    if (!Schema::hasColumn($table, 'reminder_lead_time')) {
                        $t->integer('reminder_lead_time')->default(60);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['doctors', 'mindfulness_practitioners', 'yoga_therapists', 'translators'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    if (Schema::hasColumn($table, 'booking_window_days')) {
                        $t->dropColumn('booking_window_days');
                    }
                    if (Schema::hasColumn($table, 'reminder_lead_time')) {
                        $t->dropColumn('reminder_lead_time');
                    }
                });
            }
        }
    }
};

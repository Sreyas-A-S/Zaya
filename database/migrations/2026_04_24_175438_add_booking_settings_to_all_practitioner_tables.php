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
        $tables = ['doctors', 'mindfulness_practitioners', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'booking_window_days')) {
                    $table->integer('booking_window_days')->default(14)->after('status');
                }
                if (!Schema::hasColumn($table->getTable(), 'default_slot_duration')) {
                    $table->integer('default_slot_duration')->default(60)->after('booking_window_days');
                }
                if (!Schema::hasColumn($table->getTable(), 'reminder_lead_time')) {
                    $table->integer('reminder_lead_time')->default(60)->after('default_slot_duration');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['doctors', 'mindfulness_practitioners', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['booking_window_days', 'default_slot_duration', 'reminder_lead_time']);
            });
        }
    }
};

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
        // 1. Remove from practitioners
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropColumn(['default_slot_duration', 'min_notice_hours']);
        });

        // 2. Add to practitioner_availabilities
        Schema::table('practitioner_availabilities', function (Blueprint $table) {
            $table->integer('slot_duration')->default(60)->after('end_time')->comment('Duration of a consultation in minutes.');
            $table->integer('min_notice_hours')->default(24)->after('slot_duration')->comment('Minimum hours of notice required before booking.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practitioner_availabilities', function (Blueprint $table) {
            $table->dropColumn(['slot_duration', 'min_notice_hours']);
        });

        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('default_slot_duration')->default(60)->after('booking_window_days');
            $table->integer('min_notice_hours')->default(24)->after('default_slot_duration');
        });
    }
};

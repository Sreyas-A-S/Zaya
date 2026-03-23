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
        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('default_slot_duration')->default(60)->after('booking_window_days')->comment('Default duration of a consultation in minutes.');
            $table->integer('min_notice_hours')->default(24)->after('default_slot_duration')->comment('Minimum hours of notice required before booking.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropColumn(['default_slot_duration', 'min_notice_hours']);
        });
    }
};

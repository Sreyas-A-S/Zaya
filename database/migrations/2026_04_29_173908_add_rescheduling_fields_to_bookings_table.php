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
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('original_booking_date')->nullable()->after('booking_time');
            $table->string('original_booking_time')->nullable()->after('original_booking_date');
            $table->timestamp('rescheduled_at')->nullable()->after('original_booking_time');
            $table->string('rescheduled_by')->nullable()->after('rescheduled_at'); // e.g. 'practitioner', 'client'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};

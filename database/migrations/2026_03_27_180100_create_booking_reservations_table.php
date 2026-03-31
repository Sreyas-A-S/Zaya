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
        Schema::create('booking_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('practitioner_id')->constrained('practitioners')->onDelete('cascade');
            $table->date('booking_date');
            $table->string('booking_time', 50);
            $table->string('reservation_token')->unique();
            $table->string('status', 20)->default('reserved'); // reserved, confirmed, expired
            $table->json('booking_data')->nullable(); // Store booking details for confirmation
            $table->timestamp('expires_at'); // Reservation expires after 15 minutes
            $table->timestamps();
            
            // Index for checking active reservations
            $table->index(['practitioner_id', 'booking_date', 'booking_time', 'status'], 'br_p_date_time_status_idx');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_reservations');
    }
};

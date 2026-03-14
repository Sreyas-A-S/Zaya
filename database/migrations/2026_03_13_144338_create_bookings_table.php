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
        Schema::create('bookings', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('practitioner_id')->constrained('practitioners')->onDelete('cascade');
            $blueprint->json('service_ids');
            $blueprint->enum('mode', ['online', 'in-person']);
            $blueprint->text('conditions')->nullable();
            $blueprint->text('situation')->nullable();
            $blueprint->boolean('need_translator')->default(false);
            $blueprint->string('from_language')->nullable();
            $blueprint->string('to_language')->nullable();
            $blueprint->foreignId('language_id')->nullable()->constrained('languages')->onDelete('set null');
            $blueprint->foreignId('translator_id')->nullable()->constrained('translators')->onDelete('set null');
            $blueprint->date('booking_date');
            $blueprint->string('booking_time');
            $blueprint->decimal('total_price', 10, 2);
            $blueprint->string('status')->default('pending'); // pending, paid, cancelled, completed
            $blueprint->string('razorpay_order_id')->nullable();
            $blueprint->string('razorpay_payment_id')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('practitioner_id')->constrained('practitioners')->onDelete('cascade');
            $table->json('service_ids');
            $table->string('mode'); // online, in-person
            $table->text('conditions')->nullable();
            $table->text('situation')->nullable();
            $table->boolean('need_translator')->default(false);
            $table->string('from_language')->nullable();
            $table->string('to_language')->nullable();
            $table->foreignId('language_id')->nullable()->constrained('languages')->onDelete('set null');
            $table->foreignId('translator_id')->nullable()->constrained('translators')->onDelete('set null');
            $table->date('booking_date');
            $table->string('booking_time');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->timestamps();
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

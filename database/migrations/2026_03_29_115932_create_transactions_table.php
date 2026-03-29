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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Payer (Client)
            $table->foreignId('practitioner_id')->nullable()->constrained('users')->onDelete('set null'); // Receiver (Practitioner)
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('referral_id')->nullable()->constrained()->onDelete('set null');
            
            $table->decimal('total_amount', 10, 2);
            $table->string('currency')->default('INR');
            
            $table->decimal('company_share', 10, 2);
            $table->decimal('practitioner_share', 10, 2);
            $table->decimal('referrer_share', 10, 2)->default(0);
            
            $table->decimal('company_commission_percent', 5, 2);
            $table->decimal('referrer_commission_percent', 5, 2)->default(0);
            
            $table->string('payment_id')->nullable(); // Razorpay ID
            $table->string('status')->default('completed');
            $table->string('type')->default('booking'); // booking, referral
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

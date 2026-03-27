<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->json('payload');
            $table->timestamps();

            $table->unique(['booking_id', 'doctor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_forms');
    }
};

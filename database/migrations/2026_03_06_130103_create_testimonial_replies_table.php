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
        Schema::create('testimonial_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Admin');
            $table->string('role')->nullable();
            $table->text('reply');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_replies');
    }
};

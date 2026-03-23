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
        Schema::create('practitioner_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practitioner_id');
            $table->integer('day_of_week')->nullable()->comment('0=Sunday, 6=Saturday');
            $table->date('specific_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('practitioner_id')->references('id')->on('practitioners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_availabilities');
    }
};

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
        Schema::create('coin_settings', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('currency_code', 10)->unique();
            $blueprint->decimal('coin_value', 15, 2)->default(0);
            $blueprint->string('status', 20)->default('active');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_settings');
    }
};

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
        Schema::create('promo_codes', function (Blueprint $col) {
            $col->id();
            $col->string('code')->unique();
            $col->enum('type', ['fixed', 'percentage']);
            $col->decimal('reward', 10, 2);
            $col->integer('usage_limit')->nullable();
            $col->integer('used_count')->default(0);
            $col->date('expiry_date')->nullable();
            $col->boolean('status')->default(true);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};

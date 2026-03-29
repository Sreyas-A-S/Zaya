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
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->string('usage_type')->default('booking')->after('type'); // registration, booking, both
            $table->text('description')->nullable()->after('reward');
            $table->json('benefits')->nullable()->after('description'); // For multiple benefits
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['usage_type', 'description', 'benefits']);
        });
    }
};

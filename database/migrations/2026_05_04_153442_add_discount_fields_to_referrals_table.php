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
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('promo_code')->nullable()->after('amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('promo_code');
            $table->integer('coins_used')->default(0)->after('discount_amount');
            $table->decimal('coin_discount', 10, 2)->default(0)->after('coins_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'discount_amount', 'coins_used', 'coin_discount']);
        });
    }
};

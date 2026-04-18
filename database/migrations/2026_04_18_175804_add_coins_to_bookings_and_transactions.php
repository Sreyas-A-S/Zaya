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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('coins_used')->default(0)->after('discount_amount');
            $table->decimal('coin_discount', 10, 2)->default(0.00)->after('coins_used');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('coins_used')->default(0)->after('referrer_commission_percent');
            $table->decimal('coin_discount', 10, 2)->default(0.00)->after('coins_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['coins_used', 'coin_discount']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['coins_used', 'coin_discount']);
        });
    }
};

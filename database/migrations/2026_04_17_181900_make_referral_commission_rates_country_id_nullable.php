<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        DB::statement('ALTER TABLE `referral_commission_rates` MODIFY `country_id` BIGINT UNSIGNED NULL');
        DB::statement('UPDATE `referral_commission_rates` SET `country_id` = NULL WHERE `country_id` = 0');

        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        DB::statement('DELETE FROM `referral_commission_rates` WHERE `country_id` IS NULL');
        DB::statement('ALTER TABLE `referral_commission_rates` MODIFY `country_id` BIGINT UNSIGNED NOT NULL');

        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });
    }
};


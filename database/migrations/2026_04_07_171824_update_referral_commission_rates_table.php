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
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->string('type', 20)->default('referral')->after('country_id');
            $table->string('referrer_role', 50)->nullable()->change();
            
            // Drop old unique index
            $table->dropUnique('rcr_country_ref_refd_unique');
            
            // New unique index
            $table->unique(['country_id', 'type', 'referrer_role', 'referred_role'], 'rcr_full_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropUnique('rcr_full_unique');
            
            $table->string('referrer_role', 50)->nullable(false)->change();
            $table->dropColumn('type');
            
            $table->unique(['country_id', 'referrer_role', 'referred_role'], 'rcr_country_ref_refd_unique');
        });
    }
};

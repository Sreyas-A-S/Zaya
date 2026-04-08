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
        // Step 1: add new columns/changes without touching the old unique index yet.
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->string('type', 20)->default('referral')->after('country_id');
            $table->string('referrer_role', 50)->nullable()->change();
        });

        // Step 2: ensure an index exists for the FK on country_id before dropping the composite unique index.
        // MySQL may be using the old unique index to satisfy the foreign key requirement.
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->index('country_id', 'rcr_country_id_idx');
        });

        // Step 3: swap unique indexes.
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropUnique('rcr_country_ref_refd_unique');
            $table->unique(['country_id', 'type', 'referrer_role', 'referred_role'], 'rcr_full_unique');
        });

        // Step 4: remove the temporary index (the new unique index covers country_id for the FK requirement).
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropIndex('rcr_country_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ensure country_id has an index while we drop the full unique (FK requirement).
        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->index('country_id', 'rcr_country_id_idx');
        });

        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropUnique('rcr_full_unique');

            $table->string('referrer_role', 50)->nullable(false)->change();
            $table->dropColumn('type');

            $table->unique(['country_id', 'referrer_role', 'referred_role'], 'rcr_country_ref_refd_unique');
        });

        Schema::table('referral_commission_rates', function (Blueprint $table) {
            $table->dropIndex('rcr_country_id_idx');
        });
    }
};

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
        Schema::create('referral_commission_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('referrer_role', 50);
            $table->string('referred_role', 50);
            $table->decimal('company_commission_percent', 5, 2)->default(0);
            $table->decimal('referrer_commission_percent', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['country_id', 'referrer_role', 'referred_role'], 'rcr_country_ref_refd_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_commission_rates');
    }
};


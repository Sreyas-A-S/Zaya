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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('client_id')->unique()->nullable()->after('user_id');
            $table->integer('age')->nullable()->after('dob');
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile_country_code')->nullable();
            $table->json('consultation_preferences')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->string('referral_type')->nullable();
            $table->string('referrer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'client_id',
                'age',
                'gender',
                'occupation',
                'address',
                'mobile_country_code',
                'consultation_preferences',
                'languages_spoken',
                'referral_type',
                'referrer_name'
            ]);
        });
    }
};

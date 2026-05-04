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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['practitioner_id']);
            $table->renameColumn('practitioner_id', 'profile_id');
            $table->string('practitioner_type')->after('booking_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('practitioner_type');
            $table->renameColumn('profile_id', 'practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

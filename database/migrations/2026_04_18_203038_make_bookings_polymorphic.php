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
            if (Schema::hasColumn('bookings', 'practitioner_id')) {
                $table->renameColumn('practitioner_id', 'profile_id');
            }
            if (!Schema::hasColumn('bookings', 'practitioner_type')) {
                $table->string('practitioner_type')->default('practitioner')->after('user_id');
            }
        });
        
        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['profile_id', 'practitioner_type'], 'booking_practitioner_polymorphic_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('booking_practitioner_polymorphic_index');
            $table->dropColumn('practitioner_type');
            $table->renameColumn('profile_id', 'practitioner_id');
        });
    }
};

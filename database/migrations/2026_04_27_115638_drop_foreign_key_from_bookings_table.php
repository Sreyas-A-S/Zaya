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
            // Drop the old foreign key that was originally on practitioner_id
            // Even though the column was renamed to profile_id, the constraint name often stays.
            // The user's error message explicitly mentions this constraint name.
            $table->dropForeign('bookings_practitioner_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add the constraint if needed, but since it's polymorphic now, 
            // we probably shouldn't have a hard FK to practitioners.
            // If we want to restore, it would be:
            // $table->foreign('profile_id')->references('id')->on('practitioners')->onDelete('cascade');
        });
    }
};

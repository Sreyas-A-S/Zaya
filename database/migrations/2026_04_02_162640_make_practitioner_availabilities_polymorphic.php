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
        Schema::table('practitioner_availabilities', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['practitioner_id']);
            
            // Add practitioner_type for polymorphism
            $table->string('practitioner_type')->default('practitioner')->after('practitioner_id');
            
            // Index the polymorphic columns
            $table->index(['practitioner_id', 'practitioner_type'], 'practitioner_availability_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practitioner_availabilities', function (Blueprint $table) {
            $table->dropIndex('practitioner_availability_index');
            $table->dropColumn('practitioner_type');
            
            // Re-add foreign key (might fail if data is inconsistent, but count is 0)
            $table->foreign('practitioner_id')->references('id')->on('practitioners')->onDelete('cascade');
        });
    }
};

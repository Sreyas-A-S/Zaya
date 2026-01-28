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
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->dropColumn('practitioner_type');
        });
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->json('practitioner_type')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->dropColumn('practitioner_type');
        });
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->string('practitioner_type')->nullable()->after('status');
        });
    }
};

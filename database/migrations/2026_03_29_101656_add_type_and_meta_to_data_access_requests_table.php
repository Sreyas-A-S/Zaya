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
        Schema::table('data_access_requests', function (Blueprint $table) {
            $table->string('type')->default('access')->after('client_id'); // access, referral
            $table->text('meta')->nullable()->after('type'); // store professional names for referral
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_access_requests', function (Blueprint $table) {
            $table->dropColumn(['type', 'meta']);
        });
    }
};

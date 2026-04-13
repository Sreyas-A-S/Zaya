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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('open_register_link_id')->nullable()->after('id');
            // Assuming open_register_links uses foreign id, but let's just make it nullable for now so it doesn't crash on drops
        });

        Schema::table('open_register_links', function (Blueprint $table) {
            if (!Schema::hasColumn('open_register_links', 'usage_count')) {
                $table->unsignedInteger('usage_count')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('open_register_link_id');
        });

        Schema::table('open_register_links', function (Blueprint $table) {
            if (Schema::hasColumn('open_register_links', 'usage_count')) {
                $table->dropColumn('usage_count');
            }
        });
    }
};

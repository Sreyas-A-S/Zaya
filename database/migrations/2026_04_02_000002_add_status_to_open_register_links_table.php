<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('open_register_links', function (Blueprint $table) {
            if (!Schema::hasColumn('open_register_links', 'status')) {
                $table->string('status', 20)->default('active')->after('token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('open_register_links', function (Blueprint $table) {
            if (Schema::hasColumn('open_register_links', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};


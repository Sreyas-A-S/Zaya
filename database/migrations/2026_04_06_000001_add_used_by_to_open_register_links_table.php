<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('open_register_links', function (Blueprint $table) {
            if (!Schema::hasColumn('open_register_links', 'used_by')) {
                $table->foreignId('used_by')
                    ->nullable()
                    ->after('used_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('open_register_links', function (Blueprint $table) {
            if (Schema::hasColumn('open_register_links', 'used_by')) {
                $table->dropConstrainedForeignId('used_by');
            }
        });
    }
};


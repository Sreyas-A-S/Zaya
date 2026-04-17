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
        if (!Schema::hasTable('doctors')) {
            return;
        }

        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'primary_institute')) {
                $table->string('primary_institute')->nullable()->after('primary_qualification_other');
            }
            if (!Schema::hasColumn('doctors', 'primary_year')) {
                $table->integer('primary_year')->nullable()->after('primary_institute');
            }
            if (!Schema::hasColumn('doctors', 'pg_institute')) {
                $table->string('pg_institute')->nullable()->after('post_graduation_other');
            }
            if (!Schema::hasColumn('doctors', 'pg_year')) {
                $table->integer('pg_year')->nullable()->after('pg_institute');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('doctors')) {
            return;
        }

        Schema::table('doctors', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('doctors', 'primary_institute')) {
                $drop[] = 'primary_institute';
            }
            if (Schema::hasColumn('doctors', 'primary_year')) {
                $drop[] = 'primary_year';
            }
            if (Schema::hasColumn('doctors', 'pg_institute')) {
                $drop[] = 'pg_institute';
            }
            if (Schema::hasColumn('doctors', 'pg_year')) {
                $drop[] = 'pg_year';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};


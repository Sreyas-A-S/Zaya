<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change status in testimonials table
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });

            // Update existing boolean statuses to strings
            DB::table('testimonials')->where('status', '1')->update(['status' => 'approved']);
            DB::table('testimonials')->where('status', '0')->update(['status' => 'pending']);
        }

        // Change status in testimonial_replies table
        if (Schema::hasTable('testimonial_replies')) {
            Schema::table('testimonial_replies', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });

            // Update existing boolean statuses to strings
            DB::table('testimonial_replies')->where('status', '1')->update(['status' => 'approved']);
            DB::table('testimonial_replies')->where('status', '0')->update(['status' => 'pending']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->boolean('status')->default(true)->change();
            });
        }

        if (Schema::hasTable('testimonial_replies')) {
            Schema::table('testimonial_replies', function (Blueprint $table) {
                $table->boolean('status')->default(true)->change();
            });
        }
    }
};

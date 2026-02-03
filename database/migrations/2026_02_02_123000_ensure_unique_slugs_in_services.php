<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $services = DB::table('services')->orderBy('id')->get();

        foreach ($services as $service) {
            // Generate slug from title
            $baseSlug = Str::slug($service->title);

            // If title is somehow empty, fallback to id or something (unlikely given validation)
            if (empty($baseSlug)) {
                $baseSlug = 'service-' . $service->id;
            }

            $slug = $baseSlug;
            $count = 1;

            // Ensure uniqueness
            while (DB::table('services')->where('slug', $slug)->where('id', '!=', $service->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            DB::table('services')->where('id', $service->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

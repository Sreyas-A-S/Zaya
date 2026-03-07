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
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('last_name');
        });

        // Backfill existing practitioners
        $practitioners = \App\Models\Practitioner::all();
        foreach ($practitioners as $practitioner) {
            $name = trim(($practitioner->first_name ?? '') . ' ' . ($practitioner->last_name ?? ''));
            if (empty($name)) {
                $name = $practitioner->user->name ?? 'practitioner-' . $practitioner->id;
            }
            
            $baseSlug = \Illuminate\Support\Str::slug($name);
            $slug = $baseSlug;
            $count = 1;
            
            while (\App\Models\Practitioner::where('slug', $slug)->where('id', '!=', $practitioner->id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            
            $practitioner->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

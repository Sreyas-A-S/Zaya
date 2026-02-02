<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Ayurveda',
            'Yoga',
            'Mindfulness',
            'Spiritual Guidance',
            'Eat Better',
            'Stress Management',
        ];

        foreach ($categories as $category) {
            \App\Models\ServiceCategory::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($category)],
                ['name' => $category]
            );
        }
    }
}

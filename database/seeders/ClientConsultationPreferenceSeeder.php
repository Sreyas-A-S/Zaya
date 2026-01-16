<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientConsultationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'General Consultation',
            'Diet & Nutrition',
            'Lifestyle Management',
            'Stress Management',
            'Mental Health',
            'Chronic Disease Management',
            'Detoxification',
            'Rejuvenation',
            'Women\'s Health',
            'Child Health',
        ];

        foreach ($data as $item) {
            \App\Models\ClientConsultationPreference::create([
                'name' => $item,
                'status' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YogaExpertiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expertises = [
            'Hatha Yoga',
            'Vinyasa Yoga',
            'Ashtanga Yoga',
            'Prenatal Yoga',
            'Postnatal Yoga',
            'Therapeutic Yoga',
            'Meditation',
            'Pranayama',
            'Yoga Nidra',
            'Restorative Yoga',
            'Kundalini Yoga',
            'Iyengar Yoga',
            'Power Yoga',
            'Yin Yoga',
            'Trauma-Informed Yoga',
            'Yoga for Seniors',
            'Yoga for Kids',
            'Corporate Yoga'
        ];

        foreach ($expertises as $expertise) {
            \App\Models\YogaExpertise::firstOrCreate(['name' => $expertise]);
        }
    }
}

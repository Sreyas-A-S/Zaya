<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Qualification;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qualifications = [
            'BAMS (Bachelor of Ayurvedic Medicine and Surgery)',
            'BHMS (Bachelor of Homeopathic Medicine and Surgery)',
            'BUMS (Bachelor of Unani Medicine and Surgery)',
            'MBBS (Bachelor of Medicine and Bachelor of Surgery)',
            'BNYS (Bachelor of Naturopathy and Yogic Sciences)',
            'MD (Ayurveda)',
            'MS (Ayurveda)',
            'BPT (Bachelor of Physiotherapy)',
            'BSc Nursing',
            'PhD in Ayurveda',
            'Diploma in Ayurveda',
            'other'
        ];

        foreach ($qualifications as $item) {
            Qualification::updateOrCreate(['name' => $item], ['status' => true]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PractitionerMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultations = ['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'];
        foreach ($consultations as $item) {
            \App\Models\WellnessConsultation::firstOrCreate(['name' => $item], ['status' => true]);
        }

        $therapies = ['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy', 'Others'];
        foreach ($therapies as $item) {
            \App\Models\BodyTherapy::firstOrCreate(['name' => $item], ['status' => true]);
        }

        $modalities = ['Yoga Sessions', 'Yoga Therapy', 'Ayurvedic Cooking'];
        foreach ($modalities as $item) {
            \App\Models\PractitionerModality::firstOrCreate(['name' => $item], ['status' => true]);
        }
    }
}

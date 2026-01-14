<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;
use App\Models\AyurvedaExpertise;
use App\Models\HealthCondition;
use App\Models\ExternalTherapy;

class DoctorMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'Kayachikitsa', 'Panchakarma', 'Shalya Tantra', 'Shalakya Tantra', 
            'Prasuti & Stri Roga', 'Kaumarabhritya', 'Agada Tantra', 'Swasthavritta', 'Yoga'
        ];

        foreach ($specializations as $item) {
            Specialization::updateOrCreate(['name' => $item], ['status' => true]);
        }

        $expertises = [
            'Prakriti Analysis', 'Vikriti Analysis', 'Samprapti Writing', 
            'Dosha Imbalance Correction Plans', 'Agni / Ama Assessment', 
            'Lifestyle & Dinacharya Planning', 'Ayurvedic Diet Planning'
        ];

        foreach ($expertises as $item) {
            AyurvedaExpertise::updateOrCreate(['name' => $item], ['status' => true]);
        }

        $conditions = [
            'Digestive Issues', 'Skin Issues', 'Joint Pains', 'PCOS / Menstrual Disorders', 
            'Thyroid Management Support', 'Diabetes / Metabolic Disorder Support', 
            'Stress / Anxiety / Sleep Issues', 'Weight Management', 'Hair Fall / Dandruff', 
            'Respiratory Issues', 'Sexual Wellness / Infertility Support', 'Chronic Disease Management'
        ];

        foreach ($conditions as $item) {
            HealthCondition::updateOrCreate(['name' => $item], ['status' => true]);
        }

        $therapies = [
            'Abhyanga', 'Shirodhara', 'Swedana', 'Udwartana', 'Pinda Sweda', 'Kati / Janu / Greeva Basti'
        ];

        foreach ($therapies as $item) {
            ExternalTherapy::updateOrCreate(['name' => $item], ['status' => true]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeederFrench extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Consultations
            ['title' => 'Conseiller en nutrition ayurvédique', 'slug' => 'ayurveda-nutrition-advisor-fr'],
            ['title' => 'Éducateur en Ayurvéda', 'slug' => 'ayurveda-educator-fr'],
            ['title' => 'Conseiller consultant en Ayurvéda', 'slug' => 'ayurveda-consultant-advisor-fr'],
            ['title' => 'Conseils sur le mode de vie', 'slug' => 'lifestyle-advice-fr'],
            
            // Therapies
            ['title' => 'Abhyanga', 'slug' => 'abhyanga-fr'],
            ['title' => 'Pindasweda', 'slug' => 'pindasweda-fr'],
            ['title' => 'Udwarthanam', 'slug' => 'udwarthanam-fr'],
            ['title' => 'Shirodhara', 'slug' => 'sirodhara-fr'],
            ['title' => 'Dhara complet du corps', 'slug' => 'full-body-dhara-fr'],
            ['title' => 'Lepam', 'slug' => 'lepam-fr'],
            ['title' => 'Gestion de la douleur', 'slug' => 'pain-management-fr'],
            ['title' => 'Soins du visage et de beauté', 'slug' => 'face-beauty-care-fr'],
            ['title' => 'Thérapie Marma', 'slug' => 'marma-therapy-fr'],
            
            // Other Modalities
            ['title' => 'Séances de yoga', 'slug' => 'yoga-sessions-fr'],
            ['title' => 'Yoga thérapie', 'slug' => 'yoga-therapy-fr'],
            ['title' => 'Cuisine ayurvédique', 'slug' => 'ayurvedic-cooking-fr'],

            // Original Main Services (for compatibility)
            ['title' => 'Ayurvéda et Panchakarma', 'slug' => 'ayurveda-panchakarma-fr'],
            ['title' => 'Guidance spirituelle', 'slug' => 'spiritual-guidance-fr'],
            ['title' => 'Conseil en pleine conscience', 'slug' => 'mindfulness-counselling-fr'],
        ];

        foreach ($services as $index => $s) {
            Service::updateOrCreate(
                ['title' => $s['title']],
                [
                    'slug' => $s['slug'],
                    'description' => 'Séance professionnelle de ' . $s['title'] . ' axée sur votre bien-être holistique.',
                    'status' => true,
                    'order_column' => $index + 1,
                    'image' => 'frontend/assets/ayurveda-and-panchakarma.png' // Default image
                ]
            );
        }
    }
}

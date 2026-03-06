<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Consultations
            ['title' => 'Ayurveda Nutrition Advisor', 'slug' => 'ayurveda-nutrition-advisor'],
            ['title' => 'Ayurveda Educator', 'slug' => 'ayurveda-educator'],
            ['title' => 'Ayurveda Consultant Advisor', 'slug' => 'ayurveda-consultant-advisor'],
            ['title' => 'Lifestyle Advice', 'slug' => 'lifestyle-advice'],
            
            // Therapies
            ['title' => 'Abhyanga', 'slug' => 'abhyanga'],
            ['title' => 'Pindasweda', 'slug' => 'pindasweda'],
            ['title' => 'Udwarthanam', 'slug' => 'udwarthanam'],
            ['title' => 'Sirodhara', 'slug' => 'sirodhara'],
            ['title' => 'Full Body Dhara', 'slug' => 'full-body-dhara'],
            ['title' => 'Lepam', 'slug' => 'lepam'],
            ['title' => 'Pain Management', 'slug' => 'pain-management'],
            ['title' => 'Face & Beauty Care', 'slug' => 'face-beauty-care'],
            ['title' => 'Marma Therapy', 'slug' => 'marma-therapy'],
            
            // Other Modalities
            ['title' => 'Yoga Sessions', 'slug' => 'yoga-sessions'],
            ['title' => 'Yoga Therapy', 'slug' => 'yoga-therapy'],
            ['title' => 'Ayurvedic Cooking', 'slug' => 'ayurvedic-cooking'],

            // Original Main Services (for compatibility)
            ['title' => 'Ayurveda & Panchakarma', 'slug' => 'ayurveda-panchakarma'],
            ['title' => 'Spiritual Guidance', 'slug' => 'spiritual-guidance'],
            ['title' => 'Mindfulness Counselling', 'slug' => 'mindfulness-counselling'],
        ];

        foreach ($services as $index => $s) {
            Service::updateOrCreate(
                ['title' => $s['title']],
                [
                    'slug' => $s['slug'],
                    'description' => 'Professional ' . $s['title'] . ' session focused on your holistic well-being.',
                    'status' => true,
                    'order_column' => $index + 1,
                    'image' => 'frontend/assets/ayurveda-and-panchakarma.png' // Default image
                ]
            );
        }
    }
}

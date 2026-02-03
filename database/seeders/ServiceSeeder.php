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
            [
                'title' => 'Ayurveda & Panchakarma',
                'image' => 'frontend/assets/ayurveda-and-panchakarma.png',
                'description' => 'Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized detoxification and rejuvenation.',
                'slug' => 'ayurveda-panchakarma',
                'status' => true,
                'order_column' => 1,
            ],
            [
                'title' => 'Yoga Therapy',
                'image' => 'frontend/assets/yoga-therapy.png',
                'description' => 'Yoga Therapy goes beyond flexibility. It is a clinical approach to healing that combines specific asanas, breathwork...',
                'slug' => 'yoga-therapy',
                'status' => true,
                'order_column' => 2,
            ],
            [
                'title' => 'Spiritual Guidance',
                'image' => 'frontend/assets/spiritual-guidance.png',
                'description' => 'Explore the deeper aspects of your existence. These sessions provide a safe space.',
                'slug' => 'spiritual-guidance',
                'status' => true,
                'order_column' => 3,
            ],
            [
                'title' => 'Mindfulness Counselling',
                'image' => 'frontend/assets/mindfulness-counselling.png',
                'description' => 'Cultivate a non-judgmental awareness of the present moment. Our sessions bridge traditional psychology...',
                'slug' => 'mindfulness-counselling',
                'status' => true,
                'order_column' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title' => $service['title']],
                $service
            );
        }
    }
}

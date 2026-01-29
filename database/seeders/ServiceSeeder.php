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
                'title' => 'Yoga Therapy',
                'image' => 'frontend/assets/service-yoga.png',
                'status' => true,
                'order_column' => 1,
            ],
            [
                'title' => 'Naturopathy',
                'image' => 'frontend/assets/service-naturopathy.png',
                'status' => true,
                'order_column' => 2,
            ],
            [
                'title' => 'Pranic Healing',
                'image' => 'frontend/assets/service-pranic.png',
                'status' => true,
                'order_column' => 3,
            ],
            [
                'title' => 'Massage Therapy',
                'image' => 'frontend/assets/massage-therapy.jpg',
                'status' => true,
                'order_column' => 4,
            ],
            [
                'title' => 'Hypnotherapy',
                'image' => 'frontend/assets/hypnotherapy.jpg',
                'status' => true,
                'order_column' => 5,
            ],
            [
                'title' => 'Graphotherapy',
                'image' => 'frontend/assets/graphotherapy.jpg',
                'status' => true,
                'order_column' => 6,
            ],
            [
                'title' => 'Sophrology',
                'image' => 'frontend/assets/sophrology.jpg',
                'status' => true,
                'order_column' => 7,
            ],
            [
                'title' => 'Life Coach',
                'image' => 'frontend/assets/life-coach.jpg',
                'status' => true,
                'order_column' => 8,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

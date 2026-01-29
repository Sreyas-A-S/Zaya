<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Hriday',
                'role' => 'Psychotherapy',
                'message' => "They didn't just give me supplements; they gave me a lifestyle shift. I'll definitely be booking a follow-up.",
                'rating' => 4,
                'status' => true,
            ],
            [
                'name' => 'Diya',
                'role' => 'Naturopathy',
                'message' => "They didn't just give me supplements; they gave me a lifestyle shift. I'll definitely be booking a follow-up.",
                'rating' => 5,
                'status' => true,
            ],
            [
                'name' => 'Aarohi',
                'role' => 'Yoga',
                'message' => "They didn't just give me supplements; they gave me a lifestyle shift. I'll definitely be booking a follow-up.",
                'rating' => 4,
                'status' => true,
            ],
            [
                'name' => 'Sanvi',
                'role' => 'Ayurveda',
                'message' => "The consultation was deep and insightful. I finally understand my body type and how to eat for my Dosha.",
                'rating' => 5,
                'status' => true,
            ],
            [
                'name' => 'Arjun',
                'role' => 'Wellness',
                'message' => "Extremely professional practitioners. The holistic approach they take is truly life-changing.",
                'rating' => 5,
                'status' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}

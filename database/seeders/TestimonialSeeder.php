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
                'status' => 'approved',
            ],
            [
                'name' => 'Diya',
                'role' => 'Naturopathy',
                'message' => "They didn't just give me supplements; they gave me a lifestyle shift. I'll definitely be booking a follow-up.",
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'name' => 'Aarohi',
                'role' => 'Yoga',
                'message' => "They didn't just give me supplements; they gave me a lifestyle shift. I'll definitely be booking a follow-up.",
                'rating' => 4,
                'status' => 'approved',
            ],
            [
                'name' => 'Sanvi',
                'role' => 'Ayurveda',
                'message' => "The consultation was deep and insightful. I finally understand my body type and how to eat for my Dosha.",
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'name' => 'Arjun',
                'role' => 'Wellness',
                'message' => "Extremely professional practitioners. The holistic approach they take is truly life-changing.",
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'name' => 'Lilly',
                'role' => 'Mental Health',
                'message' => "The guidance I received was exactly what I needed. It's rare to find such personalized care.",
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'name' => 'Rohan',
                'role' => 'Holistic Health',
                'message' => "Zaya Wellness has a unique way of connecting you with the right practitioner. Highly recommended.",
                'rating' => 4,
                'status' => 'approved',
            ],
            [
                'name' => 'Sara',
                'role' => 'Nutrition',
                'message' => "My energy levels have improved significantly since my consultation. The plan was very easy to follow.",
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'name' => 'Vikram',
                'role' => 'Yoga Therapy',
                'message' => "A very peaceful and professional environment. The therapists truly care about your well-being.",
                'rating' => 5,
                'status' => 'approved',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'language' => 'en',
                'question' => 'What is Ayurveda and how can it help me?',
                'answer' => 'Ayurveda is an ancient Indian system of medicine that emphasizes balancing mind, body, and spirit for optimal health. Our practitioners can create personalized wellness plans tailored to your unique constitution.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'How do I book a consultation online?',
                'answer' => 'You can book a consultation by browsing our practitioner directory, selecting a practitioner that matches your needs, and scheduling a session through their profile page.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'What types of practitioners are available?',
                'answer' => 'We have a diverse range of practitioners including Ayurvedic doctors, Yoga therapists, Mindfulness counselors, and Spiritual guides — all verified and experienced.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'Can I get a consultation from abroad?',
                'answer' => 'Yes! Zaya offers both in-person and online consultations, making it easy for clients from anywhere in the world to access our practitioner network.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'How do I join as a practitioner on Zaya?',
                'answer' => 'Register through our practitioner registration page, submit your qualifications and certifications, and our team will verify your profile before listing you on the platform.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'What conditions do your practitioners treat?',
                'answer' => 'Our practitioners address a wide range of conditions including stress, digestive disorders, skin issues, chronic pain, mental health challenges, and overall wellness optimization.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'Are your practitioners certified and verified?',
                'answer' => 'Absolutely. Every practitioner on Zaya goes through a thorough verification process to ensure they meet our quality standards and hold valid certifications.',
                'status' => true
            ],
            [
                'language' => 'en',
                'question' => 'How can I cancel or reschedule a session?',
                'answer' => 'You can manage your bookings through your account dashboard. Cancellations and rescheduling are available up to 24 hours before the scheduled session.',
                'status' => true
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}

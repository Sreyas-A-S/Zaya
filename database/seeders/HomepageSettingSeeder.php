<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomepageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'ZAYA: Embrace Wellness',
                'type' => 'text',
                'section' => 'hero'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Traditional Ayurveda for Modern Wellness',
                'type' => 'text',
                'section' => 'hero'
            ],
            [
                'key' => 'hero_button_text',
                'value' => 'Discover Our Story',
                'type' => 'text',
                'section' => 'hero'
            ],
            [
                'key' => 'hero_search_placeholder_1',
                'value' => 'Practitioners, Treatments...',
                'type' => 'text',
                'section' => 'hero'
            ],
            [
                'key' => 'hero_search_placeholder_2',
                'value' => 'City, Postal code...',
                'type' => 'text',
                'section' => 'hero'
            ],

            // Services Section
            [
                'key' => 'services_title',
                'value' => 'Our Services',
                'type' => 'text',
                'section' => 'services'
            ],
            [
                'key' => 'services_subtitle',
                'value' => 'Holistic Healing for Mind, Body & Soul',
                'type' => 'text',
                'section' => 'services'
            ],
            [
                'key' => 'services_description',
                'value' => 'Explore our specialized Ayurvedic treatments, transformative Yoga therapy and professional Mindfulness counseling. Connect with global experts dedicated to your wellness journey.',
                'type' => 'textarea',
                'section' => 'services'
            ],
            [
                'key' => 'services_button_text',
                'value' => 'Browse All Services',
                'type' => 'text',
                'section' => 'services'
            ],

            // Practitioners Section
            [
                'key' => 'practitioners_title',
                'value' => 'Practitioner Directory',
                'type' => 'text',
                'section' => 'practitioners'
            ],
            [
                'key' => 'practitioners_search_placeholder',
                'value' => 'Search practitioners...',
                'type' => 'text',
                'section' => 'practitioners'
            ],
            [
                'key' => 'practitioners_button_text',
                'value' => 'Book Now',
                'type' => 'text',
                'section' => 'practitioners'
            ],

            // CTA Section
            [
                'key' => 'cta_title',
                'value' => "Let's Embrace Wellness Together",
                'type' => 'text',
                'section' => 'cta'
            ],
            [
                'key' => 'cta_description',
                'value' => 'Connect with clients seeking authentic wellness. List your services, manage bookings and join a professional community of Ayurvedic and wellness experts.',
                'type' => 'textarea',
                'section' => 'cta'
            ],
            [
                'key' => 'cta_button_text',
                'value' => 'Join Our Team',
                'type' => 'text',
                'section' => 'cta'
            ],

            // Testimonials Section
            [
                'key' => 'testimonials_title',
                'value' => 'Real Stories of Healing',
                'type' => 'text',
                'section' => 'testimonials'
            ],
            [
                'key' => 'testimonials_badge',
                'value' => 'Testimonials',
                'type' => 'text',
                'section' => 'testimonials'
            ],
            [
                'key' => 'testimonials_subtitle',
                'value' => 'Discover how our personalized Ayurvedic consultations have helped our community find balance, vitality and lasting wellness.',
                'type' => 'textarea',
                'section' => 'testimonials'
            ],

            // Blog Section
            [
                'key' => 'blog_title',
                'value' => 'Wisdom Journal',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_subtitle',
                'value' => 'Your Guide to Ayurvedic Mastery',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_button_text',
                'value' => 'Explore Journal',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_description',
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te',
                'type' => 'textarea',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_image_main',
                'value' => 'frontend/assets/Eucalyptus-Essential-Oil.png',
                'type' => 'image',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_1_image',
                'value' => 'frontend/assets/bed-air.png',
                'type' => 'image',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_1_title',
                'value' => 'The Art of Resfull Sleep',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_1_read_time',
                'value' => '7 min Read',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_2_image',
                'value' => 'frontend/assets/ayurvedha-medicine.png',
                'type' => 'image',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_2_title',
                'value' => 'Morning Rituals for Energy',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_post_2_read_time',
                'value' => '15 min Read',
                'type' => 'text',
                'section' => 'blog'
            ],
            [
                'key' => 'blog_footer_text',
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te',
                'type' => 'textarea',
                'section' => 'blog'
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\HomepageSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

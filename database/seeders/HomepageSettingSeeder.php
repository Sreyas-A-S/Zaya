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
                'value' => 'Where Indian Wisdom Meets Modern Wellness',
                'type' => 'text',
                'section' => 'hero',
                'max_length' => 50
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Personalized wellness guided by experienced practitioners and trusted holistic experts.',
                'type' => 'text',
                'section' => 'hero',
                'max_length' => 100
            ],
            [
                'key' => 'hero_button_text',
                'value' => 'Discover Our Story',
                'type' => 'text',
                'section' => 'hero',
                'max_length' => 25
            ],
            [
                'key' => 'hero_search_placeholder_1',
                'value' => 'Practitioners, Treatments...',
                'type' => 'text',
                'section' => 'hero',
                'max_length' => 40
            ],
            [
                'key' => 'hero_search_placeholder_2',
                'value' => 'City, Postal code...',
                'type' => 'text',
                'section' => 'hero',
                'max_length' => 40
            ],

            // Services Section
            [
                'key' => 'services_title',
                'value' => 'Our Services',
                'type' => 'text',
                'section' => 'services',
                'max_length' => 40
            ],
            [
                'key' => 'services_subtitle',
                'value' => 'Holistic Healing for Mind, Body & Soul',
                'type' => 'text',
                'section' => 'services',
                'max_length' => 80
            ],
            [
                'key' => 'services_description',
                'value' => 'Explore our specialized Ayurvedic treatments, transformative Yoga therapy and professional Mindfulness counseling. Connect with global experts dedicated to your wellness journey.',
                'type' => 'textarea',
                'section' => 'services',
                'max_length' => 250
            ],
            [
                'key' => 'services_button_text',
                'value' => 'Browse All Services',
                'type' => 'text',
                'section' => 'services',
                'max_length' => 30
            ],

            // Practitioners Section
            [
                'key' => 'practitioners_title',
                'value' => 'Practitioner Directory',
                'type' => 'text',
                'section' => 'practitioners',
                'max_length' => 40
            ],
            [
                'key' => 'practitioners_search_placeholder',
                'value' => 'Search practitioners...',
                'type' => 'text',
                'section' => 'practitioners',
                'max_length' => 50
            ],
            [
                'key' => 'practitioners_button_text',
                'value' => 'Book Now',
                'type' => 'text',
                'section' => 'practitioners',
                'max_length' => 20
            ],

            // CTA Section
            [
                'key' => 'cta_title',
                'value' => "Let's Embrace Wellness Together",
                'type' => 'text',
                'section' => 'cta',
                'max_length' => 50
            ],
            [
                'key' => 'cta_description',
                'value' => 'Connect with clients seeking authentic wellness. List your services, manage bookings and join a professional community of Ayurvedic and wellness experts.',
                'type' => 'textarea',
                'section' => 'cta',
                'max_length' => 200
            ],
            [
                'key' => 'cta_button_text',
                'value' => 'Join Our Team',
                'type' => 'text',
                'section' => 'cta',
                'max_length' => 25
            ],

            // Testimonials Section
            [
                'key' => 'testimonials_title',
                'value' => 'Real Stories of Healing',
                'type' => 'text',
                'section' => 'testimonials',
                'max_length' => 40
            ],
            [
                'key' => 'testimonials_badge',
                'value' => 'Testimonials',
                'type' => 'text',
                'section' => 'testimonials',
                'max_length' => 20
            ],
            [
                'key' => 'testimonials_subtitle',
                'value' => 'Discover how our personalized Ayurvedic consultations have helped our community find balance, vitality and lasting wellness.',
                'type' => 'textarea',
                'section' => 'testimonials',
                'max_length' => 200
            ],

            // Blog Section
            [
                'key' => 'blog_title',
                'value' => 'Wisdom Journal',
                'type' => 'text',
                'section' => 'blog',
                'max_length' => 30
            ],
            [
                'key' => 'blog_subtitle',
                'value' => 'Your Guide to Ayurvedic Mastery',
                'type' => 'text',
                'section' => 'blog',
                'max_length' => 80
            ],
            [
                'key' => 'blog_button_text',
                'value' => 'Explore Journal',
                'type' => 'text',
                'section' => 'blog',
                'max_length' => 25
            ],
            [
                'key' => 'blog_description',
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te',
                'type' => 'textarea',
                'section' => 'blog',
                'max_length' => 150
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
                'section' => 'blog',
                'max_length' => 60
            ],
            [
                'key' => 'blog_post_1_read_time',
                'value' => '7 min Read',
                'type' => 'text',
                'section' => 'blog',
                'max_length' => 15
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
                'section' => 'blog',
                'max_length' => 60
            ],
            [
                'key' => 'blog_post_2_read_time',
                'value' => '15 min Read',
                'type' => 'text',
                'section' => 'blog',
                'max_length' => 15
            ],
            [
                'key' => 'blog_footer_text',
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te',
                'type' => 'textarea',
                'section' => 'blog',
                'max_length' => 150
            ],

            // About Us Section
            [
                'key' => 'about_title',
                'value' => 'About Us',
                'type' => 'text',
                'section' => 'about_page',
                'max_length' => 30
            ],
            [
                'key' => 'about_banner_image',
                'value' => 'frontend/assets/about-us-bg.png',
                'type' => 'image',
                'section' => 'about_page'
            ],
            [
                'key' => 'about_banner_title',
                'value' => "The Hearts and Minds\nBehind ZAYA",
                'type' => 'textarea',
                'section' => 'about_page',
                'max_length' => 80
            ],
            [
                'key' => 'about_banner_button_text',
                'value' => 'Meet Our Team',
                'type' => 'text',
                'section' => 'about_page',
                'max_length' => 25
            ],
            [
                'key' => 'about_description',
                'value' => 'ZAYA is more than a platform that is a bridge between traditional Ayurvedic wisdom and modern wellness. Meet the dedicated team working to empower practitioners and provide clients with a seamless path to holistic health.',
                'type' => 'textarea',
                'section' => 'about_page',
                'max_length' => 300
            ],
            [
                'key' => 'about_team_title',
                'value' => 'Meet the Team',
                'type' => 'text',
                'section' => 'about_page',
                'max_length' => 40
            ],
            [
                'key' => 'about_team_subtitle',
                'value' => 'The Visionaries Behind ZAYA Wellness',
                'type' => 'text',
                'section' => 'about_page',
                'max_length' => 80
            ],

            // Services Page Section
            [
                'key' => 'services_page_badge',
                'value' => 'Our Services',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 30
            ],
            [
                'key' => 'services_page_title',
                'value' => "Embrace Holistic \n Wellness",
                'type' => 'textarea',
                'section' => 'services_page',
                'max_length' => 60
            ],
            [
                'key' => 'services_page_subtitle',
                'value' => 'Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.',
                'type' => 'textarea',
                'section' => 'services_page',
                'max_length' => 150
            ],
            [
                'key' => 'services_page_description',
                'value' => 'ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in traditional Indian wisdom. Every service offered on our platform is provided by a practitioner whose background in Ayurveda, Yoga, or holistic health has been rigorously reviewed by our Approval Commission.',
                'type' => 'textarea',
                'section' => 'services_page',
                'max_length' => 400
            ],
            [
                'key' => 'services_page_image',
                'value' => 'frontend/assets/services-page-bg.png',
                'type' => 'image',
                'section' => 'services_page'
            ],
            // Stats
            [
                'key' => 'services_stat_1_count',
                'value' => '300',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 10
            ],
            [
                'key' => 'services_stat_1_label',
                'value' => 'Sessions Completed',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 30
            ],
            [
                'key' => 'services_stat_2_count',
                'value' => '50+',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 10
            ],
            [
                'key' => 'services_stat_2_label',
                'value' => 'Certified Practitioners',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 30
            ],
            [
                'key' => 'services_stat_3_count',
                'value' => '99%',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 10
            ],
            [
                'key' => 'services_stat_3_label',
                'value' => 'Positive Feedbacks',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 30
            ],
            [
                'key' => 'services_stat_4_count',
                'value' => '10',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 10
            ],
            [
                'key' => 'services_stat_4_label',
                'value' => 'Years of Tradition',
                'type' => 'text',
                'section' => 'services_page',
                'max_length' => 30
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

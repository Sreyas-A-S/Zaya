<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    //
    public function index()
    {
        $practitioners = \App\Models\Practitioner::with(['user', 'reviews'])
            ->latest()
            ->take(8)
            ->get();
        $testimonials = \App\Models\Testimonial::where('status', true)->latest()->get();
        $services = \App\Models\Service::where('status', true)->orderBy('order_column')->get();
        $settings = \App\Models\HomepageSetting::pluck('value', 'key');

        return view('index', compact('practitioners', 'testimonials', 'services', 'settings'));
    }

    public function comingSoon()
    {
        return view('coming-soon');
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function services()
    {
        $services = \App\Models\Service::where('status', true)->orderBy('order_column')->get();
        return view('services', compact('services'));
    }

    public function practitionerDetail($id)
    {
        $practitioner = \App\Models\Practitioner::with(['user', 'reviews'])->findOrFail($id);
        return view('practitioner-detail', compact('practitioner'));
    }

    public function zayaLogin()
    {
        return view('zaya-login');
    }

    public function clientRegister()
    {
        return view('client-register');
    }

    public function practitionerRegister()
    {
        return view('practitioner-register');
    }

    public function serviceDetail($slug)
    {
        // Services data array
        $services = [
            'ayurveda-panchakarma' => [
                'slug' => 'ayurveda-panchakarma',
                'title' => 'Ayurveda & Panchakarma',
                'image' => 'ayurveda-and-panchakarma.png',
                'description' => 'Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized detoxification and rejuvenation.',
            ],
            'yoga-therapy' => [
                'slug' => 'yoga-therapy',
                'title' => 'Yoga Therapy',
                'image' => 'yoga-therapy.png',
                'description' => 'Yoga Therapy goes beyond flexibility. It is a clinical approach to healing that combines specific asanas, breathwork...',
            ],
            'spiritual-guidance' => [
                'slug' => 'spiritual-guidance',
                'title' => 'Spiritual Guidance',
                'image' => 'spiritual-guidance.png',
                'description' => 'Connect with Authentic Guides and Spiritual Counselors.',
            ],
            'mindfulness-counselling' => [
                'slug' => 'mindfulness-counselling',
                'title' => 'Mindfulness Counselling',
                'image' => 'mindfulness-counselling.png',
                'description' => 'Evidence-Based Mindfulness for Emotional and Mental Balance.',
            ],
        ];

        // Check if service exists
        if (!isset($services[$slug])) {
            abort(404);
        }

        $service = $services[$slug];

        // Get other services (excluding current one)
        $otherServices = array_filter($services, function ($s) use ($slug) {
            return $s['slug'] !== $slug;
        });

        return view('service-detail', compact('service', 'otherServices'));
    }
}

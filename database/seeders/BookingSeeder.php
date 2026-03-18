<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Booking;
use App\Models\Practitioner;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'client@zaya.com')->first();
        if (!$user) return;

        $practitioner = Practitioner::first();
        if (!$practitioner) return;

        $services = Service::take(2)->pluck('id')->toArray();

        // Create some conference history (completed online sessions)
        for ($i = 1; $i <= 5; $i++) {
            Booking::create([
                'user_id' => $user->id,
                'practitioner_id' => $practitioner->id,
                'service_ids' => $services,
                'mode' => 'online',
                'booking_date' => now()->subDays($i * 7),
                'booking_time' => '10:00 AM',
                'total_price' => 50.00,
                'status' => 'completed',
                'razorpay_payment_id' => 'pay_' . \Illuminate\Support\Str::random(14),
                'recording_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            ]);
        }

        // Create some upcoming online sessions (no recording yet)
        for ($i = 1; $i <= 2; $i++) {
            Booking::create([
                'user_id' => $user->id,
                'practitioner_id' => $practitioner->id,
                'service_ids' => $services,
                'mode' => 'online',
                'booking_date' => now()->addDays($i * 7),
                'booking_time' => '11:00 AM',
                'total_price' => 50.00,
                'status' => 'pending',
                'razorpay_payment_id' => 'pay_' . \Illuminate\Support\Str::random(14),
            ]);
        }
    }
}

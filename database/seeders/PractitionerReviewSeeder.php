<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\PractitionerReview;

class PractitionerReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $practitioners = Practitioner::all();
        $clients = User::where('role', 'client')->get();

        if ($practitioners->isEmpty() || $clients->isEmpty()) {
            return;
        }

        $reviews = [
            "Excellent guidance and very professional!",
            "Had a great session, feeling much better.",
            "Highly recommend for anyone seeking holistic wellness.",
            "The practitioner was very knowledgeable and patient.",
            "A life-changing experience. Thank you!",
            "Very calming atmosphere and effective techniques.",
            "I've seen significant improvement in my health.",
            "Fantastic service and very easy to book.",
            "Genuine expertise and compassionate care.",
            "Wonderful approach to traditional healing."
        ];

        foreach ($practitioners as $practitioner) {
            // Create 2-3 reviews for each practitioner
            $reviewCount = rand(2, 4);

            for ($i = 0; $i < $reviewCount; $i++) {
                PractitionerReview::create([
                    'practitioner_id' => $practitioner->id,
                    'user_id' => $clients->random()->id,
                    'rating' => rand(4, 5), // Let's keep ratings positive for now
                    'review' => $reviews[array_rand($reviews)],
                    'status' => 1,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}

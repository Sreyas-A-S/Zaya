<?php

namespace Database\Seeders;

use App\Models\ReferralCommissionRate;
use Illuminate\Database\Seeder;

class GlobalReferralCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'practitioner',
            'doctor',
            'yoga_therapist',
            'mindfulness_practitioner',
        ];

        foreach ($roles as $role) {
            // Global Direct Booking (Scenario 1)
            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => 0,
                    'type' => 'direct',
                    'referred_role' => $role,
                    'referrer_role' => null,
                ],
                [
                    'company_commission_percent' => 10.00,
                    'referrer_commission_percent' => 0,
                ]
            );

            // Global Referral Booking (Scenario 2)
            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => 0,
                    'type' => 'referral',
                    'referred_role' => $role,
                    'referrer_role' => 'practitioner',
                ],
                [
                    'company_commission_percent' => 0,
                    'referrer_commission_percent' => 5.00,
                ]
            );
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ReferralCommissionRate;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;

class ReferralCommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:other-fees-view')->only('index');
        $this->middleware('permission:other-fees-edit')->only('update', 'setCountry');
    }

    public function index(Request $request)
    {
        $countries = Country::where('status', 'active')->orderBy('name')->get();
        
        $adminCountryCode = session('admin_country', 'all');
        $countryCode = ($adminCountryCode === 'all') ? 'all' : strtoupper($adminCountryCode);
        
        $currentCountry = null;
        if ($countryCode !== 'all') {
            $currentCountry = Country::where('code', $countryCode)->first();
        }

        $targetCountry = $currentCountry;
        // The UI uses `0` as "Global", but the DB stores global rows with `country_id` = NULL.
        $countryId = $targetCountry ? $targetCountry->id : 0;
        $countryIdForQuery = ($countryId === 0) ? null : $countryId;

        $roles = [
            'practitioner' => 'Practitioner',
            'doctor' => 'Doctor',
            'yoga_therapist' => 'Yoga Therapist',
            'mindfulness_practitioner' => 'Mindfulness Counsellor',
        ];

        // Load specific rates for the selected country
        $rates = ReferralCommissionRate::when(
            $countryIdForQuery === null,
            fn ($q) => $q->whereNull('country_id'),
            fn ($q) => $q->where('country_id', $countryIdForQuery)
        )->get();
        $directRates = $rates->where('type', 'direct')->keyBy('referred_role');
        $referralRates = $rates->where('type', 'referral')
            ->where('referrer_role', 'practitioner')
            ->keyBy('referred_role');

        // ALWAYS Load global fallback rates (country_id IS NULL)
        $globalRates = ReferralCommissionRate::whereNull('country_id')->get();
        $globalDirectRates = $globalRates->where('type', 'direct')->keyBy('referred_role');
        $globalReferralRates = $globalRates->where('type', 'referral')
            ->where('referrer_role', 'practitioner')
            ->keyBy('referred_role');

        $isSuperAdmin = auth()->user()->role === 'super-admin';

        return view('admin.referral-commissions.index', compact(
            'countries', 'countryId', 'roles', 'directRates', 'referralRates', 
            'globalDirectRates', 'globalReferralRates',
            'countryCode', 'targetCountry', 'isSuperAdmin'
        ));
    }

    /**
     * Set the active country in session and return its rates
     */
    public function setCountry(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required' // Can be 0 for Global
        ]);

        $countryId = (int) $validated['country_id'];
        session(['admin_commission_country_id' => $countryId]);

        $rates = ($countryId === 0)
            ? ReferralCommissionRate::whereNull('country_id')->get()
            : ReferralCommissionRate::where('country_id', $countryId)->get();
        
        $directRates = $rates->where('type', 'direct')->mapWithKeys(function($item) {
            return [$item->referred_role => $item->company_commission_percent];
        });

        $referralRates = $rates->where('type', 'referral')
            ->where('referrer_role', 'practitioner')
            ->mapWithKeys(function($item) {
                return [$item->referred_role => [
                    'company' => $item->company_commission_percent,
                    'referrer' => $item->referrer_commission_percent
                ]];
            });

        return response()->json([
            'success' => true,
            'country_id' => $countryId,
            'direct_rates' => $directRates,
            'referral_rates' => $referralRates
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required',
            'direct_rates' => 'required|array',
            'direct_rates.*.referred_role' => 'required|string|max:50',
            'direct_rates.*.company_commission_percent' => 'required|numeric|min:0|max:100',
            'referral_rates' => 'required|array',
            'referral_rates.*.referred_role' => 'required|string|max:50',
            'referral_rates.*.company_commission_percent' => 'required|numeric|min:0|max:100',
            'referral_rates.*.referrer_commission_percent' => 'required|numeric|min:0|max:100',
            
            // Optional global rates
            'global_direct_rates' => 'nullable|array',
            'global_referral_rates' => 'nullable|array',
        ]);

        $countryId = (int) $validated['country_id'];
        $countryIdForSave = ($countryId === 0) ? null : $countryId;
        $isSuperAdmin = auth()->user()->role === 'super-admin';

        // 1. Save Country-Specific Rates
        foreach ($validated['direct_rates'] as $index => $rateData) {
            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => $countryIdForSave,
                    'type' => 'direct',
                    'referred_role' => $rateData['referred_role'],
                    'referrer_role' => null,
                ],
                ['company_commission_percent' => (float) $rateData['company_commission_percent']]
            );
        }

        foreach ($validated['referral_rates'] as $index => $rateData) {
            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => $countryIdForSave,
                    'type' => 'referral',
                    'referrer_role' => 'practitioner',
                    'referred_role' => $rateData['referred_role'],
                ],
                [
                    'company_commission_percent' => (float) $rateData['company_commission_percent'],
                    'referrer_commission_percent' => (float) $rateData['referrer_commission_percent'],
                ]
            );
        }

        // 2. Save Global Fallback Rates (ONLY if Super Admin)
        if ($isSuperAdmin && $request->has('global_direct_rates')) {
            foreach ($request->global_direct_rates as $rateData) {
                ReferralCommissionRate::updateOrCreate(
                    ['country_id' => null, 'type' => 'direct', 'referred_role' => $rateData['referred_role'], 'referrer_role' => null],
                    ['company_commission_percent' => (float) $rateData['company_commission_percent']]
                );
            }
        }
        if ($isSuperAdmin && $request->has('global_referral_rates')) {
            foreach ($request->global_referral_rates as $rateData) {
                ReferralCommissionRate::updateOrCreate(
                    ['country_id' => null, 'type' => 'referral', 'referred_role' => $rateData['referred_role'], 'referrer_role' => 'practitioner'],
                    [
                        'company_commission_percent' => (float) $rateData['company_commission_percent'],
                        'referrer_commission_percent' => (float) $rateData['referrer_commission_percent']
                    ]
                );
            }
        }

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Commission settings updated successfully.'])
            : redirect()->route('admin.referral-commissions.index')
                ->with('success', 'Commission settings updated successfully.');
    }
}

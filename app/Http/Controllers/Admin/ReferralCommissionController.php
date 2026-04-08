<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ReferralCommissionRate;
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
        
        // Get country from session, fallback to first country
        $countryId = session('admin_commission_country_id');
        
        if (!$countryId || !$countries->contains('id', $countryId)) {
            $countryId = $countries->first()->id ?? 0;
            session(['admin_commission_country_id' => $countryId]);
        }

        $roles = [
            'practitioner' => 'Practitioner',
            'doctor' => 'Doctor',
            'yoga_therapist' => 'Yoga Therapist',
            'mindfulness_practitioner' => 'Mindfulness Counsellor',
        ];

        $rates = ReferralCommissionRate::where('country_id', $countryId)->get();
        
        $directRates = $rates->where('type', 'direct')->keyBy('referred_role');
        $referralRates = $rates->where('type', 'referral')
            ->where('referrer_role', 'practitioner')
            ->keyBy('referred_role');

        return view('admin.referral-commissions.index', compact('countries', 'countryId', 'roles', 'directRates', 'referralRates'));
    }

    /**
     * Set the active country in session and return its rates
     */
    public function setCountry(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $countryId = $validated['country_id'];
        session(['admin_commission_country_id' => $countryId]);

        $rates = ReferralCommissionRate::where('country_id', $countryId)->get();
        
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
            'country_id' => 'required|exists:countries,id',
            'direct_rates' => 'required|array',
            'direct_rates.*.referred_role' => 'required|string|max:50',
            'direct_rates.*.company_commission_percent' => 'required|numeric|min:0|max:100',
            'referral_rates' => 'required|array',
            'referral_rates.*.referred_role' => 'required|string|max:50',
            'referral_rates.*.company_commission_percent' => 'required|numeric|min:0|max:100',
            'referral_rates.*.referrer_commission_percent' => 'required|numeric|min:0|max:100',
        ]);

        $countryId = $validated['country_id'];

        // Save Direct Booking Rates
        foreach ($validated['direct_rates'] as $index => $rateData) {
            $company = (float) $rateData['company_commission_percent'];
            
            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => $countryId,
                    'type' => 'direct',
                    'referred_role' => $rateData['referred_role'],
                    'referrer_role' => null,
                ],
                [
                    'company_commission_percent' => $company,
                    'referrer_commission_percent' => 0,
                ]
            );
        }

        // Save Referral Booking Rates
        foreach ($validated['referral_rates'] as $index => $rateData) {
            $company = (float) $rateData['company_commission_percent'];
            $referrer = (float) $rateData['referrer_commission_percent'];
            
            if (($company + $referrer) > 100.0) {
                $roleLabel = str_replace('_', ' ', $rateData['referred_role']);
                return back()->withErrors([
                    "referral_rates.$index" => "Total commission (Zaya + Referrer) for referral to $roleLabel cannot exceed 100%.",
                ])->withInput();
            }

            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => $countryId,
                    'type' => 'referral',
                    'referrer_role' => 'practitioner',
                    'referred_role' => $rateData['referred_role'],
                ],
                [
                    'company_commission_percent' => $company,
                    'referrer_commission_percent' => $referrer,
                ]
            );
        }

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Commission rates updated successfully.'])
            : redirect()->route('admin.referral-commissions.index')
                ->with('success', 'Commission rates updated successfully.');
    }
}

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
        $this->middleware('permission:other-fees-edit')->only('update');
    }

    public function index(Request $request)
    {
        $countries = Country::where('status', 'active')->orderBy('name')->get();
        $countryId = (int) ($request->query('country_id') ?: ($countries->first()->id ?? 0));

        $roles = [
            'practitioner' => 'Practitioner',
            'doctor' => 'Doctor',
            'yoga_therapist' => 'Yoga Therapist',
            'mindfulness_practitioner' => 'Mindfulness Counsellor',
        ];

        $rates = ReferralCommissionRate::where('country_id', $countryId)->get()
            ->keyBy(fn ($r) => $r->referrer_role . '>' . $r->referred_role);

        return view('admin.referral-commissions.index', compact('countries', 'countryId', 'roles', 'rates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'rates' => 'required|array',
            'rates.*.referrer_role' => 'required|string|max:50',
            'rates.*.referred_role' => 'required|string|max:50',
            'rates.*.company_commission_percent' => 'required|numeric|min:0|max:100',
            'rates.*.referrer_commission_percent' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['rates'] as $rateData) {
            $company = (float) $rateData['company_commission_percent'];
            $referrer = (float) $rateData['referrer_commission_percent'];
            if (($company + $referrer) > 100.0) {
                return back()->withErrors([
                    'rates' => 'Company + Referrer commission cannot exceed 100%.',
                ])->withInput();
            }

            ReferralCommissionRate::updateOrCreate(
                [
                    'country_id' => $validated['country_id'],
                    'referrer_role' => $rateData['referrer_role'],
                    'referred_role' => $rateData['referred_role'],
                ],
                [
                    'company_commission_percent' => $company,
                    'referrer_commission_percent' => $referrer,
                ]
            );
        }

        return redirect()
            ->route('admin.referral-commissions.index', ['country_id' => $validated['country_id']])
            ->with('success', 'Referral commission rates updated successfully.');
    }
}


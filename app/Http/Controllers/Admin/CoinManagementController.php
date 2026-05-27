<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinSetting;
use App\Models\Country;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CoinManagementController extends Controller
{
    public function index(Request $request)
    {
        $adminCountry = session('admin_country', 'all');
        $currencyCode = config('currencies.default', 'INR');
        $selectedCountry = trim((string) $request->get('country', ''));

        if ($selectedCountry === '' && $adminCountry !== 'all') {
            $selectedCountry = $this->resolveCountryDisplay($adminCountry);
        }

        if ($adminCountry !== 'all') {
            $map = config('currencies.country_to_currency', []);
            $currencyCode = $map[strtoupper($adminCountry)] ?? $currencyCode;
        }

        if ($request->ajax()) {
            $users = User::query()
                ->with('patient:id,user_id,country')
                ->where('role', 'client');

            $this->applyCountryFilter($users, $selectedCountry);

            return DataTables::of($users)
                ->addColumn('country', function($user) {
                    return $user->patient->country ?? 'N/A';
                })
                ->addColumn('action', function($user) {
                    return '<button class="btn btn-sm btn-info editCoins" data-id="'.$user->id.'" data-coins="'.$user->coins.'">Update Coins</button>';
                })
                ->make(true);
        }

        $coinSetting = CoinSetting::where('currency_code', $currencyCode)->first();
        $symbols = config('currencies.symbols', []);
        $symbol = $symbols[$currencyCode] ?? $currencyCode;
        $countryOptions = Patient::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country')
            ->values();

        if ($selectedCountry !== '' && !$countryOptions->contains(function ($country) use ($selectedCountry) {
            return strcasecmp((string) $country, $selectedCountry) === 0;
        })) {
            $countryOptions = $countryOptions->push($selectedCountry)->sort()->values();
        }

        return view('admin.finance.coins.index', compact('coinSetting', 'currencyCode', 'symbol', 'countryOptions', 'selectedCountry'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string',
            'coin_value' => 'required|numeric|min:0',
            'referral_coins' => 'required|integer|min:0',
        ]);

        CoinSetting::updateOrCreate(
            ['currency_code' => $request->currency_code],
            ['coin_value' => $request->coin_value, 'referral_coins' => $request->referral_coins]
        );

        return back()->with('success', 'Coin settings updated successfully for ' . $request->currency_code);
    }

    public function updateUsersCoins(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'coins' => 'required|integer|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->coins = $request->coins;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User coins updated successfully.']);
    }

    protected function applyCountryFilter(Builder $users, ?string $country): void
    {
        $candidates = $this->resolveCountryCandidates($country);

        if (empty($candidates)) {
            return;
        }

        $users->whereHas('patient', function (Builder $query) use ($candidates) {
            $query->where(function (Builder $countryQuery) use ($candidates) {
                foreach ($candidates as $candidate) {
                    $countryQuery->orWhereRaw('LOWER(country) = ?', [strtolower($candidate)]);
                }
            });
        });
    }

    protected function resolveCountryDisplay(?string $country): string
    {
        $country = trim((string) $country);
        if ($country === '' || strtolower($country) === 'all') {
            return '';
        }

        $match = Country::query()
            ->whereRaw('LOWER(code) = ?', [strtolower($country)])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($country)])
            ->first();

        return $match?->name ?? $country;
    }

    protected function resolveCountryCandidates(?string $country): array
    {
        $country = trim((string) $country);
        if ($country === '' || strtolower($country) === 'all') {
            return [];
        }

        $candidates = collect([
            $country,
            strtoupper($country),
            ucwords(strtolower($country)),
        ]);

        $match = Country::query()
            ->whereRaw('LOWER(code) = ?', [strtolower($country)])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($country)])
            ->first();

        if ($match) {
            $candidates->push($match->code, strtoupper($match->code), $match->name);
        }

        return $candidates
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->unique(fn ($value) => strtolower((string) $value))
            ->values()
            ->all();
    }
}

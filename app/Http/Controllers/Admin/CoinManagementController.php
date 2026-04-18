<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CoinManagementController extends Controller
{
    public function index(Request $request)
    {
        $adminCountry = session('admin_country', 'all');
        $currencyCode = config('currencies.default', 'INR');

        if ($adminCountry !== 'all') {
            $map = config('currencies.country_to_currency', []);
            $currencyCode = $map[strtoupper($adminCountry)] ?? $currencyCode;
        }

        if ($request->ajax()) {
            $users = User::query()->where('role', 'client');

            if ($adminCountry !== 'all') {
                $users->whereHas('patient', function($query) use ($adminCountry) {
                    $query->where('country', strtoupper($adminCountry));
                });
            }

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

        return view('admin.finance.coins.index', compact('coinSetting', 'currencyCode', 'symbol'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string',
            'coin_value' => 'required|numeric|min:0',
        ]);

        CoinSetting::updateOrCreate(
            ['currency_code' => $request->currency_code],
            ['coin_value' => $request->coin_value]
        );

        return back()->with('success', 'Coin value updated successfully for ' . $request->currency_code);
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
}

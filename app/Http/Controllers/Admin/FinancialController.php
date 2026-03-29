<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    /**
     * Display a listing of transactions and global balance.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with(['user', 'practitioner', 'booking', 'referral'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('practitioner_name', function ($row) {
                    return $row->practitioner->name ?? 'N/A';
                })
                ->editColumn('total_amount', function ($row) {
                    return $row->currency . ' ' . number_format($row->total_amount, 2);
                })
                ->editColumn('company_share', function ($row) {
                    return $row->currency . ' ' . number_format($row->company_share, 2) . ' (' . $row->company_commission_percent . '%)';
                })
                ->editColumn('practitioner_share', function ($row) {
                    return $row->currency . ' ' . number_format($row->practitioner_share, 2);
                })
                ->editColumn('referrer_share', function ($row) {
                    if ($row->type === 'referral') {
                        return $row->currency . ' ' . number_format($row->referrer_share, 2) . ' (' . $row->referrer_commission_percent . '%)';
                    }
                    return 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('M d, Y H:i');
                })
                ->make(true);
        }

        // Global Balances (simplified, assuming one currency for now or summing them)
        $balances = Transaction::select('currency', 
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('SUM(company_share) as total_company'),
            DB::raw('SUM(practitioner_share) as total_practitioners'),
            DB::raw('SUM(referrer_share) as total_referrers')
        )->groupBy('currency')->get();

        return view('admin.financial.index', compact('balances'));
    }

    /**
     * View individual practitioner balances.
     */
    public function practitionerBalances(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereIn('role', ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])
                ->select('users.id', 'users.name', 'users.role', 'users.email')
                ->withSum('practitionerTransactions as total_earned', 'practitioner_share')
                ->withSum('referrerTransactions as referral_earnings', 'referrer_share');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('total_balance', function($row) {
                    $total = ($row->total_earned ?? 0) + ($row->referral_earnings ?? 0);
                    return 'INR ' . number_format($total, 2);
                })
                ->make(true);
        }

        return view('admin.financial.practitioners');
    }
}

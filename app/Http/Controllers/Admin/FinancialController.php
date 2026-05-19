<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class FinancialController extends Controller
{
    /**
     * Display a listing of transactions and global balance.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with(['user', 'practitioner', 'booking', 'referral'])->latest();

            if ($request->filled('type_filter')) {
                $data->where('type', $request->type_filter);
            }

            if ($request->filled('user_filter')) {
                $data->where('user_id', $request->user_filter);
            }

            // Calculate dynamic filtered balances without inheriting order clauses from latest()
            $balancesQuery = Transaction::query();
            if ($request->filled('type_filter')) {
                $balancesQuery->where('type', $request->type_filter);
            }
            if ($request->filled('user_filter')) {
                $balancesQuery->where('user_id', $request->user_filter);
            }
            $filteredBalances = $balancesQuery->select('currency', 
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(company_share) as total_company'),
                DB::raw('SUM(practitioner_share) as total_practitioners'),
                DB::raw('SUM(referrer_share) as total_referrers')
            )->groupBy('currency')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('practitioner_name', function ($row) {
                    if ($row->type === 'registration') return 'Zaya Wellness (Reg Fee)';
                    return $row->practitioner->name ?? 'N/A';
                })
                ->editColumn('type', function($row) {
                    $class = match($row->type) {
                        'booking' => 'primary',
                        'referral' => 'success',
                        'registration' => 'info',
                        default => 'dark'
                    };
                    return '<span class="badge badge-'.$class.'">'.ucfirst($row->type).'</span>';
                })
                ->editColumn('total_amount', function ($row) {
                    return $row->currency . ' ' . number_format($row->total_amount, 2);
                })
                ->editColumn('company_share', function ($row) {
                    return $row->currency . ' ' . number_format($row->company_share, 2) . ' (' . (float)$row->company_commission_percent . '%)';
                })
                ->editColumn('practitioner_share', function ($row) {
                    if ($row->type === 'registration') return 'N/A';
                    return $row->currency . ' ' . number_format($row->practitioner_share, 2);
                })
                ->editColumn('referrer_share', function ($row) {
                    if ($row->type === 'referral' || ($row->referrer_id && $row->referrer_share > 0)) {
                        return $row->currency . ' ' . number_format($row->referrer_share, 2) . ' (' . (float)$row->referrer_commission_percent . '%)';
                    }
                    return 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('M d, Y H:i');
                })
                ->addColumn('action', function ($row) {
                    $btns = '<div class="d-flex align-items-center gap-3">';
                    $btns .= '<a href="' . route('admin.financial.show', $row->id) . '" class="text-primary" title="View Details">
                                <i class="iconly-Show icli" style="font-size: 20px;"></i>
                             </a>';
                    $btns .= '<a href="' . route('admin.financial.download', $row->id) . '" class="text-secondary" title="Download PDF">
                                <i class="fa fa-file-pdf-o" style="font-size: 18px;"></i>
                             </a>';
                    $btns .= '</div>';
                    return $btns;
                })
                ->rawColumns(['action', 'type'])
                ->with('balances', $filteredBalances)
                ->make(true);
        }

        // Global Balances (simplified, assuming one currency for now or summing them)
        $balances = Transaction::select('currency', 
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('SUM(company_share) as total_company'),
            DB::raw('SUM(practitioner_share) as total_practitioners'),
            DB::raw('SUM(referrer_share) as total_referrers')
        )->groupBy('currency')->get();

        $users = User::whereIn('id', Transaction::select('user_id')->distinct()->whereNotNull('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.financial.index', compact('balances', 'users'));
    }

    /**
     * Export transactions as Excel.
     */
    public function export(Request $request)
    {
        $type = $request->input('type_filter');
        $userId = $request->input('user_filter');

        return Excel::download(new TransactionsExport($type, $userId), 'transactions.xlsx');
    }

    /**
     * Display the specified transaction details.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'practitioner', 'referrer', 'booking', 'referral', 'country'])->findOrFail($id);
        
        $services = collect();
        $serviceIds = [];
        if ($transaction->booking && $transaction->booking->service_ids) {
            $serviceIds = is_array($transaction->booking->service_ids) ? $transaction->booking->service_ids : json_decode($transaction->booking->service_ids, true);
        } elseif ($transaction->referral && $transaction->referral->service_ids) {
            $serviceIds = is_array($transaction->referral->service_ids) ? $transaction->referral->service_ids : json_decode($transaction->referral->service_ids, true);
        }

        if (!empty($serviceIds)) {
            $services = Service::whereIn('id', $serviceIds)->get();
        }

        return view('admin.financial.show', compact('transaction', 'services'));
    }

    /**
     * Export transaction as PDF.
     */
    public function downloadPdf($id)
    {
        $transaction = Transaction::with(['user', 'practitioner', 'referrer', 'booking', 'referral', 'country'])->findOrFail($id);
        
        $services = collect();
        $serviceIds = [];
        if ($transaction->booking && $transaction->booking->service_ids) {
            $serviceIds = is_array($transaction->booking->service_ids) ? $transaction->booking->service_ids : json_decode($transaction->booking->service_ids, true);
        } elseif ($transaction->referral && $transaction->referral->service_ids) {
            $serviceIds = is_array($transaction->referral->service_ids) ? $transaction->referral->service_ids : json_decode($transaction->referral->service_ids, true);
        }

        if (!empty($serviceIds)) {
            $services = Service::whereIn('id', $serviceIds)->get();
        }

        $pdf = Pdf::loadView('admin.financial.pdf', compact('transaction', 'services'));
        return $pdf->download('Transaction-' . $transaction->transaction_no . '.pdf');
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

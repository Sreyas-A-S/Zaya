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
use Illuminate\Database\Eloquent\Builder;

class FinancialController extends Controller
{
    /**
     * Display a listing of transactions and global balance.
     */
    public function index(Request $request)
    {
        $countryCode = session('admin_country', 'all');
        if (!$countryCode) $countryCode = 'all';

        if ($request->ajax()) {
            if ($countryCode === 'all' && !$request->filled('currency_filter')) {
                return DataTables::of(Transaction::query()->whereRaw('1 = 0'))
                    ->addIndexColumn()
                    ->with('overview', [])
                    ->make(true);
            }

            $data = Transaction::with(['user', 'practitioner', 'booking', 'referral'])->latest();
            $this->applyTransactionFilters($data, $request);

            $overviewQuery = Transaction::query();
            $this->applyTransactionFilters($overviewQuery, $request);
            $filteredBalances = $this->buildOverviewMetrics($overviewQuery);

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
                ->with('overview', $filteredBalances)
                ->make(true);
        }

        $overview = [];
        if ($countryCode !== 'all') {
            $overviewQuery = Transaction::query();
            $this->applyTransactionFilters($overviewQuery, $request);
            $overview = $this->buildOverviewMetrics($overviewQuery);
        }

        $userRoles = collect([
            'doctor',
            'practitioner',
            'mindfulness_practitioner',
            'yoga_therapist',
            'translator',
        ]);

        $yearsQuery = Transaction::query();
        $this->applyTransactionFilters($yearsQuery, $request);
        $years = $yearsQuery
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => now()->startOfYear()->month($month)->format('F')];
        });

        return view('admin.financial.index', compact('overview', 'userRoles', 'years', 'months'));
    }

    /**
     * Export transactions as Excel.
     */
    public function export(Request $request)
    {
        $type = $request->input('type_filter');
        $userId = $request->input('user_filter');
        $month = $request->input('month_filter');
        $year = $request->input('year_filter');
        $currency = $request->input('currency_filter');

        return Excel::download(new TransactionsExport($type, $userId, $month, $year, $currency), 'transactions.xlsx');
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

    protected function applyTransactionFilters(Builder $query, Request $request): void
    {
        $countryCode = session('admin_country', 'all');
        if ($countryCode && $countryCode !== 'all') {
            $query->whereHas('country', function ($q) use ($countryCode) {
                $q->where('code', strtoupper($countryCode));
            });
        } elseif ($countryCode === 'all' && $request->filled('currency_filter')) {
            $query->where('currency', $request->currency_filter);
        }

        if ($request->filled('type_filter')) {
            $query->where('type', $request->type_filter);
        }

        if ($request->filled('user_filter')) {
            $selectedRole = $request->user_filter;
            $query->where(function ($subQuery) use ($selectedRole) {
                $subQuery->whereHas('user', function ($roleQuery) use ($selectedRole) {
                    $roleQuery->where('role', $selectedRole);
                })->orWhereHas('practitioner', function ($roleQuery) use ($selectedRole) {
                    $roleQuery->where('role', $selectedRole);
                })->orWhereHas('referrer', function ($roleQuery) use ($selectedRole) {
                    $roleQuery->where('role', $selectedRole);
                });
            });
        }

        if ($request->filled('month_filter')) {
            $query->whereMonth('created_at', (int) $request->month_filter);
        }

        if ($request->filled('year_filter')) {
            $query->whereYear('created_at', (int) $request->year_filter);
        }
    }

    protected function buildOverviewMetrics(Builder $query): array
    {
        $rows = $query
            ->select(
                'currency',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(company_share) as total_company'),
                DB::raw('SUM(practitioner_share) as total_practitioners')
            )
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        $formatMetric = function (string $key) use ($rows) {
            return $rows->map(function ($row) use ($key) {
                return [
                    'currency' => $row->currency,
                    'amount' => (float) ($row->{$key} ?? 0),
                ];
            })->values()->all();
        };

        return [
            [
                'title' => 'Total Revenue',
                'icon' => 'database',
                'color' => 'primary',
                'amounts' => $formatMetric('total_revenue'),
            ],
            [
                'title' => 'Company Share',
                'icon' => 'briefcase',
                'color' => 'secondary',
                'amounts' => $formatMetric('total_company'),
            ],
            [
                'title' => 'Practitioner Share',
                'icon' => 'user-check',
                'color' => 'success',
                'amounts' => $formatMetric('total_practitioners'),
            ],
        ];
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\MonthlyRevenueReportMail;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMonthlyRevenueReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:monthly-revenue {--month= : The month to report for (1-12)} {--year= : The year to report for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly revenue reports to practitioners for the previous month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Default to previous month
        $reportDate = Carbon::now()->subMonth();
        
        $month = $this->option('month') ? (int)$this->option('month') : $reportDate->month;
        $year = $this->option('year') ? (int)$this->option('year') : $reportDate->year;
        
        $monthName = Carbon::create($year, $month, 1)->format('F');
        
        $this->info("Generating revenue reports for {$monthName} {$year}...");

        // Get all unique practitioners who had transactions in that period
        $practitionerIds = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'paid')
            ->pluck('practitioner_id')
            ->merge(
                Transaction::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('status', 'paid')
                    ->whereNotNull('referrer_id')
                    ->pluck('referrer_id')
            )
            ->unique()
            ->filter();

        if ($practitionerIds->isEmpty()) {
            $this->warn("No paid transactions found for {$monthName} {$year}.");
            return;
        }

        $count = 0;
        foreach ($practitionerIds as $practitionerId) {
            $practitioner = User::find($practitionerId);
            if (!$practitioner || empty($practitioner->email)) continue;

            // Calculate metrics
            $transactions = Transaction::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', 'paid')
                ->where(function($q) use ($practitionerId) {
                    $q->where('practitioner_id', $practitionerId)
                      ->orWhere('referrer_id', $practitionerId);
                })
                ->get();

            $practitionerEarnings = $transactions->where('practitioner_id', $practitionerId)->sum('practitioner_share');
            $referralEarnings = $transactions->where('referrer_id', $practitionerId)->sum('referrer_share');
            $totalNetEarnings = $practitionerEarnings + $referralEarnings;
            
            if ($totalNetEarnings <= 0) continue;

            $totalGrossRevenue = $transactions->where('practitioner_id', $practitionerId)->sum('total_amount');
            $totalServices = $transactions->where('practitioner_id', $practitionerId)->count();
            
            // Get currency (assume first transaction's currency or default)
            $currency = $transactions->first()->currency ?? 'USD';
            $currencySymbol = get_currency_symbol($currency);

            $reportData = [
                'practitioner_earnings' => $practitionerEarnings,
                'referral_earnings' => $referralEarnings,
                'total_net_earnings' => $totalNetEarnings,
                'total_gross_revenue' => $totalGrossRevenue,
                'total_services' => $totalServices,
                'currency' => $currency,
                'currency_symbol' => $currencySymbol,
            ];

            try {
                Mail::to($practitioner->email)->send(new MonthlyRevenueReportMail($practitioner, $reportData, $monthName, $year));
                $this->line("Sent report to: {$practitioner->email}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to send to {$practitioner->email}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} revenue reports.");
    }
}

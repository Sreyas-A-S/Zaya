<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type;
    protected $userId;
    protected $month;
    protected $year;

    public function __construct($type = null, $userId = null, $month = null, $year = null)
    {
        $this->type = $type;
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transaction::with(['user', 'practitioner', 'referrer'])->latest();

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->userId) {
            $selectedRole = $this->userId;
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

        if ($this->month) {
            $query->whereMonth('created_at', (int) $this->month);
        }

        if ($this->year) {
            $query->whereYear('created_at', (int) $this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Transaction No',
            'Date',
            'Type',
            'Client Name',
            'Practitioner Name',
            'Currency',
            'Total Amount',
            'Company Share',
            'Practitioner Share',
            'Referrer Share',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        $clientName = $transaction->user->name ?? 'N/A';
        $practitionerName = $transaction->type === 'registration' ? 'Zaya Wellness (Reg Fee)' : ($transaction->practitioner->name ?? 'N/A');

        return [
            $transaction->id,
            $transaction->transaction_no,
            $transaction->created_at->format('M d, Y H:i'),
            ucfirst($transaction->type),
            $clientName,
            $practitionerName,
            $transaction->currency,
            number_format($transaction->total_amount, 2),
            number_format($transaction->company_share, 2),
            $transaction->type === 'registration' ? 'N/A' : number_format($transaction->practitioner_share, 2),
            $transaction->type === 'referral' || ($transaction->referrer_id && $transaction->referrer_share > 0) ? number_format($transaction->referrer_share, 2) : 'N/A',
            ucfirst($transaction->status),
        ];
    }
}

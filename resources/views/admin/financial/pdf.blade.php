<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Details - {{ $transaction->transaction_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500;700&display=swap');
        
        body { 
            font-family: 'Roboto', 'Helvetica', sans-serif; 
            font-size: 11px; 
            color: #4B5563; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        
        .page-wrapper {
            padding: 20px 40px;
        }

        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding-bottom: 15px;
            position: relative;
        }

        .header:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #F8E0BB 50%, transparent 100%);
        }
        
        .logo {
            height: 60px;
            margin-bottom: 5px;
        }

        .report-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: #97563D;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            background-color: #FEFFF3;
            color: #2E4B3C;
            border: 1px solid #2E4B3C;
            margin-top: 8px;
            letter-spacing: 1px;
        }

        .section { 
            margin-bottom: 20px; 
        }
        
        .section-title { 
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            font-weight: 700; 
            color: #2E4B3C;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .section-title:after {
            content: "";
            flex-grow: 1;
            height: 1px;
            background-color: #F8E0BB;
            margin-left: 10px;
        }
        
        .data-table { 
            width: 100%; 
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #F8E0BB;
        }
        
        .data-table th, .data-table td { 
            text-align: left; 
            padding: 8px 12px; 
            border-bottom: 1px solid #F8E0BB; 
        }
        
        .data-table th { 
            background-color: #FFF7EF; 
            color: #97563D;
            font-weight: 600;
            width: 35%; 
        }

        .data-table tr:last-child th, .data-table tr:last-child td {
            border-bottom: none;
        }

        .summary-box {
            background-color: #FEFFF3;
            border-radius: 12px;
            padding: 15px 20px;
            border: 1px solid #F8E0BB;
            margin-bottom: 20px;
        }

        .summary-grid {
            width: 100%;
        }

        .summary-grid td {
            padding: 6px 0;
            border-bottom: 1px dashed #F8E0BB;
        }

        .summary-grid tr:last-child td {
            border-bottom: none;
        }

        .label {
            color: #6B7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            color: #1F2937;
            font-weight: 600;
            text-align: right;
            font-size: 12px;
        }

        .value-primary {
            color: #97563D;
            font-size: 15px;
        }

        .participant-container {
            display: table;
            width: 100%;
            margin-top: 5px;
            border-spacing: 10px 0;
            margin-left: -10px;
        }

        .participant-card {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
        }

        .participant-inner {
            background-color: #ffffff;
            border: 1px solid #F8E0BB;
            border-radius: 10px;
            padding: 10px 12px;
            min-height: 65px;
        }

        .participant-role {
            font-size: 8px;
            text-transform: uppercase;
            color: #97563D;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .participant-name {
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            color: #2E4B3C;
            font-weight: 700;
            line-height: 1.2;
        }

        .footer { 
            text-align: center; 
            font-size: 9px; 
            color: #9CA3AF; 
            margin-top: 30px; 
            padding-top: 15px;
            border-top: 1px solid #F8E0BB; 
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(248, 224, 187, 0.1);
            z-index: -1;
            font-family: 'Playfair Display', serif;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="watermark">ZAYA WELLNESS</div>

    <div class="page-wrapper">
        <div class="header">
            <img src="https://demo.zayawellness.com/frontend/assets/zaya-logo.svg" class="logo" alt="Zaya Logo">
            <h1 class="report-title">Financial Transaction</h1>
            <div class="status-pill">{{ strtoupper($transaction->status) }}</div>
        </div>

        <div class="section" style="margin-bottom: 15px;">
            <div class="section-title">Revenue Breakdown</div>
            <div class="summary-box">
                <table class="summary-grid">
                    <tr>
                        <td class="label">Total Paid by Client</td>
                        <td class="value value-primary">{{ $transaction->currency }} {{ number_format($transaction->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Company Revenue ({{ number_format($transaction->company_commission_percent, 1) }}%)</td>
                        <td class="value" style="color: #2E4B3C;">{{ $transaction->currency }} {{ number_format($transaction->company_share, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Specialist Professional Fee</td>
                        <td class="value">{{ $transaction->currency }} {{ number_format($transaction->practitioner_share, 2) }}</td>
                    </tr>
                    @if($transaction->referrer_id)
                    <tr>
                        <td class="label">Referral Commission ({{ number_format($transaction->referrer_commission_percent, 1) }}%)</td>
                        <td class="value">{{ $transaction->currency }} {{ number_format($transaction->referrer_share, 2) }}</td>
                    </tr>
                    @endif
                    @if($transaction->coin_discount > 0)
                    <tr>
                        <td class="label">Zaya Coins Applied</td>
                        <td class="value" style="color: #9CA3AF;">- {{ $transaction->currency }} {{ number_format($transaction->coin_discount, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="section" style="margin-bottom: 15px;">
            <div class="section-title">Reference Details</div>
            <table class="data-table">
                <tr>
                    <th>Reference Number</th>
                    <td style="font-weight: 700; color: #97563D;">{{ $transaction->transaction_no }}</td>
                </tr>
                <tr>
                    <th>Transaction Type</th>
                    <td>{{ ucfirst($transaction->type) }} Session</td>
                </tr>
                <tr>
                    <th>Payment Processor ID</th>
                    <td><code>{{ $transaction->payment_id ?: 'N/A' }}</code></td>
                </tr>
                <tr>
                    <th>Execution Date</th>
                    <td>{{ $transaction->created_at->format('F d, Y') }} at {{ $transaction->created_at->format('h:i A') }}</td>
                </tr>
            </table>
        </div>

        @if($services->count() > 0)
        <div class="section" style="margin-bottom: 15px;">
            <div class="section-title">Included Services</div>
            <div style="background-color: #FFF7EF; border-radius: 8px; padding: 5px 15px; border: 1px solid #F8E0BB;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($services as $service)
                    <li style="padding: 6px 0; border-bottom: 1px solid #F8E0BB; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #1F2937;">{{ $service->title }}</span>
                        <span style="font-size: 10px; color: #97563D; text-transform: uppercase;">{{ $service->category->name ?? 'Service' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Participant Summary</div>
            <div class="participant-container">
                <div class="participant-card">
                    <div class="participant-inner">
                        <div class="participant-role">Client</div>
                        <div class="participant-name">{{ $transaction->user->name ?? 'N/A' }}</div>
                        <div style="font-size: 9px; color: #6B7280; margin-top: 2px;">{{ $transaction->user->email ?? '' }}</div>
                    </div>
                </div>
                <div class="participant-card">
                    <div class="participant-inner">
                        <div class="participant-role">Specialist</div>
                        <div class="participant-name">{{ $transaction->practitioner->name ?? 'N/A' }}</div>
                        <div style="font-size: 9px; color: #6B7280; margin-top: 2px;">{{ ucfirst($transaction->practitioner->role ?? 'Expert') }}</div>
                    </div>
                </div>
                @if($transaction->referrer)
                <div class="participant-card">
                    <div class="participant-inner">
                        <div class="participant-role">Referrer</div>
                        <div class="participant-name">{{ $transaction->referrer->name ?? 'N/A' }}</div>
                        <div style="font-size: 9px; color: #6B7280; margin-top: 2px;">{{ ucfirst($transaction->referrer->role ?? 'Referrer') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p style="font-weight: 600; color: #97563D; margin-bottom: 3px;">ZAYA WELLNESS &middot; EMBRACE WELLNESS</p>
            <p style="margin: 0;">This document serves as an official financial record of the session transaction processed through our secure gateway.</p>
            <p style="margin-top: 8px;">&copy; {{ date('Y') }} Zaya Wellness. All rights reserved. | www.zayawellness.com</p>
        </div>
    </div>
</body>
</html>

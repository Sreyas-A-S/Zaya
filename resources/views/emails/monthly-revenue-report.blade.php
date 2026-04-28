<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #2E4B3D;
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            color: #2E4B3D;
            margin-bottom: 10px;
        }
        .summary-box {
            background-color: #F9FBF9;
            border: 1px solid #2E4B3D12;
            border-radius: 16px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .revenue-amount {
            font-size: 36px;
            font-weight: 900;
            color: #2E4B3D;
            margin: 10px 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 50%;
            padding: 10px;
        }
        .stat-label {
            font-size: 10px;
            font-weight: 800;
            color: #8F8F8F;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .details-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 800;
            color: #8F8F8F;
            text-transform: uppercase;
            padding: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .details-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f8f8f8;
            font-size: 14px;
        }
        .footer {
            background-color: #f9fbf9;
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #8F8F8F;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2E4B3D;
            color: #ffffff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Monthly Report</h1>
            <p style="margin-top: 10px; opacity: 0.8;">{{ $month }} {{ $year }}</p>
        </div>
        <div class="content">
            <p class="greeting">Hello, {{ $practitioner->name }}!</p>
            <p>Your performance report for the month of <strong>{{ $month }}</strong> is ready. Here is a summary of your earnings through Zaya Wellness.</p>

            <div class="summary-box">
                <p class="stat-label">Total Net Earnings</p>
                <p class="revenue-amount">{{ $reportData['currency_symbol'] }}{{ number_format($reportData['total_net_earnings'], 2) }}</p>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <p class="stat-label">Total Services</p>
                        <p class="stat-value">{{ $reportData['total_services'] }}</p>
                    </div>
                    <div class="stat-item">
                        <p class="stat-label">Total Gross</p>
                        <p class="stat-value">{{ $reportData['currency_symbol'] }}{{ number_format($reportData['total_gross_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            <h3 style="font-size: 14px; text-transform: uppercase; color: #8F8F8F; letter-spacing: 1px;">Revenue Breakdown</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Earnings Type</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Practitioner Revenue</td>
                        <td style="text-align: right;">{{ $reportData['currency_symbol'] }}{{ number_format($reportData['practitioner_earnings'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Referral Commission</td>
                        <td style="text-align: right;">{{ $reportData['currency_symbol'] }}{{ number_format($reportData['referral_earnings'], 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 40px;">
                <p style="font-size: 14px; color: #666;">For a detailed view of all transactions, please visit your dashboard.</p>
                <a href="{{ url('/dashboard') }}" class="btn">View Dashboard</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Zaya Wellness. All rights reserved.</p>
            <p>You received this email because you are a registered practitioner on our platform.</p>
        </div>
    </div>
</body>
</html>

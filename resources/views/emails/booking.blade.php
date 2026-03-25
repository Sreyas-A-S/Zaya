<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Booking Update' }}</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .accent-bar { height: 6px; background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C); }
        .header { padding: 40px 20px; text-align: center; }
        .logo { height: 60px; }
        .content { padding: 0 40px 40px; text-align: left; }
        h1 { color: #2E4B3C; font-size: 24px; margin-bottom: 16px; font-weight: 700; }
        p { font-size: 16px; line-height: 1.6; margin-bottom: 24px; color: #4B5563; }
        .details-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 32px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; }
        .detail-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #64748b; font-size: 14px; }
        .value { color: #1e293b; font-size: 14px; font-weight: 700; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 14px; }
        .footer a { color: #F8E0BB; text-decoration: none; }
        .button { display: inline-block; padding: 12px 24px; background-color: #97563D; color: #ffffff !important; border-radius: 99px; text-decoration: none; font-weight: 600; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            @include('emails.partials.logo')
        </div>

        <div class="content">
            <h1>{{ $title }}</h1>
            <p>{{ $intro }}</p>

            <div class="details-card">
                <div class="detail-row">
                    <span class="label">Invoice No:</span>
                    <span class="value">{{ $booking->invoice_no }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Date:</span>
                    <span class="value">{{ $booking->booking_date->format('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Time:</span>
                    <span class="value">{{ $booking->booking_time }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Mode:</span>
                    <span class="value">{{ strtoupper($booking->mode) }}</span>
                </div>
                @if($type === 'client')
                <div class="detail-row">
                    <span class="label">Practitioner:</span>
                    <span class="value">{{ $booking->practitioner->user->name }}</span>
                </div>
                @else
                <div class="detail-row">
                    <span class="label">Client:</span>
                    <span class="value">{{ $booking->user->name }}</span>
                </div>
                @endif

                @if($booking->need_translator)
                <div class="detail-row">
                    <span class="label">Translator:</span>
                    <span class="value">{{ $booking->translator->user->name ?? 'Assigned' }} ({{ $booking->from_language }} &rarr; {{ $booking->to_language }})</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="label">Total Amount:</span>
                    <span class="value">€ {{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                @if($type === 'client')
                    <a href="{{ route('invoice.show', $booking->invoice_no) }}" class="button">View Online Invoice</a>
                @else
                    <a href="{{ route('admin.invoices.index') }}" class="button">View Invoices List</a>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="{{ config('app.url') }}">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

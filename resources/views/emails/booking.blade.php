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
        h1 { color: #2E4B3C; font-size: 18px; margin-bottom: 12px; font-weight: 700; }
        p { font-size: 13px; line-height: 1.6; margin-bottom: 20px; color: #4B5563; }
        .details-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 8px 0; border-bottom: 1px solid #edf2f7; vertical-align: top; word-break: break-word; }
        .details-table tr:last-child td { border-bottom: none; }
        .label { font-weight: 600; color: #64748b; font-size: 11px; width: 35%; }
        .value { color: #1e293b; font-size: 12px; font-weight: 700; text-align: right; width: 65%; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 13px; }
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
                <table class="details-table">
                    <tr>
                        <td class="label">Invoice No:</td>
                        <td class="value">{{ $booking->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date:</td>
                        <td class="value">{{ $booking->booking_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Mode:</td>
                        <td class="value">{{ strtoupper($booking->mode) }}</td>
                    </tr>

                    @php
                        $sessions = $booking->additional_info['sessions'] ?? [];
                        $hasMultipleSessions = count($sessions) > 1;
                    @endphp

                    @if($booking->mode === 'online')
                        @if($hasMultipleSessions)
                            @foreach($sessions as $session)
                                @php
                                    $serviceName = \App\Models\Service::find($session['service_id'])->title ?? 'Session ' . ($loop->iteration);
                                    $sessionTime = $session['time'] ?? $booking->booking_time;
                                    $sessionDate = !empty($session['day']) && $session['day'] !== 'Day' ? $session['day'] : $booking->booking_date->format('M d, Y');
                                @endphp
                                <tr>
                                    <td class="label">{{ $serviceName }} ({{ $sessionTime }} {{ $timezone ?? 'UTC' }}):</td>
                                    <td class="value"><a href="{{ route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']) }}" style="color: #2E4B3C;">Join Session</a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="label">Meeting Link ({{ $booking->booking_time }} {{ $timezone ?? 'UTC' }}):</td>
                                <td class="value"><a href="{{ route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']) }}" style="color: #2E4B3C;">Click here to join</a></td>
                            </tr>
                        @endif
                    @endif
                    
                    @if($booking->referral && $booking->referral->referredBy)
                    <tr>
                        <td class="label">Referral From:</td>
                        <td class="value">{{ $booking->referral->referredBy->name }}</td>
                    </tr>
                    @endif

                    @if($type === 'client')
                    <tr>
                        <td class="label">Practitioner:</td>
                        <td class="value">{{ $booking->practitioner->user->name }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="label">Client:</td>
                        <td class="value">{{ $booking->user->name }}</td>
                    </tr>
                    @endif

                    @if($booking->need_translator)
                    <tr>
                        <td class="label">Translator:</td>
                        <td class="value">{{ $booking->translator->user->name ?? 'Assigned' }} ({{ $booking->from_language }} &rarr; {{ $booking->to_language }})</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">Total Amount:</td>
                        <td class="value">{{ $currencySymbol }} {{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center;">
                @if($type === 'client' && $booking->invoice_no)
                    <a href="{{ route('invoice.show', $booking->invoice_no) }}" class="button">View Online Invoice</a>
                @endif
            </div>
        </div>

        <div class="footer">
            <p style="color: #F8E0BB; margin-bottom: 10px;">&copy; {{ date('Y') }} Zaya Wellness. All rights reserved.</p>
            <p style="color: #ffffff; font-weight: 500; opacity: 0.9;">Where Indian Wisdom Meets Modern Wellness</p>
            <p style="margin-top: 20px;"><a href="{{ config('app.url') }}" style="color: #F8E0BB; text-decoration: none; border: 1px solid #F8E0BB; padding: 8px 20px; border-radius: 99px; font-size: 12px;">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

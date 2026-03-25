<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Referral Invitation</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .accent-bar { height: 6px; background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C); }
        .header { padding: 40px 20px; text-align: center; }
        .logo { height: 60px; }
        .content { padding: 0 40px 40px; text-align: left; }
        h1 { color: #2E4B3C; font-size: 22px; margin-bottom: 16px; font-weight: 700; }
        p { font-size: 16px; line-height: 1.6; margin-bottom: 24px; color: #4B5563; }
        .referral-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 32px; }
        .practitioner-info { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .avatar { width: 50px; height: 50px; border-radius: 50%; background-color: #e2e8f0; object-cover: cover; }
        .label { font-weight: 600; color: #64748b; font-size: 13px; text-uppercase: true; }
        .value { color: #1e293b; font-size: 15px; font-weight: 700; display: block; margin-top: 2px; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 14px; }
        .footer a { color: #F8E0BB; text-decoration: none; }
        .button { display: inline-block; padding: 16px 32px; background-color: #2FA749; color: #ffffff !important; border-radius: 99px; text-decoration: none; font-weight: 700; font-size: 16px; margin-top: 10px; text-align: center; width: 100%; box-sizing: border-box; }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            @include('emails.partials.logo')
        </div>

        <div class="content">
            <h1>Expert Session Recommendation</h1>
            <p>Hello {{ $referral->user->name }},</p>
            <p>Your practitioner, <strong>{{ $referral->referredBy->name }}</strong>, has referred you to <strong>{{ $referral->referredTo->name }}</strong> for a specialized session. They believe this will be beneficial for your wellness journey.</p>

            <div class="referral-card">
                <div class="practitioner-info">
                    <div>
                        <span class="label">Referred To:</span>
                        <span class="value">{{ $referral->referredTo->name }}</span>
                    </div>
                </div>
                <div style="margin-top: 15px; border-top: 1px solid #edf2f7; pt: 15px;">
                    <span class="label">Session Amount:</span>
                    <span class="value">€ {{ number_format($referral->amount, 2) }}</span>
                </div>
            </div>

            <p>To confirm this referral and schedule your session, please complete the payment using the secure link below:</p>

            <a href="{{ $payUrl }}" class="button">Accept Referral & Pay</a>
            
            <p style="font-size: 13px; color: #94a3b8; margin-top: 30px;">
                Reference ID: {{ $referral->referral_no }}
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="{{ config('app.url') }}">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

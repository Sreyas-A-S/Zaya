<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Access OTP</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .accent-bar { height: 6px; background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C); }
        .header { padding: 40px 20px; text-align: center; }
        .logo { height: 60px; }
        .content { padding: 0 40px 40px; text-align: center; }
        h1 { color: #2E4B3C; font-size: 22px; margin-bottom: 16px; font-weight: 700; }
        p { font-size: 16px; line-height: 1.6; margin-bottom: 24px; color: #4B5563; }
        .otp-box { background-color: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 30px; margin: 32px 0; }
        .otp-code { font-size: 42px; font-weight: 800; color: #2E4B3C; letter-spacing: 10px; margin: 0; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 14px; }
        .footer a { color: #F8E0BB; text-decoration: none; }
        .warning { font-size: 13px; color: #ef4444; margin-top: 20px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            @include('emails.partials.logo')
        </div>

        <div class="content">
            <h1>Data Access Verification</h1>
            <p>Pracitioner <strong>{{ $requesterName }}</strong> has requested temporary access to your profile, health data, and session recordings to better assist you.</p>
            
            <p>If you wish to grant this access, please provide the following One-Time Password (OTP) to your practitioner:</p>

            <div class="otp-box">
                <h2 class="otp-code">{{ $otp }}</h2>
            </div>

            <p class="warning">If you did not expect this request, please ignore this email and do not share the OTP with anyone.</p>
            
            <p style="font-size: 14px; color: #94a3b8;">This OTP will expire in 15 minutes.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="{{ config('app.url') }}">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #FFF7EF;
            margin: 0;
            padding: 0;
            color: #4B5563;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #F8E0BB;
        }
        .header {
            background-color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            height: 60px;
            width: auto;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            color: #97563D;
            font-size: 28px;
            margin-bottom: 24px;
            font-weight: 700;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .otp-container {
            background: linear-gradient(135deg, #FFF7EF 0%, #F8E0BB 100%);
            padding: 30px;
            border-radius: 16px;
            margin: 32px 0;
            border: 1px solid #F8E0BB;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #97563D;
            margin: 0;
        }
        .footer {
            background-color: #2E4B3C;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #F8E0BB;
            text-decoration: none;
        }
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C);
        }
        .expiration {
            font-size: 14px;
            color: #6B7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            <img src="{{ url('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="logo">
        </div>
        
        <div class="content">
            <h1>Password Reset OTP</h1>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <div class="otp-container">
                <p style="margin-bottom: 10px; font-weight: 600; color: #2E4B3C; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Your Secure OTP</p>
                <h2 class="otp-code">{{ $otp }}</h2>
            </div>
            
            <p class="expiration">This OTP will expire in 5 minutes for your security.</p>
            
            <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="{{ config('app.url') }}">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

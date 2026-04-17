<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Registration Link' }}</title>
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
            height: 72px;
            width: auto;
            display: block;
            margin: 6px auto;
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
        .button-container {
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background: #97563D;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
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
            margin-top: 20px;
        }
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
            
            <p>Hello,</p>
            
            <p>{{ $intro }}</p>

            <div class="button-container">
                <a href="{{ $link }}" class="button">
                    Complete Registration
                </a>
            </div>

            @if(!empty($outro))
                <p class="expiration">{{ $outro }}</p>
            @endif
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="{{ config('app.url') }}">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>

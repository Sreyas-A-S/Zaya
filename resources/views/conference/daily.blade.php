<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Session | Daily.co | Zaya Wellness</title>
    <script src="https://unpkg.com/@daily-co/daily-js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #07110B;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        #daily-container {
            width: 100%;
            height: 100%;
        }
        .back-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: rgba(7, 17, 11, 0.8);
            backdrop-blur: 10px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 100;
            border-bottom: 1px solid rgba(46, 75, 61, 0.2);
            transition: transform 0.3s ease;
        }
        .back-bar.hidden {
            transform: translateY(-100%);
        }
        .back-link {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .back-link:hover {
            opacity: 1;
        }
        .room-info {
            margin-left: auto;
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="back-bar" id="back-bar">
        <a href="{{ route('conferences.index') }}" class="back-link">
            <i class="ri-arrow-left-line"></i>
            Back to Dashboard
        </a>
        <div class="room-info">
            Channel: {{ $channel }}
        </div>
    </div>

    @if(!empty($dailyError))
        <div style="position:absolute;top:80px;left:20px;right:20px;z-index:120;padding:14px 16px;border-radius:12px;background:rgba(127,29,29,.92);color:#fff;font:600 14px/1.5 -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
            Daily.co setup failed: {{ $dailyError }}
        </div>
    @endif

    <div id="daily-container"></div>

    <script>
        const dailyUrl = "{{ $dailyUrl }}";
        const dailyToken = "{{ $dailyToken }}";
        const dailyError = @json($dailyError ?? null);
        
        const callFrame = window.DailyIframe.createFrame(document.getElementById('daily-container'), {
            showLeaveButton: true,
            iframeStyle: {
                width: '100%',
                height: '100%',
                border: '0',
            },
            theme: {
                colors: {
                    accent: '#2E4B3D',
                    accentText: '#FFFFFF',
                    background: '#07110B',
                    backgroundAccent: '#111E16',
                    baseText: '#FFFFFF',
                    border: '#2E4B3D',
                    mainAreaBg: '#07110B',
                    mainAreaBgAccent: '#0A140F',
                    mainAreaText: '#FFFFFF',
                    supportiveText: '#8F8F8F',
                },
            },
        });

        if (!dailyError) {
            const joinOptions = { url: dailyUrl };
            if (dailyToken) {
                joinOptions.token = dailyToken;
            }

            callFrame.join(joinOptions);
        }

        callFrame.on('left-meeting', () => {
            console.log('User left the meeting');
            // window.location.href = "{{ route('conferences.index') }}";
        });

        callFrame.on('error', (e) => {
            console.error('Daily.co Error:', e);
            alert('Daily.co Error: ' + e.errorMsg);
        });

        callFrame.on('load-attempt-failed', (e) => {
            console.error('Daily.co Load Attempt Failed:', e);
            alert('Daily.co Load Failed. Check console for details.');
        });

        // Auto-hide back bar
        let timeout;
        document.addEventListener('mousemove', () => {
            const bar = document.getElementById('back-bar');
            bar.classList.remove('hidden');
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (callFrame.meetingState() === 'joined-meeting') {
                    bar.classList.add('hidden');
                }
            }, 3000);
        });
    </script>
</body>
</html>

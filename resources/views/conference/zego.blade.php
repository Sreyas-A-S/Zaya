<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Session | ZEGOCLOUD | Zaya Wellness</title>
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
        .meeting-shell {
            position: relative;
            width: 100%;
            height: 100%;
        }
        #zego-root {
            width: 100%;
            height: 100%;
        }
        .top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            min-height: 60px;
            background: rgba(7, 17, 11, 0.84);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 20px;
            z-index: 100;
            border-bottom: 1px solid rgba(46, 75, 61, 0.2);
            box-sizing: border-box;
        }
        .back-link {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            opacity: 0.9;
        }
        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: auto;
        }
        .recording-indicator {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(127, 29, 29, 0.18);
            color: #fecaca;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }
        .recording-indicator.visible {
            display: inline-flex;
        }
        .room-info {
            color: rgba(255,255,255,0.55);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .prejoin-overlay {
            position: absolute;
            inset: 0;
            z-index: 110;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 88px 20px 24px;
            background:
                radial-gradient(circle at top, rgba(46, 75, 61, 0.35), transparent 36%),
                linear-gradient(180deg, rgba(7, 17, 11, 0.94), rgba(7, 17, 11, 0.98));
            box-sizing: border-box;
        }
        .prejoin-overlay.hidden {
            display: none;
        }
        .prejoin-card {
            width: 100%;
            max-width: 480px;
            padding: 28px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(14px);
            box-sizing: border-box;
        }
        .prejoin-badge {
            width: 64px;
            height: 64px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .prejoin-card h1 {
            margin: 0 0 10px;
            color: #fff;
            font-size: 30px;
            line-height: 1.15;
        }
        .prejoin-card p {
            margin: 0 0 22px;
            color: rgba(255, 255, 255, 0.68);
            line-height: 1.6;
            font-size: 14px;
        }
        .prejoin-field {
            margin-bottom: 18px;
        }
        .prejoin-label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.56);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .prejoin-value {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 15px;
            box-sizing: border-box;
        }
        .prejoin-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        .prejoin-btn {
            flex: 1 1 180px;
            min-height: 50px;
            border: 0;
            border-radius: 999px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            box-sizing: border-box;
        }
        .prejoin-btn-primary {
            background: #fff;
            color: #2E4B3D;
        }
        .prejoin-btn-primary:disabled {
            opacity: 0.7;
            cursor: wait;
        }
        .prejoin-btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .prejoin-status {
            margin-top: 16px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            display: none;
        }
        .prejoin-status.visible {
            display: block;
        }
        .error-card {
            position: absolute;
            inset: 0;
            z-index: 120;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(7, 17, 11, 0.9);
            box-sizing: border-box;
        }
        .error-panel {
            width: 100%;
            max-width: 640px;
            background: #fff;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.25);
            box-sizing: border-box;
        }
        .error-panel h1 {
            margin: 0 0 12px;
            color: #2E4B3D;
            font-size: 28px;
        }
        .error-panel p {
            margin: 0 0 20px;
            color: #55615b;
            line-height: 1.6;
        }
        .error-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }
        .btn-primary {
            background: #2E4B3D;
            color: #fff;
        }
        .btn-secondary {
            background: #F4F6F5;
            color: #2E4B3D;
        }
        @media (max-width: 640px) {
            .top-bar {
                align-items: flex-start;
                flex-wrap: wrap;
                padding: 14px 16px;
            }
            .top-actions {
                width: 100%;
                margin-left: 0;
                justify-content: space-between;
            }
            .room-info {
                font-size: 11px;
            }
            .prejoin-overlay {
                padding: 98px 14px 16px;
            }
            .prejoin-card {
                padding: 22px 18px;
                border-radius: 22px;
            }
            .prejoin-card h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="meeting-shell">
        <div class="top-bar">
            <a href="{{ route('conferences.index') }}" class="back-link">
                <i class="ri-arrow-left-line"></i>
                Back to Dashboard
            </a>
            <div class="top-actions">
                <div id="recording-indicator" class="recording-indicator">
                    <span style="width:8px;height:8px;border-radius:999px;background:#f87171;display:inline-block;"></span>
                    Auto Recording
                </div>
                <div class="room-info">
                    ZEGOCLOUD | Channel: {{ $channel }}
                </div>
            </div>
        </div>

        @if(!empty($zegoError))
            <div class="error-card">
                <div class="error-panel">
                    <h1>ZEGOCLOUD could not start</h1>
                    <p>{{ $zegoError }}</p>
                    <div class="error-actions">
                        <a class="btn btn-primary" href="{{ route('conference.join', ['channel' => $channel, 'provider' => 'jaas']) }}">Try JaaS instead</a>
                        <a class="btn btn-secondary" href="{{ route('conferences.index') }}">Back to history</a>
                    </div>
                </div>
            </div>
        @else
            <div id="zego-root"></div>
            <div id="zego-prejoin" class="prejoin-overlay">
                <div class="prejoin-card">
                    <div class="prejoin-badge">
                        <i class="ri-vidicon-fill"></i>
                    </div>
                    <h1>Ready to join?</h1>
                    <p>This session will be recorded automatically on the server through ZEGOCLOUD Cloud Recording as soon as you join.</p>

                    <div class="prejoin-field">
                        <span class="prejoin-label">Display Name</span>
                        <div class="prejoin-value">{{ $user->name ?? 'Guest' }}</div>
                    </div>

                    <div class="prejoin-field">
                        <span class="prejoin-label">Meeting Channel</span>
                        <div class="prejoin-value">{{ $channel }}</div>
                    </div>

                    <div class="prejoin-actions">
                        <button id="zego-join-btn" class="prejoin-btn prejoin-btn-primary">Join Meeting</button>
                        <a class="prejoin-btn prejoin-btn-secondary" href="{{ route('conferences.index') }}">Back to History</a>
                    </div>

                    <div id="zego-prejoin-status" class="prejoin-status">Starting server-side recording...</div>
                    <div id="zego-recording-note" class="prejoin-status">If cloud recording is unavailable, the meeting will still continue without recording.</div>
                </div>
            </div>

            <script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
            <script>
                const appId = {{ (int) $zegoAppId }};
                const roomId = @json($roomId);
                const userId = @json((string) ($user->id ?? ('guest_' . substr(md5($channel . now()->timestamp), 0, 8))));
                const userName = @json((string) ($user->name ?? 'Guest'));
                const container = document.querySelector('#zego-root');
                const prejoinOverlay = document.querySelector('#zego-prejoin');
                const prejoinStatus = document.querySelector('#zego-prejoin-status');
                const joinButton = document.querySelector('#zego-join-btn');
                const recordingIndicator = document.querySelector('#recording-indicator');
                const csrfToken = "{{ csrf_token() }}";
                const startRecordingUrl = "{{ route('zego.recording.start', ['channel' => $channel]) }}";
                const stopRecordingUrl = "{{ route('zego.recording.stop', ['channel' => $channel]) }}";
                const syncRecordingUrl = "{{ route('zego.recording.status', ['channel' => $channel]) }}";
                const tokenUrl = "{{ route('zego.token', ['channel' => $channel]) }}";
                let recordingTaskId = null;
                let recordingEnabled = false;
                let leaveHandled = false;

                const showSdkLoadError = (message) => {
                    document.body.insertAdjacentHTML(
                        'beforeend',
                        '<div class="error-card"><div class="error-panel"><h1>ZEGOCLOUD could not start</h1><p>' + message + '</p><div class="error-actions"><a class="btn btn-primary" href="{{ route('conference.join', ['channel' => $channel, 'provider' => 'jaas']) }}">Try JaaS instead</a><a class="btn btn-secondary" href="{{ route('conferences.index') }}">Back to history</a></div></div></div>'
                    );
                };

                if (!window.ZegoUIKitPrebuilt || !container || !prejoinOverlay || !joinButton) {
                    console.error('ZEGOCLOUD SDK failed to load or required UI elements are missing.');
                    showSdkLoadError('The ZEGOCLOUD web SDK did not load correctly. Refresh once, and if it still fails, check browser console/network errors.');
                } else {
                    joinButton.addEventListener('click', async () => {
                        joinButton.disabled = true;
                        prejoinStatus.classList.add('visible');
                        prejoinStatus.textContent = 'Checking cloud recording availability...';

                        const recordingStart = await fetch(startRecordingUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        }).then((response) => response.json()).catch(() => ({ success: false, message: 'Could not contact the recording service.' }));

                        if (!recordingStart.success) {
                            recordingEnabled = false;
                            prejoinStatus.textContent = (recordingStart.message || 'Server-side recording unavailable.') + ' Joining without recording...';
                        } else {
                            recordingEnabled = true;
                            recordingTaskId = recordingStart.task_id || null;
                            recordingIndicator.classList.add('visible');
                            prejoinStatus.textContent = 'Joining meeting and starting cloud recording...';
                        }

                        const tokenResponse = await fetch(tokenUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                name: userName
                            })
                        }).then((response) => response.json()).catch(() => ({ success: false, message: 'Could not generate a ZEGOCLOUD token.' }));

                        if (!tokenResponse.success || !tokenResponse.token) {
                            joinButton.disabled = false;
                            prejoinStatus.textContent = tokenResponse.message || 'Could not generate a secure ZEGOCLOUD token.';
                            return;
                        }

                        const kitToken = window.ZegoUIKitPrebuilt.generateKitTokenForProduction(
                            appId,
                            tokenResponse.token,
                            roomId,
                            tokenResponse.user_id || userId,
                            userName
                        );

                        const zp = window.ZegoUIKitPrebuilt.create(kitToken);
                        zp.joinRoom({
                            container,
                            sharedLinks: [
                                {
                                    name: 'Personal link',
                                    url: window.location.href,
                                },
                            ],
                            scenario: {
                                mode: window.ZegoUIKitPrebuilt.VideoConference,
                            },
                            showPreJoinView: false,
                            turnOnMicrophoneWhenJoining: true,
                            turnOnCameraWhenJoining: true,
                            showScreenSharingButton: true,
                            showTextChat: true,
                            maxUsers: 2,
                            onLeaveRoom: async () => {
                                if (leaveHandled) {
                                    return;
                                }

                                leaveHandled = true;

                                if (recordingEnabled) {
                                    await stopRecording();
                                }
                            }
                        });

                        prejoinOverlay.classList.add('hidden');
                    });
                }

                async function stopRecording() {
                    try {
                        await fetch(stopRecordingUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ task_id: recordingTaskId })
                        });
                    } catch (error) {
                        console.error('Failed to stop ZEGOCLOUD recording:', error);
                    }

                    try {
                        await fetch(syncRecordingUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ task_id: recordingTaskId })
                        });
                    } catch (error) {
                        console.error('Failed to sync recording status:', error);
                    }
                }
            </script>
        @endif
    </div>
</body>
</html>

@extends('layouts.client')

@section('title', 'Live Session | Zaya Wellness')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}" class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap transition-colors">Dashboard</a>
    <a href="{{ route('conferences.index') }}" class="leading-none text-lg text-secondary font-normal whitespace-nowrap transition-colors border-b-2 border-secondary pb-1">Live Session</a>
</div>

<div class="max-w-6xl mx-auto">
    <!-- Meeting Portal Card -->
    <div class="bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
        
        <!-- Meeting Header -->
        <div class="p-6 border-b border-[#2E4B3D]/12 flex flex-col sm:flex-row justify-between items-center gap-4 bg-[#FDFDFD]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-secondary/5 rounded-2xl flex items-center justify-center">
                    <i class="ri-vidicon-fill text-secondary text-2xl animate-pulse"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-secondary font-sans! leading-none mb-1">Consultation Room</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Channel:</span>
                        <span class="text-[10px] text-secondary font-bold">{{ $channel }}</span>
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full ml-2"></div>
                        <span id="timer" class="text-[10px] text-gray-500 font-mono">00:00:00</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="copyMeetingLink()" class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer" title="Copy Meeting Link">
                    <i class="ri-share-forward-line text-xl"></i>
                </button>
                <button onclick="togglePiP()" class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer" title="Picture in Picture">
                    <i class="ri-picture-in-picture-2-fill text-xl"></i>
                </button>
                <button onclick="toggleSettings()" class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer" title="Device Settings">
                    <i class="ri-settings-4-fill text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Video Area -->
        <div class="relative aspect-video bg-[#0A1209] group" id="meeting-stage">
            
            <!-- Pre-Join Screen -->
            <div id="join-overlay" class="absolute inset-0 z-[100] bg-secondary flex flex-col items-center justify-center text-center p-6 transition-all duration-500">
                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mb-8">
                    <i class="ri-vidicon-fill text-white text-5xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-4">Ready to Join?</h2>
                <p class="text-white/60 mb-10 max-w-md text-lg">Ensure your camera and microphone are connected before entering the session.</p>
                
                <div id="setup-feedback" class="mb-6 text-white/80 text-sm hidden">
                    <i class="ri-loader-4-line animate-spin mr-2"></i> Checking devices...
                </div>

                <button id="start-btn" onclick="startSession()" class="px-12 py-4 bg-white text-secondary rounded-full font-bold text-xl hover:scale-105 transition-all shadow-2xl flex items-center gap-3 cursor-pointer">
                    <i class="ri-door-open-fill"></i>
                    Join Meeting Now
                </button>
                
                <p id="browser-warning" class="mt-8 text-yellow-300 text-sm hidden">
                    <i class="ri-error-warning-line"></i> 
                    Warning: Browser requires HTTPS for camera access.
                </p>
            </div>

            <!-- Main Remote Video Container -->
            <div id="remote-playerlist" class="w-full h-full flex flex-wrap items-center justify-center gap-2">
                <!-- Remote players will be appended here -->
            </div>

            <!-- Waiting Screen Overlay (shown when no remote user) -->
            <div id="remote-waiting" class="absolute inset-0 flex items-center justify-center z-10 bg-[#0A1209]">
                <div class="text-center p-6">
                    <div class="w-20 h-20 border-2 border-secondary/20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya" class="w-10 opacity-20">
                        <div class="absolute inset-0 border-t-2 border-secondary rounded-full animate-spin"></div>
                    </div>
                    <h3 class="text-white text-xl font-light mb-2">Waiting for Participant</h3>
                    <p class="text-white/30 text-xs tracking-wide">The session will begin as soon as others join.</p>
                </div>
            </div>

            <!-- Local Self View (Floating) -->
            <div id="local-container" class="absolute bottom-6 right-6 w-32 md:w-56 aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl z-20 group/local bg-black">
                <div id="local-player" class="w-full h-full"></div>
                <div class="absolute top-2 left-2 opacity-0 group-hover/local:opacity-100 transition-opacity">
                    <span class="text-[8px] text-white bg-black/40 backdrop-blur-md px-2 py-0.5 rounded-full uppercase font-bold tracking-tighter">You</span>
                </div>
                <div id="local-muted-overlay" class="absolute inset-0 bg-black/60 flex items-center justify-center hidden">
                    <i class="ri-video-off-fill text-white text-2xl"></i>
                </div>
            </div>

            <!-- Desktop Hover Controls -->
            <div class="hidden md:block absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-30">
                <div class="flex items-center justify-center gap-6">
                    <!-- Mic -->
                    <button id="audio-toggle" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all active:scale-90 cursor-pointer">
                        <i class="ri-mic-fill text-xl" id="mic-icon"></i>
                    </button>
                    <!-- Vid -->
                    <button id="video-toggle" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all active:scale-90 cursor-pointer">
                        <i class="ri-video-on-fill text-xl" id="vid-icon"></i>
                    </button>
                    <!-- Share -->
                    <button id="screen-share-btn" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all active:scale-90 cursor-pointer">
                        <i class="ri-screen-share-line text-xl" id="screen-icon"></i>
                    </button>
                    <!-- End -->
                    <button onclick="leave()" class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center text-white hover:bg-red-600 transition-all active:scale-95 shadow-lg shadow-red-500/20 cursor-pointer">
                        <i class="ri-phone-fill text-2xl rotate-[135deg]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Controls -->
        <div class="md:hidden px-4 py-4 bg-[#0A1209] border-t border-white/10">
            <div class="grid grid-cols-4 gap-3">
                <button id="audio-toggle-mobile" class="h-12 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all">
                    <i class="ri-mic-fill text-xl" id="mic-icon-mobile"></i>
                </button>
                <button id="video-toggle-mobile" class="h-12 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all">
                    <i class="ri-video-on-fill text-xl" id="vid-icon-mobile"></i>
                </button>
                <button id="screen-share-btn-mobile" class="h-12 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all">
                    <i class="ri-screen-share-line text-xl" id="screen-icon-mobile"></i>
                </button>
                <button onclick="leave()" class="h-12 bg-red-500 rounded-2xl flex items-center justify-center text-white active:scale-95 shadow-lg shadow-red-500/20 transition-all">
                    <i class="ri-phone-fill text-xl rotate-[135deg]"></i>
                </button>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="p-6 bg-white flex items-center justify-between border-t border-gray-50">
            <div class="flex items-center gap-3">
                <i class="ri-shield-check-line text-secondary"></i>
                <p class="text-xs text-gray-400 font-medium italic">End-to-end encrypted secure session</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest" id="net-label">Network: Excellent</span>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div id="settings-modal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 px-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleSettings()"></div>
    <div class="relative bg-white rounded-[32px] p-8 w-full max-w-md shadow-2xl scale-95 transition-transform" id="settings-content">
        <h3 class="text-xl font-bold text-secondary mb-6 font-sans!">Device Settings</h3>
        <div class="space-y-6">
            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Camera</label>
                <select id="camera-select" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></select>
            </div>
            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Microphone</label>
                <select id="mic-select" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></select>
            </div>
        </div>
        <button onclick="applySettings()" class="mt-8 w-full py-4 bg-secondary text-white rounded-xl font-bold hover:opacity-90 transition-all cursor-pointer">
            Save & Apply
        </button>
    </div>
</div>

<div class="h-10"></div>
@endsection

@push('styles')
<style>
    .agora_video_player { object-fit: cover !important; border-radius: inherit; }
    #local-player video { transform: rotateY(180deg); }
    .remote-video-container { position: relative; flex: 1; min-width: 300px; height: 100%; border-radius: inherit; overflow: hidden; background: #000; }
</style>
@endpush

@section('scripts')
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- SECURE ORIGIN CHECK ---
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            document.getElementById('browser-warning').classList.remove('hidden');
        }

        // Timer Logic
        let startTime = Date.now();
        setInterval(() => {
            const now = Date.now();
            const diff = Math.floor((now - startTime) / 1000);
            const h = Math.floor(diff / 3600).toString().padStart(2, '0');
            const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const s = (diff % 60).toString().padStart(2, '0');
            const timerEl = document.getElementById('timer');
            if (timerEl) timerEl.innerText = `${h}:${m}:${s}`;
        }, 1000);

        // --- GLOBAL FUNCTIONS ---
        window.copyMeetingLink = () => {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Meeting link copied to clipboard!');
            });
        };

        window.togglePiP = async () => {
            const video = document.querySelector('#remote-playerlist video');
            if (video) {
                try {
                    if (video !== document.pictureInPictureElement) {
                        await video.requestPictureInPicture();
                    } else {
                        await document.exitPictureInPicture();
                    }
                } catch (e) { console.error(e); }
            }
        };

        window.toggleSettings = () => {
            const modal = document.getElementById('settings-modal');
            const isHidden = modal.classList.contains('opacity-0');
            if (isHidden) {
                modal.classList.replace('opacity-0', 'opacity-100');
                modal.classList.remove('pointer-events-none');
                loadDevices();
            } else {
                modal.classList.replace('opacity-100', 'opacity-0');
                modal.classList.add('pointer-events-none');
            }
        };

        window.applySettings = async () => {
            const camId = document.getElementById('camera-select').value;
            const micId = document.getElementById('mic-select').value;
            
            try {
                if (localTracks.videoTrack) {
                    await localTracks.videoTrack.setDevice(camId);
                }
                if (localTracks.audioTrack) {
                    await localTracks.audioTrack.setDevice(micId);
                }
                toggleSettings();
            } catch (e) {
                console.error("Failed to apply device settings:", e);
                alert("Could not switch devices. Please try again.");
            }
        };

        async function loadDevices() {
            const devices = await AgoraRTC.getDevices();
            const camSelect = document.getElementById('camera-select');
            const micSelect = document.getElementById('mic-select');
            
            if (camSelect && micSelect) {
                camSelect.innerHTML = '';
                micSelect.innerHTML = '';
                devices.filter(d => d.kind === 'videoinput').forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.deviceId;
                    opt.text = c.label || `Camera ${camSelect.length + 1}`;
                    camSelect.appendChild(opt);
                });
                devices.filter(d => d.kind === 'audioinput').forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.deviceId;
                    opt.text = m.label || `Microphone ${micSelect.length + 1}`;
                    micSelect.appendChild(opt);
                });
            }
        }

        window.leave = async () => {
            if (localTracks.audioTrack) {
                localTracks.audioTrack.stop();
                localTracks.audioTrack.close();
            }
            if (localTracks.videoTrack) {
                localTracks.videoTrack.stop();
                localTracks.videoTrack.close();
            }
            if (client) {
                await client.leave();
            }
            window.location.href = "{{ route('conferences.index') }}";
        };

        // --- AGORA CORE ---
        const appId = "{{ $appId }}".trim();
        const channel = "{{ trim($channel) }}";
        const uid = {{ (int) ($user->id ?? 1) }};
        
        console.log("DEBUG: Agora Initial Config:", { appId, channel, uid });

        if (!appId || appId.length < 5) {
            console.error("Invalid Agora App ID");
            alert("Configuration error: Invalid Agora App ID.");
            return;
        }

        let client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        let localTracks = { videoTrack: null, audioTrack: null };
        let remoteUsers = {};
        let screenTrack = null;
        let isSharing = false;

        async function fetchToken() {
            try {
                const url = `{{ route('agora.token') }}?channel=${encodeURIComponent(channel)}&uid=${uid}`;
                console.log("DEBUG: Fetching token from:", url);
                const response = await fetch(url);
                const data = await response.json();
                console.log("DEBUG: Token Data received:", data);
                if (data.error) throw new Error(data.error);
                return data.token;
            } catch (e) {
                console.error("Token fetch failed:", e);
                return null;
            }
        }

        window.startSession = async () => {
            const btn = document.getElementById('start-btn');
            const feedback = document.getElementById('setup-feedback');
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Initializing...';
            feedback.classList.remove('hidden');

            try {
                // 1. Get Devices
                feedback.innerText = "Requesting permissions...";
                localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
                
                // 2. Fetch Token
                feedback.innerText = "Securing connection...";
                const token = await fetchToken();
                if (!token) throw new Error("Failed to generate access token.");

                // 3. Join Channel
                feedback.innerText = "Entering room...";
                console.log("DEBUG: client.join starting with:", { appId, channel, token, uid });
                await client.join(appId, channel, token, uid);
                console.log("DEBUG: client.join succeeded.");
                
                // 4. Play and Publish
                localTracks.videoTrack.play("local-player");
                await client.publish(Object.values(localTracks));

                // 5. Success - Hide Overlay
                const overlay = document.getElementById('join-overlay');
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                setTimeout(() => overlay.remove(), 500);

                // Setup listeners
                setupClientListeners();

            } catch (e) {
                console.error("Start Session Error:", e);
                let msg = "Could not join the session. ";
                if (e.message.includes("Permission denied")) msg += "Camera/Microphone access was denied.";
                else if (e.message.includes("token")) msg += "Authentication failed.";
                else msg += e.message;
                
                alert(msg);
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-door-open-fill"></i> Retry Joining';
                feedback.classList.add('hidden');
            }
        };

        function setupClientListeners() {
            client.on("user-published", handleUserPublished);
            client.on("user-unpublished", handleUserUnpublished);
            
            client.on("token-privilege-will-expire", async function() {
                let newToken = await fetchToken();
                if (newToken) await client.renewToken(newToken);
            });

            client.on("network-quality", q => {
                const labels = ['Excellent', 'Excellent', 'Good', 'Fair', 'Poor', 'Bad', 'Critical'];
                const labelEl = document.getElementById('net-label');
                if (labelEl) labelEl.innerText = `Network: ${labels[q.downlinkNetworkQuality] || 'Stable'}`;
            });
        }

        async function handleUserPublished(user, mediaType) {
            await client.subscribe(user, mediaType);
            
            if (mediaType === "video") {
                const remotePlayerList = document.getElementById("remote-playerlist");
                const waiting = document.getElementById("remote-waiting");
                if (waiting) waiting.classList.add('hidden');

                // Create container for remote user
                let playerContainer = document.getElementById(`player-${user.uid}`);
                if (!playerContainer) {
                    playerContainer = document.createElement("div");
                    playerContainer.id = `player-${user.uid}`;
                    playerContainer.className = "remote-video-container";
                    remotePlayerList.appendChild(playerContainer);
                }
                user.videoTrack.play(playerContainer.id);
            }
            
            if (mediaType === "audio") {
                user.audioTrack.play();
            }
            remoteUsers[user.uid] = user;
        }

        function handleUserUnpublished(user) {
            const playerContainer = document.getElementById(`player-${user.uid}`);
            if (playerContainer) playerContainer.remove();
            
            delete remoteUsers[user.uid];
            
            if (Object.keys(remoteUsers).length === 0) {
                const waiting = document.getElementById("remote-waiting");
                if (waiting) waiting.classList.remove('hidden');
            }
        }

        // Control Toggles
        async function toggleAudio() {
            if (!localTracks.audioTrack) return;
            const isMuted = !localTracks.audioTrack.muted;
            await localTracks.audioTrack.setMuted(isMuted);
            
            const icon = isMuted ? 'ri-mic-off-fill text-red-500' : 'ri-mic-fill';
            document.getElementById('mic-icon').className = icon;
            document.getElementById('mic-icon-mobile').className = icon;
        }

        async function toggleVideo() {
            if (!localTracks.videoTrack) return;
            const isOff = !localTracks.videoTrack.muted;
            await localTracks.videoTrack.setMuted(isOff);
            
            const icon = isOff ? 'ri-video-off-fill text-red-500' : 'ri-video-on-fill';
            document.getElementById('vid-icon').className = icon;
            document.getElementById('vid-icon-mobile').className = icon;
            document.getElementById('local-muted-overlay').classList.toggle('hidden', !isOff);
        }

        async function toggleScreenShare() {
            if (!isSharing) {
                try {
                    screenTrack = await AgoraRTC.createScreenVideoTrack();
                    await client.unpublish(localTracks.videoTrack);
                    await client.publish(screenTrack);
                    screenTrack.play("local-player");
                    
                    screenTrack.on("track-ended", () => {
                        if (isSharing) toggleScreenShare();
                    });

                    isSharing = true;
                    updateScreenShareUI(true);
                } catch (e) {
                    console.error("Screen share failed:", e);
                }
            } else {
                await client.unpublish(screenTrack);
                screenTrack.stop();
                screenTrack.close();
                await client.publish(localTracks.videoTrack);
                localTracks.videoTrack.play("local-player");
                isSharing = false;
                updateScreenShareUI(false);
            }
        }

        function updateScreenShareUI(sharing) {
            const icon = sharing ? 'ri-stop-circle-fill text-red-500' : 'ri-screen-share-line';
            document.getElementById('screen-icon').className = icon;
            document.getElementById('screen-icon-mobile').className = icon;
        }

        document.getElementById('audio-toggle').onclick = toggleAudio;
        document.getElementById('video-toggle').onclick = toggleVideo;
        document.getElementById('screen-share-btn').onclick = toggleScreenShare;
        document.getElementById('audio-toggle-mobile').onclick = toggleAudio;
        document.getElementById('video-toggle-mobile').onclick = toggleVideo;
        document.getElementById('screen-share-btn-mobile').onclick = toggleScreenShare;
    });
</script>
@endsection

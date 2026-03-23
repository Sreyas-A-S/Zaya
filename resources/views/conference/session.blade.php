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
            
            <!-- Pre-Join Screen (User Gesture Trigger) -->
            <div id="join-overlay" class="absolute inset-0 z-[100] bg-secondary flex flex-col items-center justify-center text-center p-6 transition-all duration-500">
                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mb-8">
                    <i class="ri-vidicon-fill text-white text-5xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-4">Ready to Join?</h2>
                <p class="text-white/60 mb-10 max-w-md text-lg">Ensure your camera and microphone are connected before entering the session.</p>
                <button id="start-btn" onclick="startSession()" class="px-12 py-4 bg-white text-secondary rounded-full font-bold text-xl hover:scale-105 transition-all shadow-2xl flex items-center gap-3 cursor-pointer">
                    <i class="ri-door-open-fill"></i>
                    Join Meeting Now
                </button>
                <p id="browser-warning" class="mt-8 text-yellow-300 text-sm hidden">
                    <i class="ri-error-warning-line"></i> 
                    Warning: Browser requires HTTPS for camera access.
                </p>
            </div>

            <!-- Main Remote Video -->
            <div id="remote-player" class="w-full h-full"></div>

            <!-- Waiting Screen Overlay -->
            <div id="remote-waiting" class="absolute inset-0 flex items-center justify-center z-10 bg-[#0A1209]">
                <div class="text-center p-6">
                    <div class="w-20 h-20 border-2 border-secondary/20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya" class="w-10 opacity-20">
                        <div class="absolute inset-0 border-t-2 border-secondary rounded-full animate-spin"></div>
                    </div>
                    <h3 class="text-white text-xl font-light mb-2">Connecting to Practitioner</h3>
                    <p class="text-white/30 text-xs tracking-wide">Please ensure your camera and mic are enabled.</p>
                </div>
            </div>

            <!-- Local Self View (Floating) -->
            <div id="local-container" class="absolute bottom-6 right-6 w-32 md:w-56 aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl z-20 group/local bg-black">
                <div id="local-player" class="w-full h-full"></div>
                <div class="absolute top-2 left-2 opacity-0 group-hover/local:opacity-100 transition-opacity">
                    <span class="text-[8px] text-white bg-black/40 backdrop-blur-md px-2 py-0.5 rounded-full uppercase font-bold tracking-tighter">You</span>
                </div>
            </div>

            <!-- On-Video Hover Controls -->
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-30">
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

        <!-- Footer Info -->
        <div class="p-6 bg-white flex items-center justify-between border-t border-gray-50">
            <div class="flex items-center gap-3">
                <i class="ri-shield-check-line text-secondary"></i>
                <p class="text-xs text-gray-400 font-medium italic">End-to-end encrypted medical consultation</p>
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
        <button onclick="toggleSettings()" class="mt-8 w-full py-4 bg-secondary text-white rounded-xl font-bold hover:opacity-90 transition-all cursor-pointer">
            Save & Exit
        </button>
    </div>
</div>

<div class="h-10"></div>
@endsection

@push('styles')
<style>
    .agora_video_player { object-fit: cover !important; border-radius: inherit; }
    #local-player video { transform: rotateY(180deg); }
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

        // --- GLOBAL FUNCTIONS (WINDOW SCOPE) ---
        window.copyMeetingLink = () => {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                if (window.showZayaToast) {
                    showZayaToast('Meeting link copied to clipboard!', 'Share Session');
                } else {
                    alert('Meeting link copied!');
                }
            });
        };

        window.togglePiP = async () => {
            const video = document.querySelector('#remote-player video');
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
            } else {
                modal.classList.replace('opacity-100', 'opacity-0');
                modal.classList.add('pointer-events-none');
            }
        };

        window.leave = () => {
            if (localTracks.audioTrack) localTracks.audioTrack.close();
            if (localTracks.videoTrack) localTracks.videoTrack.close();
            client.leave();
            window.location.href = "{{ route('conferences.index') }}";
        };

        // --- AGORA LOGIC ---
        const rawAppId = "{{ $appId }}";
        const trimmedAppId = rawAppId.trim();
        
        let options = { 
            appId: trimmedAppId, 
            channel: "{{ trim($channel) }}", 
            token: null, 
            uid: 0 
        };
        
        console.log("Agora Initialization with App ID Length:", options.appId.length);
        
        if (!options.appId) {
            console.error("CRITICAL: Agora App ID is empty. Check your .env and config/services.php");
            alert("Configuration Error: Agora App ID is missing. Please contact support.");
            return;
        }

        let client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        let localTracks = { videoTrack: null, audioTrack: null };
        let screenTrack = null;
        let remoteUsers = {};
        let isSharing = false;

        // Fetch Token from Backend
        async function fetchToken() {
            try {
                const response = await fetch(`{{ route('agora.token') }}?channel=${options.channel}&uid=${options.uid}`);
                const data = await response.json();
                return data.token;
            } catch (e) {
                console.error("Token fetch failed:", e);
                return null;
            }
        }

        // Start Session (User Gesture Required)
        window.startSession = async () => {
            const btn = document.getElementById('start-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Initializing...';

            await join();
            
            // Hide Overlay
            const overlay = document.getElementById('join-overlay');
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            setTimeout(() => overlay.remove(), 500);
        };

        async function join() {
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

            try {
                console.log("Checking App ID validity...");
                if (options.appId.length < 10) {
                    throw new Error("Invalid App ID format. Check your .env file.");
                }

                // REQUEST PERMISSIONS FIRST
                console.log("Requesting microphone track...");
                localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack().catch(err => {
                    console.error("Mic Access Denied:", err);
                    throw new Error("Microphone access was denied or no device found.");
                });

                console.log("Requesting camera track...");
                localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack().catch(err => {
                    console.error("Camera Access Denied:", err);
                    throw new Error("Camera access was denied or no device found.");
                });

                options.token = await fetchToken();
                if (!options.token) {
                    throw new Error("Failed to generate secure access token.");
                }
                
                console.log("Attempting Secure Join...");
                await client.join(options.appId, options.channel, options.token, options.uid);
                console.log("Joined successfully.");

                localTracks.videoTrack.play("local-player");
                await client.publish(Object.values(localTracks));

                const devices = await AgoraRTC.getDevices();
                const camSelect = document.getElementById('camera-select');
                const micSelect = document.getElementById('mic-select');
                if (camSelect && micSelect) {
                    camSelect.innerHTML = ''; micSelect.innerHTML = '';
                    devices.filter(d => d.kind === 'videoinput').forEach(c => camSelect.innerHTML += `<option value="${c.deviceId}">${c.label || 'Camera ' + (camSelect.length+1)}</option>`);
                    devices.filter(d => d.kind === 'audioinput').forEach(m => micSelect.innerHTML += `<option value="${m.deviceId}">${m.label || 'Mic ' + (micSelect.length+1)}</option>`);
                }
            } catch (e) {
                console.error("Agora Critical Error:", e);
                let msg = e.message;
                
                if (e.message.includes("dynamic use static key")) {
                    msg = "Security Mismatch: Your Agora project is in 'Testing Mode' but we are sending a secure token. \n\nFIX: Go to Agora Console and ENABLE 'App Certificate' for this project.";
                } else if (e.message.includes("invalid vendor key")) {
                    msg = "Invalid App ID: The ID in your .env does not match any project in your Agora Console.";
                }
                
                alert("Connection Failed: " + msg);
                const btn = document.getElementById('start-btn');
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-door-open-fill"></i> Retry Joining';
            }        }

        async function handleUserPublished(user, mediaType) {
            await client.subscribe(user, mediaType);
            if (mediaType === "video") {
                const waiting = document.getElementById("remote-waiting");
                if (waiting) waiting.classList.add('hidden');
                user.videoTrack.play("remote-player");
            }
            if (mediaType === "audio") user.audioTrack.play();
            remoteUsers[user.uid] = user;
        }

        function handleUserUnpublished(user) {
            const waiting = document.getElementById("remote-waiting");
            if (waiting) waiting.classList.remove('hidden');
            delete remoteUsers[user.uid];
        }

        // Audio/Video Toggle
        document.getElementById('audio-toggle').onclick = async () => {
            const isMuted = !localTracks.audioTrack.muted;
            await localTracks.audioTrack.setMuted(isMuted);
            document.getElementById('mic-icon').className = isMuted ? 'ri-mic-off-fill text-red-500' : 'ri-mic-fill';
        };

        document.getElementById('video-toggle').onclick = async () => {
            const isOff = !localTracks.videoTrack.muted;
            await localTracks.videoTrack.setMuted(isOff);
            document.getElementById('vid-icon').className = isOff ? 'ri-video-off-fill text-red-500' : 'ri-video-on-fill';
        };

        document.getElementById('screen-share-btn').onclick = async () => {
            if (!isSharing) {
                screenTrack = await AgoraRTC.createScreenVideoTrack();
                await client.unpublish(localTracks.videoTrack);
                await client.publish(screenTrack);
                screenTrack.play("local-player");
                isSharing = true;
                document.getElementById('screen-icon').className = 'ri-stop-circle-fill text-red-500';
            } else {
                await client.unpublish(screenTrack);
                screenTrack.stop(); screenTrack.close();
                await client.publish(localTracks.videoTrack);
                localTracks.videoTrack.play("local-player");
                isSharing = false;
                document.getElementById('screen-icon').className = 'ri-screen-share-line';
            }
        };
    });
</script>
@endsection

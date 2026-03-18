@extends('layouts.app')

@section('title', 'Session Recording - ' . $booking->practitioner->user->name)

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-8 md:py-20">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Back Link -->
        <a href="{{ route('conferences.index') }}" class="inline-flex items-center text-secondary hover:text-opacity-80 transition-colors mb-6 md:mb-8 group text-sm md:text-base">
            <i class="ri-arrow-left-line mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Back to History</span>
        </a>

        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 md:mb-8 gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-bold text-secondary mt-2 mb-2 font-sans!">Session Recording</h1>
                    <p class="text-gray-500 text-sm md:text-base">
                        Consultation with <span class="text-secondary font-medium">{{ $booking->practitioner->user->name }}</span> 
                        <span class="hidden md:inline">on</span><br class="md:hidden"> {{ $booking->booking_date->format('F d, Y') }} at {{ $booking->booking_time }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 md:px-4 md:py-2 bg-[#EEF2EF] text-secondary text-[10px] md:text-sm font-semibold rounded-full uppercase tracking-wider">
                        Online Session
                    </span>
                </div>
            </div>

            <!-- Zaya Pro Video Player -->
            <div id="video-wrapper" class="bg-white rounded-2xl md:rounded-[32px] overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500">
                <div class="relative group aspect-video bg-black overflow-hidden" id="player-container">
                    <!-- Error Overlay (Hidden by default) -->
                    <div id="video-error-overlay" class="absolute inset-0 z-50 bg-[#1A1A1A] flex items-center justify-center p-6 text-center hidden">
                        <div class="max-w-sm">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                                <i class="ri-error-warning-line text-secondary text-3xl md:text-4xl"></i>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-white mb-2 md:mb-3">Recording Unavailable</h3>
                            <p class="text-gray-400 text-xs md:text-sm mb-6 md:mb-8 leading-relaxed">
                                We're sorry, but this recording couldn't be loaded. It may still be processing.
                            </p>
                            <a href="{{ route('conferences.index') }}" class="inline-flex px-6 py-2.5 md:px-8 md:py-3 bg-secondary text-white rounded-full font-medium hover:bg-opacity-90 transition-all text-xs md:text-sm">
                                Return to History
                            </a>
                        </div>
                    </div>

                    <video id="main-player" class="w-full h-full cursor-pointer" playsinline>
                        <source src="{{ $booking->recording_url }}" type="video/mp4">
                    </video>

                    <!-- Custom UI Overlay -->
                    <div id="video-controls" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-4 md:p-6 pt-12 md:pt-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none group-focus-within:opacity-100">
                        <div class="pointer-events-auto">
                            <!-- Progress Bar -->
                            <div class="relative w-full h-1 md:h-1.5 bg-white/20 rounded-full mb-4 md:mb-6 cursor-pointer group/progress" id="progress-bar-container">
                                <div id="progress-bar-buffered" class="absolute top-0 left-0 h-full bg-white/10 rounded-full transition-all duration-300" style="width: 0%"></div>
                                <div id="progress-bar-current" class="absolute top-0 left-0 h-full bg-secondary rounded-full relative" style="width: 0%">
                                    <div class="absolute right-[-6px] top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg scale-0 group-hover/progress:scale-100 transition-transform"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 md:gap-6">
                                    <!-- Play/Pause -->
                                    <button id="play-pause" class="text-white hover:text-secondary transition-colors">
                                        <i class="ri-play-fill text-2xl md:text-3xl" id="play-icon"></i>
                                    </button>

                                    <!-- Volume (Hidden on mobile usually or minimized) -->
                                    <div class="hidden sm:flex items-center gap-3 group/volume">
                                        <button id="mute-unmute" class="text-white hover:text-secondary transition-colors">
                                            <i class="ri-volume-up-fill text-xl" id="volume-icon"></i>
                                        </button>
                                        <input type="range" id="volume-slider" min="0" max="1" step="0.1" value="1" class="w-0 group-hover/volume:w-20 transition-all duration-300 accent-secondary opacity-0 group-hover/volume:opacity-100">
                                    </div>

                                    <!-- Time -->
                                    <div class="text-white/90 text-[10px] md:text-sm font-medium tabular-nums">
                                        <span id="current-time">0:00</span> / <span id="duration">0:00</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 md:gap-5">
                                    <!-- Speed Selector -->
                                    <div class="relative group/speed">
                                        <button class="text-white text-[10px] font-bold border border-white/30 rounded px-1.5 py-0.5 md:px-2 md:py-1 hover:bg-white/10 transition-colors uppercase tracking-tighter">
                                            <span id="speed-display">1x</span>
                                        </button>
                                        <div class="absolute bottom-full mb-2 right-0 bg-white rounded-xl shadow-xl p-1 md:p-2 hidden group-hover/speed:block border border-gray-100">
                                            <div class="flex flex-col">
                                                <button onclick="setSpeed(0.5)" class="px-3 py-1.5 md:px-4 md:py-2 text-[10px] md:text-xs text-gray-600 hover:bg-gray-50 rounded-lg">0.5x</button>
                                                <button onclick="setSpeed(1)" class="px-3 py-1.5 md:px-4 md:py-2 text-[10px] md:text-xs text-secondary font-bold hover:bg-gray-50 rounded-lg">1x</button>
                                                <button onclick="setSpeed(1.5)" class="px-3 py-1.5 md:px-4 md:py-2 text-[10px] md:text-xs text-gray-600 hover:bg-gray-50 rounded-lg">1.5x</button>
                                                <button onclick="setSpeed(2)" class="px-4 py-2 text-[10px] md:text-xs text-gray-600 hover:bg-gray-50 rounded-lg">2x</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PiP (Hide on small mobile if not supported) -->
                                    <button id="pip-btn" class="text-white hover:text-secondary transition-colors" title="PiP">
                                        <i class="ri-picture-in-picture-2-fill text-lg md:text-xl"></i>
                                    </button>

                                    <!-- Fullscreen -->
                                    <button id="fullscreen-btn" class="text-white hover:text-secondary transition-colors">
                                        <i class="ri-fullscreen-fill text-xl md:text-2xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Player Info Footer -->
                <div class="p-5 md:p-10 flex flex-col sm:flex-row items-center justify-between gap-4 md:gap-6 border-t border-gray-50">
                    <div class="flex items-center gap-3 md:gap-4 w-full sm:w-auto">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-[#EEF2EF] rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="ri-vidicon-2-fill text-secondary text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-sm text-gray-400 font-medium uppercase tracking-widest mb-0.5">Session ID</p>
                            <p class="text-sm md:text-lg font-bold text-secondary">#{{ $booking->id + 10000 }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between sm:justify-end gap-4 md:gap-6 w-full sm:w-auto border-t sm:border-t-0 pt-4 sm:pt-0">
                        <div class="text-left sm:text-right">
                            <p class="text-[10px] md:text-sm text-gray-400 font-medium uppercase tracking-widest mb-0.5">Session Date</p>
                            <p class="text-sm md:text-lg font-bold text-secondary">{{ $booking->booking_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Note -->
            <p class="text-center text-gray-400 text-[10px] md:text-sm mt-8 md:mt-12 leading-relaxed max-w-lg mx-auto">
                This recording is for your personal records. Please respect the privacy of your practitioner.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const player = document.getElementById('main-player');
    const playPauseBtn = document.getElementById('play-pause');
    const playIcon = document.getElementById('play-icon');
    const progressBar = id => document.getElementById(id);
    const currentTimeEl = document.getElementById('current-time');
    const durationEl = document.getElementById('duration');
    const volumeSlider = document.getElementById('volume-slider');
    const muteBtn = document.getElementById('mute-unmute');
    const volumeIcon = document.getElementById('volume-icon');
    const speedDisplay = document.getElementById('speed-display');
    const pipBtn = document.getElementById('pip-btn');
    const theaterBtn = document.getElementById('theater-btn');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const videoWrapper = document.getElementById('video-wrapper');
    const playerContainer = document.getElementById('player-container');

    // Play/Pause
    function togglePlay() {
        if (player.paused) {
            player.play();
            playIcon.classList.replace('ri-play-fill', 'ri-pause-fill');
        } else {
            player.pause();
            playIcon.classList.replace('ri-pause-fill', 'ri-play-fill');
        }
    }

    playPauseBtn.addEventListener('click', togglePlay);
    player.addEventListener('click', togglePlay);

    // Robust Error Handling
    const showErrorUI = () => {
        document.getElementById('video-error-overlay').classList.remove('hidden');
        document.getElementById('video-controls').style.display = 'none';
        document.getElementById('video-controls').classList.add('hidden');
    };

    // Check for source errors (more reliable than just video error)
    const source = player.querySelector('source');
    if (source) {
        source.addEventListener('error', showErrorUI);
    }

    player.addEventListener('error', showErrorUI);

    // Safety check after a few seconds if video hasn't started loading
    setTimeout(() => {
        if (player.networkState === player.NETWORK_NO_SOURCE || player.error) {
            showErrorUI();
        }
    }, 3000);

    // Time Update
    function formatTime(time) {
        const minutes = Math.floor(time / 60);
        const seconds = Math.floor(time % 60);
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }

    player.addEventListener('timeupdate', () => {
        const percent = (player.currentTime / player.duration) * 100;
        progressBar('progress-bar-current').style.width = `${percent}%`;
        currentTimeEl.innerText = formatTime(player.currentTime);
        
        // Update buffered
        if (player.buffered.length > 0) {
            const bufferedEnd = player.buffered.end(player.buffered.length - 1);
            const bufferedPercent = (bufferedEnd / player.duration) * 100;
            progressBar('progress-bar-buffered').style.width = `${bufferedPercent}%`;
        }
    });

    player.addEventListener('loadedmetadata', () => {
        durationEl.innerText = formatTime(player.duration);
    });

    // Seek
    document.getElementById('progress-bar-container').addEventListener('click', (e) => {
        const rect = e.target.getBoundingClientRect();
        const pos = (e.pageX - rect.left) / rect.width;
        player.currentTime = pos * player.duration;
    });

    // Volume
    volumeSlider.addEventListener('input', (e) => {
        player.volume = e.target.value;
        updateVolumeIcon();
    });

    function updateVolumeIcon() {
        if (player.muted || player.volume === 0) {
            volumeIcon.className = 'ri-volume-mute-fill text-xl';
        } else if (player.volume < 0.5) {
            volumeIcon.className = 'ri-volume-down-fill text-xl';
        } else {
            volumeIcon.className = 'ri-volume-up-fill text-xl';
        }
    }

    muteBtn.addEventListener('click', () => {
        player.muted = !player.muted;
        updateVolumeIcon();
    });

    // Speed
    window.setSpeed = (speed) => {
        player.playbackRate = speed;
        speedDisplay.innerText = speed + 'x';
    };

    // PiP
    pipBtn.addEventListener('click', async () => {
        try {
            if (player !== document.pictureInPictureElement) {
                await player.requestPictureInPicture();
            } else {
                await document.exitPictureInPicture();
            }
        } catch (error) {
            console.error(error);
        }
    });

    // Theater Mode
    theaterBtn.addEventListener('click', () => {
        videoWrapper.classList.toggle('max-w-none');
        videoWrapper.classList.toggle('fixed');
        videoWrapper.classList.toggle('inset-0');
        videoWrapper.classList.toggle('z-[100003]');
        videoWrapper.classList.toggle('rounded-none');
        
        if (videoWrapper.classList.contains('fixed')) {
            playerContainer.classList.replace('aspect-video', 'h-screen');
        } else {
            playerContainer.classList.replace('h-screen', 'aspect-video');
        }
    });

    // Fullscreen
    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            playerContainer.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    });

    // Hide controls when mouse is still
    let timeout;
    playerContainer.addEventListener('mousemove', () => {
        document.getElementById('video-controls').style.opacity = '1';
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (!player.paused) document.getElementById('video-controls').style.opacity = '0';
        }, 3000);
    });
</script>
@endsection

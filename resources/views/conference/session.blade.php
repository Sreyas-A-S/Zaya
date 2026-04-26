@extends('layouts.client')

@section('title', 'Live Session | Zaya Wellness')

@section('content')
    @if(!$isMeetingPopout)
        <!-- Mobile Tab Navigation -->
        <div id="conference-mobile-nav" class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
            <a href="{{ route('dashboard') }}"
                class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap transition-colors">Dashboard</a>
            <a href="{{ route('conferences.index') }}"
                class="leading-none text-lg text-secondary font-normal whitespace-nowrap transition-colors border-b-2 border-secondary pb-1">Live
                Session</a>
        </div>
    @endif

    <div class="max-w-none mx-auto {{ $isMeetingPopout ? 'h-full' : 'lg:h-[calc(100vh-2rem)]' }} flex flex-col lg:flex-row gap-6 transition-all duration-500"
        id="session-wrapper">
        <!-- Meeting Portal Card -->
        <div id="video-container"
            class="flex flex-col flex-1 min-w-0 transition-all duration-500 {{ $isMeetingPopout ? 'in-popout h-full overflow-hidden rounded-none border-0 shadow-none bg-[#07110B]' : 'bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)]' }}">

            <!-- PiP Controls Overlay (visible only in PiP mode) -->
            <div id="pip-controls" class="select-none">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-[0.2em]">Live Session</span>
                </div>
                <button onclick="togglePiP()"
                    class="px-3 py-1.5 bg-white text-secondary rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all shadow-lg flex items-center gap-2 cursor-pointer pointer-events-auto">
                    <i class="ri-picture-in-picture-exit-fill"></i>
                    Restore
                </button>
            </div>

            @if(!$isMeetingPopout)
                <!-- Meeting Header -->
                <div id="conference-meeting-header"
                    class="p-6 border-b border-[#2E4B3D]/12 flex flex-col sm:flex-row justify-between items-center gap-4 bg-[#FDFDFD] {{ $isMeetingPopout ? 'hidden' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/5 rounded-2xl flex items-center justify-center">
                            <i class="ri-vidicon-fill text-secondary text-2xl animate-pulse"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-secondary font-sans! leading-none mb-1">
                                {{ $isInstant ? 'Instant Meeting' : 'Consultation Room' }}
                            </h2>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Channel:</span>
                                <span class="text-[10px] text-secondary font-bold">{{ $channel }}</span>
                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full ml-2"></div>
                                <span id="timer" class="text-[10px] text-gray-500 font-mono">00:00:00</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button onclick="copyMeetingLink()"
                            class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer"
                            title="Copy Meeting Link">
                            <i class="ri-share-forward-line text-xl"></i>
                        </button>

                        <button onclick="togglePopout()"
                            class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer"
                            title="Pop-out Window">
                            <i class="ri-external-link-line text-xl"></i>
                        </button>

                        @if($booking && in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                            @if($booking->need_translator && !$booking->translator_id)
                                <button
                                    onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language }}', '{{ $booking->to_language }}')"
                                    class="p-3 text-blue-600 hover:text-blue-700 transition-colors cursor-pointer"
                                    title="Assign Translator">
                                    <i class="ri-translate text-xl"></i>
                                </button>
                            @endif
                        @endif

                        @if($provider === 'agora')
                            <button onclick="toggleSettings()"
                                class="p-3 text-gray-400 hover:text-secondary transition-colors cursor-pointer"
                                title="Device Settings">
                                <i class="ri-settings-4-fill text-xl"></i>
                            </button>
                        @endif
                        <div id="recording-indicator"
                            class="hidden items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            Recording
                        </div>
                    </div>
                </div>
            @endif

            <!-- Video Area -->
            <div class="relative flex-1 min-h-[60vh] lg:min-h-0 {{ $isMeetingPopout ? 'bg-[#07110B]' : 'bg-[#0A1209]' }} group flex flex-col"
                id="meeting-stage">

                @if($provider === 'jaas' && !empty($jaasError))
                    <div class="absolute inset-0 z-[120] bg-black/85 flex items-center justify-center p-6">
                        <div class="max-w-xl w-full bg-white rounded-[28px] p-8 shadow-2xl border border-red-100">
                            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-5">
                                <i class="ri-error-warning-line text-2xl text-red-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-secondary mb-3">Video Meet could not start</h2>
                            <p class="text-gray-600 leading-relaxed mb-5">{{ $jaasError }}</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button onclick="switchProvider('daily')"
                                    class="px-5 py-3 rounded-full bg-secondary text-white font-semibold">
                                    Try Daily.co instead
                                </button>
                                <a href="{{ route('conferences.index') }}"
                                    class="px-5 py-3 rounded-full border border-gray-200 text-gray-700 font-semibold text-center">
                                    Back to history
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pre-Join / Provider Selection Screen -->
                <div id="join-overlay"
                    class="absolute inset-0 z-[100] bg-secondary flex flex-col items-center justify-center text-center p-6 transition-all duration-500 overflow-y-auto">
                    <div
                        class="w-[72px] h-[72px] md:w-24 md:h-24 bg-white/10 rounded-full flex items-center justify-center mb-4 md:mb-8 shrink-0">
                        <i class="ri-vidicon-fill text-white text-4xl md:text-5xl"></i>
                    </div>

                    @if($provider === 'choose')
                        <h2 class="text-3xl font-bold text-white mb-4">Choose Meeting Platform</h2>
                        <p class="text-white/60 mb-10 max-w-md text-lg">
                            Select your preferred video platform to start the consultation.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full max-w-lg">
                            <button onclick="switchProvider('jaas')"
                                class="p-6 bg-white/5 border border-white/10 rounded-[24px] hover:bg-white/10 transition-all text-left group">
                                <div
                                    class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="ri-shield-user-fill text-white text-xl"></i>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">JaaS (8x8)</h3>
                                <p class="text-white/40 text-xs">High-quality, secure enterprise video by Jitsi.</p>
                            </button>

                            <button onclick="switchProvider('daily')"
                                class="p-6 bg-white/5 border border-white/10 rounded-[24px] hover:bg-white/10 transition-all text-left group">
                                <div
                                    class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="ri-flashlight-fill text-white text-xl"></i>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">Daily.co</h3>
                                <p class="text-white/40 text-xs">Simple, fast, and reliable browser-based video.</p>
                            </button>

                            <button onclick="switchProvider('zegocloud')"
                                class="p-6 bg-white/5 border border-white/10 rounded-[24px] hover:bg-white/10 transition-all text-left group">
                                <div
                                    class="w-10 h-10 bg-fuchsia-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="ri-apps-2-fill text-white text-xl"></i>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">ZEGOCLOUD</h3>
                                <p class="text-white/40 text-xs">Hosted prebuilt video conference via ZEGOCLOUD UIKit.</p>
                            </button>

                            @if($agoraAvailable)
                                <button onclick="switchProvider('agora')"
                                    class="p-6 bg-white/5 border border-white/10 rounded-[24px] hover:bg-white/10 transition-all text-left group">
                                    <div
                                        class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <i class="ri-broadcast-fill text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-white font-bold text-lg mb-1">Agora</h3>
                                    <p class="text-white/40 text-xs">Low-latency global real-time engagement.</p>
                                </button>
                            @endif
                        </div>
                    @else
                        <h2 class="text-3xl font-bold text-white mb-4">
                            Ready to Join?
                        </h2>
                        <p class="text-white/60 mb-10 max-w-md text-lg">
                            You are joining the consultation session.
                        </p>

                        <div id="setup-feedback" class="mb-6 text-white/80 text-sm hidden">
                            <i class="ri-loader-4-line animate-spin mr-2"></i> Initializing...
                        </div>

                        <div class="flex flex-col items-center gap-4">
                            <button id="start-btn" onclick="startSession()"
                                class="px-8 md:px-12 py-3 md:py-4 bg-white text-secondary rounded-full font-bold text-lg md:text-xl hover:scale-105 transition-all shadow-2xl flex items-center justify-center gap-3 cursor-pointer shrink-0">
                                <i class="ri-door-open-fill"></i>
                                <span>Join Meeting Now</span>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Post-Session Summary Modal -->
                <div id="summary-modal"
                    class="absolute inset-0 z-[110] bg-[#111] flex items-center justify-center hidden px-4 py-6">
                    <div class="max-w-lg w-full bg-white rounded-3xl md:rounded-[2.5rem] overflow-y-auto max-h-full shadow-2xl transform scale-95 opacity-0 transition-all duration-500 scrollbar-hide"
                        id="summary-content">
                        <div class="p-6 md:p-10 text-center">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                                <i class="ri-checkbox-circle-line text-3xl md:text-4xl"></i>
                            </div>

                            <h2 class="text-xl md:text-2xl font-black text-secondary tracking-tight mb-2">Session Completed
                            </h2>
                            <p class="text-xs md:text-sm text-gray-500 mb-6 md:mb-8 leading-relaxed">
                                Thank you for your time. Your session
                                {{ $booking ? '#' . $booking->invoice_no : 'Ref: ' . $channel }} has been successfully
                                completed.
                            </p>

                            <div class="grid grid-cols-2 gap-3 mb-6 md:mb-8">
                               <a href="{{ route('conferences.index') }}"
                                   class="flex flex-col items-center gap-2 p-3 md:p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-secondary/20 hover:bg-secondary/5 transition-all group">
                                   <i
                                       class="ri-history-line text-lg md:text-xl text-gray-400 group-hover:text-secondary"></i>
                                   <span
                                       class="text-[10px] font-black uppercase tracking-widest text-gray-600">History</span>
                               </a>
                               <a href="{{ route('book-session') }}"
                                   class="flex flex-col items-center gap-2 p-3 md:p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-secondary/20 hover:bg-secondary/5 transition-all group">
                                   <i
                                       class="ri-calendar-check-line text-lg md:text-xl text-gray-400 group-hover:text-secondary"></i>
                                   <span
                                       class="text-[10px] font-black uppercase tracking-widest text-gray-600">Follow-up</span>
                               </a>
                            </div>

                            @if($booking && $user->id === $booking->user_id)
                            <div id="feedback-section" class="mb-8 border-t border-gray-50 pt-8">
                               <h3 class="text-sm font-black text-secondary uppercase tracking-widest mb-4">Share your Experience</h3>
                               <p class="text-xs text-gray-400 mb-6">How was your session with {{ $booking->practitioner->user->name ?? 'our professional' }}?</p>

                               <div class="star-rating flex items-center justify-center gap-2 mb-6">
                                   @for($i = 1; $i <= 5; $i++)
                                       <i class="ri-star-line text-2xl text-gray-300 cursor-pointer hover:text-[#FFD166] transition-colors" onclick="setRating({{ $i }})"></i>
                                   @endfor
                                   <input type="hidden" id="rating-input" value="0">
                               </div>

                               <textarea id="review-text" placeholder="Share your story of transformation..." 
                                   class="w-full bg-[#F9FBF9] border border-[#2E4B3D]/10 rounded-2xl p-4 text-sm font-medium focus:ring-1 focus:ring-secondary outline-none transition-all mb-4 scrollbar-hide"
                                   rows="3"></textarea>

                               <button type="button" onclick="submitReview()" id="submit-review-btn"
                                   class="w-full py-3 md:py-4 bg-secondary text-white rounded-2xl font-black text-xs hover:bg-primary transition-all shadow-lg shadow-secondary/20 uppercase tracking-[0.2em]">
                                   Submit Review
                               </button>
                            </div>
                            @endif

                            <div class="flex flex-col gap-3">
                               <a href="{{ route('dashboard') }}"
                                   class="w-full py-3 md:py-4 {{ ($booking && $user->id === $booking->user_id) ? 'bg-gray-50 text-secondary border border-[#2E4B3D]/5' : 'bg-secondary text-white shadow-lg shadow-secondary/20' }} rounded-2xl font-black text-xs hover:opacity-90 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                                   Back to Dashboard
                                   <i class="ri-arrow-right-line"></i>
                               </a>                                <button
                                    onclick="window.location.href='mailto:support@zayawellness.com?subject=Session Feedback #{{ $booking?->invoice_no ?? $channel }}'"
                                    class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-secondary transition-colors py-2 block w-full text-center">
                                    Need help? Report an issue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if($provider === 'jaas')
                    <div class="absolute inset-0">
                        <div id="jitsi-meet-container" class="w-full h-full"></div>
                    </div>
                @elseif($provider === 'daily')
                    <div class="absolute inset-0">
                        <div id="daily-meet-container" class="w-full h-full"></div>
                    </div>
                @endif
            </div>

            <!-- Mobile Controls -->
            @if(!$isMeetingPopout && in_array($provider, ['daily', 'agora']))
                <div class="md:hidden px-4 py-6 bg-[#0A1209] border-t border-white/10 flex justify-center">
                    <div class="flex items-center justify-center gap-6 w-full max-w-xs">
                        <button id="audio-toggle-mobile"
                            class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all cursor-pointer">
                            <i class="ri-mic-fill text-2xl" id="mic-icon-mobile"></i>
                        </button>
                        <button id="video-toggle-mobile"
                            class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all cursor-pointer">
                            <i class="ri-video-on-fill text-2xl" id="vid-icon-mobile"></i>
                        </button>
                        <button id="record-toggle-mobile"
                            class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white active:scale-95 transition-all cursor-pointer">
                            <i class="ri-record-circle-line text-2xl" id="record-icon-mobile"></i>
                        </button>
                        <button id="mobile-leave-btn" onclick="leave()"
                            class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center text-white active:scale-95 shadow-lg shadow-red-500/20 transition-all cursor-pointer relative z-[101]">
                            <i class="ri-phone-fill text-2xl rotate-[135deg]"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Footer Info -->
            @if(!$isMeetingPopout)
                <div class="p-6 bg-white flex items-center justify-between border-t border-gray-50">
                    <div class="flex items-center gap-3">
                        <i class="ri-shield-check-line text-secondary"></i>
                        <p class="text-xs text-gray-400 font-medium italic">
                            Secure encrypted session</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest" id="net-label">Network:
                            Stable</span>
                    </div>
                </div>
            @endif
        </div>

        @if(!$booking)
            <!-- PiP Placeholder for non-bookings (shown only when video is in PiP and no form is loaded) -->
            <div id="pip-placeholder"
                class="hidden flex-1 bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)] flex-col">
                <div class="p-8 flex flex-col items-center justify-center h-full text-center">
                    <div class="w-20 h-20 bg-secondary/5 rounded-3xl flex items-center justify-center mb-6">
                        <i class="ri-vidicon-fill text-secondary text-4xl animate-pulse"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-secondary mb-2">Live Session in Pop-out</h2>
                    <p class="text-gray-400 max-w-sm">The video window is currently floating. You can continue using this
                        dashboard as normal.</p>

                    @if($booking && $booking->translator_id)
                        <div class="mt-8 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100 flex items-center gap-4 text-left">
                            <img src="{{ $booking->translator->user->profile_pic ? (str_starts_with($booking->translator->user->profile_pic, 'http') ? $booking->translator->user->profile_pic : asset('storage/' . $booking->translator->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}"
                                class="w-12 h-12 rounded-xl object-cover border border-white shadow-sm">
                            <div>
                                <p class="text-[9px] text-blue-600 font-black uppercase tracking-widest mb-1">Assigned Translator
                                </p>
                                <p class="text-sm font-black text-secondary">{{ $booking->translator->full_name }}</p>
                                @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                                    <button
                                        onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language }}', '{{ $booking->to_language }}')"
                                        class="mt-2 text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline">Change
                                        Translator →</button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Consultation Form Area (Visible only when video is popped out) -->
        @if($booking)
            <div id="clinical-sidebar"
                class="hidden flex-1 bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)] flex-col">
                <div class="p-6 border-b border-[#2E4B3D]/12 flex items-center justify-between bg-[#FDFDFD]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary/5 rounded-xl flex items-center justify-center">
                            <i class="ri-file-list-3-line text-secondary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-secondary leading-none mb-1">Clinical Records</h2>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Client:
                                {{ $booking->user->name ?? 'Guest' }}
                            </p>
                        </div>
                    </div>

                    @if($booking->translator_id)
                        <div class="hidden md:flex items-center gap-3 px-4 py-2 bg-blue-50 rounded-full border border-blue-100">
                            <i class="ri-translate text-blue-600"></i>
                            <span class="text-[10px] font-black text-secondary uppercase tracking-widest">Translator:
                                {{ $booking->translator->full_name }}</span>
                            @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                                <button
                                    onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language }}', '{{ $booking->to_language }}')"
                                    class="ml-2 text-[9px] font-black text-blue-600 uppercase hover:underline">Change</button>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="flex-1 relative bg-gray-50/50 p-4 md:p-8">
                    <iframe src="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'minimal' => 1]) }}"
                        class="w-full h-full border-0" id="clinical-iframe"></iframe>
                </div>
            </div>
        @endif
    </div>

    @include('partials.refer-modal-scripts')

    @if($provider === 'agora')
        <!-- Settings Modal -->
        <div id="settings-modal"
            class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleSettings()"></div>
            <div class="relative bg-white rounded-[32px] p-8 w-full max-w-md shadow-2xl scale-95 transition-transform"
                id="settings-content">
                <h3 class="text-xl font-bold text-secondary mb-6 font-sans!">Device Settings</h3>
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Camera</label>
                        <select id="camera-select"
                            class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></select>
                    </div>
                    <div>
                        <label
                            class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Microphone</label>
                        <select id="mic-select"
                            class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></select>
                    </div>
                </div>
                <button onclick="applySettings()"
                    class="mt-8 w-full py-4 bg-secondary text-white rounded-xl font-bold hover:opacity-90 transition-all cursor-pointer">
                    Save & Apply
                </button>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .agora_video_player {
            object-fit: cover !important;
            border-radius: inherit;
        }

        #local-player video {
            transform: rotateY(180deg);
        }

        .remote-video-container {
            position: relative;
            flex: 1;
            min-width: 300px;
            height: 100%;
            border-radius: inherit;
            overflow: hidden;
            background: #000;
        }

        #jitsi-meet-container iframe,
        #daily-meet-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        #clinical-sidebar {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: none;
            /* Hide by default in normal mode */
        }

        #video-container {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #video-container.in-popout {
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            position: fixed;
            top: 0;
            left: 0;
        }

        #session-wrapper.pip-mode-active #clinical-sidebar {
            display: flex !important;
            flex: 1;
            width: 100%;
        }

        /* Logic to hide placeholder if sidebar (form) is present and active */
        #session-wrapper.pip-mode-active:has(#clinical-sidebar) #pip-placeholder {
            display: none !important;
        }

        #session-wrapper.pip-mode-active #pip-placeholder {
            display: flex !important;
        }

        #video-container.in-popout #conference-meeting-header,
        #video-container.in-popout #conference-mobile-nav,
        #video-container.in-popout button[onclick="leave()"],
        #session-wrapper.pip-mode-active #conference-meeting-header,
        #session-wrapper.pip-mode-active #conference-mobile-nav {
            display: none !important;
        }

        #session-wrapper.pip-active #video-container {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            width: 340px !important;
            height: 220px !important;
            z-index: 150;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            max-width: none !important;
            flex: none !important;
            border-radius: 24px;
            overflow: hidden;
            cursor: grab;
            display: flex;
            flex-direction: column;
            background: #000;
            will-change: transform;
            transition: box-shadow 0.3s ease, transform 0.1s ease-out;
        }

        #pip-controls {
            display: none;
            user-select: none;
        }

        #session-wrapper.pip-active #pip-controls {
            display: flex;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 12px 16px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
            z-index: 160;
            justify-content: space-between;
            align-items: center;
        }

        #session-wrapper.pip-active #conference-meeting-header,
        #session-wrapper.pip-active #conference-mobile-nav {
            display: none !important;
        }
    </style>
@endpush

@section('scripts')
    @if($provider === 'agora')
        <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js"></script>
    @elseif($provider === 'jaas')
        <script src="https://{{ $jaasDomain }}/{{ $jaasAppId }}/external_api.js"></script>
    @elseif($provider === 'daily')
        <script src="https://unpkg.com/@daily-co/daily-js"></script>
    @endif

    <script>     document.addEventListener('DOMContentLoaded', function () {
            const provider = "{{ $provider }}";
            const agoraAvailable = {{ $agoraAvailable ? 'true' : 'false' }};
            const channel = "{{ trim($channel) }}";
            const uid = {{ (int) ($user->id ?? 1) }};
            const jitsiDomain = "{{ $jaasDomain }}";
            const jitsiRoom = "{{ $jaasRoomName }}";
            const jitsiJwt = @json($jaasToken);
            const dailyUrl = "{{ $dailyUrl }}";
            const dailyToken = "{{ $dailyToken }}";
            const conferencesUrl = "{{ route('conferences.index') }}";
            const zegoUrl = "{{ route('zego.join', ['channel' => $channel]) }}";
            const bookingId = {{ (int) ($booking->id ?? 0) }};
            const uploadRecordingUrl = "{{ route('conference.upload-recording') }}";
            const csrfToken = "{{ csrf_token() }}";

            let jitsiApi = null;
            let dailyCall = null;
            let meetingState = { audioMuted: false, videoMuted: false, screenSharing: false };
            let meetingStartedAt = null;
            let isEndingFromPip = false;
            let currentPipWindow = null;
            let recordingState = { mediaRecorder: null, stream: null, chunks: [], startedAt: null, uploadPromise: null };

            // Timer Logic
            let timerInterval = null;
            function startTimer() {
                if (timerInterval) clearInterval(timerInterval);
                let timerStartTime = sessionStorage.getItem('meeting_timer_start') ? parseInt(sessionStorage.getItem('meeting_timer_start')) : Date.now();
                if (!sessionStorage.getItem('meeting_timer_start')) sessionStorage.setItem('meeting_timer_start', timerStartTime);
                const timerEl = document.getElementById('timer');
                timerInterval = setInterval(() => {
                    const diff = Math.floor((Date.now() - timerStartTime) / 1000);
                    const h = Math.floor(diff / 3600).toString().padStart(2, '0');
                    const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                    const s = (diff % 60).toString().padStart(2, '0');
                    if (timerEl) timerEl.innerText = `${h}:${m}:${s}`;
                }, 1000);
            }

            // Prevent Accidental Reload / Navigation
            let allowExit = false;
            window.addEventListener('beforeunload', function (e) {
                if (meetingStartedAt && !allowExit) {
                    const msg = "You are currently in a live session. Leaving this page will disconnect your call and end the session.";
                    e.preventDefault();
                    e.returnValue = msg;
                    return msg;
                }
            });

            // Intercept internal link clicks to show a cleaner confirmation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link || !meetingStartedAt || allowExit) return;

                // Only intercept internal links or links that would navigate away from the meeting
                const url = new URL(link.href, window.location.origin);
                if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                    e.preventDefault();
                    if (confirm("Are you sure you want to leave the live session? This will disconnect your call.")) {
                        allowExit = true;
                        window.location.href = link.href;
                    }
                }
            }, true);

            window.switchProvider = (nextProvider) => {
                allowExit = true;
                if (nextProvider === 'zegocloud') { window.location.href = zegoUrl; return; }
                const nextUrl = new URL(window.location.href);
                nextUrl.searchParams.set('provider', nextProvider);
                window.location.href = nextUrl.toString();
            };

            window.copyMeetingLink = () => {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    if (window.showZayaToast) showZayaToast('Meeting link copied!', 'Success', 'Live Session');
                    else alert('Meeting link copied!');
                });
            };

            window.togglePiP = () => {
                const wrapper = document.getElementById('session-wrapper');
                if (!wrapper) return;
                wrapper.classList.toggle('pip-active');
            };

            window.togglePopout = async () => {
                const videoContainer = document.getElementById('video-container');
                const wrapper = document.getElementById('session-wrapper');

                if (!videoContainer || !wrapper) return;

                if ('documentPictureInPicture' in window) {
                    try {
                        if (window.documentPictureInPicture.window) {
                            window.documentPictureInPicture.window.close();
                            return;
                        }

                        const pipWindow = await window.documentPictureInPicture.requestWindow({ width: 640, height: 480 });
                        currentPipWindow = pipWindow;
                        [...document.styleSheets].forEach((styleSheet) => {
                            try {
                                const cssRules = [...styleSheet.cssRules].map((rule) => rule.cssText).join('');
                                const style = document.createElement('style');
                                style.textContent = cssRules;
                                pipWindow.document.head.appendChild(style);
                            } catch (e) {
                                const link = document.createElement('link');
                                link.rel = 'stylesheet'; link.href = styleSheet.href;
                                pipWindow.document.head.appendChild(link);
                            }
                        });

                        videoContainer.classList.add('in-popout');
                        const mobileLeaveBtn = videoContainer.querySelector('#mobile-leave-btn');
                        if (mobileLeaveBtn) mobileLeaveBtn.style.display = 'none';

                        pipWindow.document.body.append(videoContainer);
                        wrapper.classList.add('pip-mode-active');

                        if (meetingStartedAt) {
                            if (provider === 'jaas') { if (jitsiApi) jitsiApi.dispose(); initJitsi(true); }
                            else if (provider === 'daily') {
                                if (dailyCall) {
                                    const oldCall = dailyCall; dailyCall = null;
                                    oldCall.leave().then(() => oldCall.destroy()).then(() => initDaily(true));
                                } else { initDaily(true); }
                            }
                        }

                        pipWindow.addEventListener('pagehide', () => {
                            currentPipWindow = null;
                            videoContainer.classList.remove('in-popout');
                            const mobileLeaveBtn = videoContainer.querySelector('#mobile-leave-btn');
                            if (mobileLeaveBtn) mobileLeaveBtn.style.display = '';

                            wrapper.prepend(videoContainer);
                            wrapper.classList.remove('pip-mode-active');
                            if (meetingStartedAt && (typeof allowExit === 'undefined' || !allowExit)) {
                                if (provider === 'jaas') { if (jitsiApi) jitsiApi.dispose(); initJitsi(true); }
                                else if (provider === 'daily') {
                                    if (dailyCall) {
                                        const oldCall = dailyCall; dailyCall = null;
                                        oldCall.leave().then(() => oldCall.destroy()).then(() => initDaily(true));
                                    } else { initDaily(true); }
                                }
                            }

                            if (isEndingFromPip) {
                                isEndingFromPip = false;
                                setTimeout(() => {
                                    if (window.showZayaToast) {
                                        showZayaToast('Call returned to main window. Please click end again to finish session.', 'warning', 'Live Session');
                                    }
                                }, 500);
                            }
                        });
                    } catch (err) { console.error('Failed to enter Document PiP:', err); }
                }
            };

            (function () {
                const pip = document.getElementById('video-container');
                const wrapper = document.getElementById('session-wrapper');
                let isDragging = false, startX, startY, currentTranslateX = 0, currentTranslateY = 0, lastTranslateX = 0, lastTranslateY = 0;

                const originalTogglePiP = window.togglePiP;
                window.togglePiP = () => { const wasPiP = wrapper.classList.contains('pip-active'); originalTogglePiP(); if (wasPiP) resetPipPosition(); };
                function resetPipPosition() { currentTranslateX = 0; currentTranslateY = 0; lastTranslateX = 0; lastTranslateY = 0; pip.style.transform = ''; pip.style.top = ''; pip.style.left = ''; pip.style.bottom = '2rem'; pip.style.right = 'auto'; }
                function onMouseDown(e) { if (!wrapper.classList.contains('pip-active')) return; if (e.target.closest('button') || e.target.closest('a')) return; isDragging = true; pip.classList.add('is-dragging'); startX = e.clientX - lastTranslateX; startY = e.clientY - lastTranslateY; document.addEventListener('mousemove', onMouseMove); document.addEventListener('mouseup', onMouseUp); }
                function onMouseMove(e) { if (!isDragging) return; currentTranslateX = e.clientX - startX; currentTranslateY = e.clientY - startY; pip.style.transform = `translate3d(${currentTranslateX}px, ${currentTranslateY}px, 0)`; }
                function onMouseUp() { isDragging = false; pip.classList.remove('is-dragging'); lastTranslateX = currentTranslateX; lastTranslateY = currentTranslateY; document.removeEventListener('mousemove', onMouseMove); document.removeEventListener('mouseup', onMouseUp); }
                pip.addEventListener('mousedown', onMouseDown);
            })();

            window.showSummary = () => {
                allowExit = true;
                localStorage.removeItem('meeting_active_' + channel);
                localStorage.removeItem('meeting_started_at_' + channel);
                sessionStorage.removeItem('meeting_timer_start');
                const modal = document.getElementById('summary-modal');
                const content = document.getElementById('summary-content');
                if (modal && content) { modal.classList.remove('hidden'); modal.offsetHeight; content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }
                else { window.location.href = conferencesUrl; }
            };

            window.leave = async () => {
                // If in pop-out mode, dispose provider and close window
                if (currentPipWindow || (window.documentPictureInPicture && window.documentPictureInPicture.window)) {
                    isEndingFromPip = true;
                    const pipWin = currentPipWindow || window.documentPictureInPicture.window;

                    try {
                        if (provider === 'jaas' && jitsiApi) { jitsiApi.dispose(); jitsiApi = null; }
                        if (provider === 'daily' && dailyCall) { await dailyCall.leave(); dailyCall.destroy(); dailyCall = null; }
                    } catch (e) { console.warn("Cleanup error in PiP:", e); }

                    pipWin.close();
                    return; // Stop here, the pagehide listener will handle re-init in main window
                }

                allowExit = true;

                await stopRecordingAndUpload();

                try {
                    if (provider === 'jaas' && jitsiApi) { jitsiApi.dispose(); jitsiApi = null; }
                    if (provider === 'daily' && dailyCall) { await dailyCall.leave(); dailyCall.destroy(); dailyCall = null; }
                } catch (err) {
                    console.warn("Cleanup error:", err);
                }

                window.showSummary();
            };

            window.startSession = async (isResume = false) => {
                const btn = document.getElementById('start-btn'), feedback = document.getElementById('setup-feedback');
                if (!isResume) {
                    if (btn) btn.disabled = true; if (feedback) feedback.classList.remove('hidden');
                    meetingStartedAt = new Date().toISOString();
                    localStorage.setItem('meeting_active_' + channel, 'true');
                    localStorage.setItem('meeting_started_at_' + channel, meetingStartedAt);
                } else { meetingStartedAt = localStorage.getItem('meeting_started_at_' + channel) || new Date().toISOString(); hideOverlay(true); }
                startTimer();
                if (provider === 'jaas') initJitsi(isResume);
                else if (provider === 'daily') initDaily(isResume);
            };

            if (localStorage.getItem('meeting_active_' + channel) === 'true') { hideOverlay(true); setTimeout(() => { window.startSession(true); }, 100); }

            function initJitsi(isResume = false) {
                let container = document.getElementById('jitsi-meet-container');
                if (!container && window.documentPictureInPicture?.window) container = window.documentPictureInPicture.window.document.getElementById('jitsi-meet-container');
                if (!container) return;

                let vCont = document.getElementById('video-container');
                if (!vCont && window.documentPictureInPicture?.window) vCont = window.documentPictureInPicture.window.document.getElementById('video-container');
                const isInPip = (vCont && vCont.classList.contains('in-popout')) || {{ $isMeetingPopout ? 'true' : 'false' }};

                let toolbarButtons = [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security',
                    'whiteboard'
                ];

                if (isInPip) {
                    toolbarButtons = toolbarButtons.filter(btn => btn !== 'hangup');
                }

                jitsiApi = new JitsiMeetExternalAPI(jitsiDomain, {
                    roomName: jitsiRoom, parentNode: container, jwt: jitsiJwt,
                    configOverwrite: {
                        prejoinPageEnabled: !isResume, prejoinConfig: { enabled: !isResume, hideDisplayName: true },
                        readOnlyName: true, disableProfile: true, disableReactions: true,
                        toolbarButtons: toolbarButtons,
                        disabledNotifications: ['moderator', 'notify.moderator', 'notify.connected-as-moderator', 'connection.connected-as-moderator'],
                        disableModeratorIndicator: true
                    },
                    interfaceConfigOverwrite: { SHOW_JITSI_WATERMARK: false, DISABLE_PROFILE: true, TOOLBAR_ALWAYS_VISIBLE: true },
                    userInfo: { displayName: "{{ addslashes($user->name ?? 'Guest') }}" }
                });
                jitsiApi.addEventListeners({ audioMuteStatusChanged: (e) => { meetingState.audioMuted = e.muted; updateIcons(); }, videoMuteStatusChanged: (e) => { meetingState.videoMuted = e.muted; updateIcons(); }, readyToClose: () => { window.leave(); } });
                hideOverlay();
            }

            async function initDaily(isResume = false) {
                let container = document.getElementById('daily-meet-container');
                if (!container && window.documentPictureInPicture?.window) container = window.documentPictureInPicture.window.document.getElementById('daily-meet-container');
                if (!container) return;

                let vCont = document.getElementById('video-container');
                if (!vCont && window.documentPictureInPicture?.window) vCont = window.documentPictureInPicture.window.document.getElementById('video-container');
                const isInPip = (vCont && vCont.classList.contains('in-popout')) || {{ $isMeetingPopout ? 'true' : 'false' }};

                dailyCall = DailyIframe.createFrame(container, {
                    showLeaveButton: !isInPip,
                    iframeStyle: { width: '100%', height: '100%', border: '0' }
                });
                dailyCall.on('joined-meeting', () => { hideOverlay(); });
                dailyCall.on('left-meeting', () => { if (typeof allowExit !== 'undefined' && !allowExit) window.leave(); else window.showSummary(); });
                const joinOptions = { url: dailyUrl }; if (dailyToken) joinOptions.token = dailyToken; await dailyCall.join(joinOptions);
                dailyCall.on('participant-updated', (e) => { if (e.participant.local) { meetingState.audioMuted = !e.participant.audio; meetingState.videoMuted = !e.participant.video; updateIcons(); } });
            }

            function updateIcons() {
                const micIcon = meetingState.audioMuted ? 'ri-mic-off-fill text-red-500' : 'ri-mic-fill', vidIcon = meetingState.videoMuted ? 'ri-video-off-fill text-red-500' : 'ri-video-on-fill';
                if (document.getElementById('mic-icon')) document.getElementById('mic-icon').className = micIcon;
                if (document.getElementById('mic-icon-mobile')) document.getElementById('mic-icon-mobile').className = micIcon;
                if (document.getElementById('vid-icon')) document.getElementById('vid-icon').className = vidIcon;
                if (document.getElementById('vid-icon-mobile')) document.getElementById('vid-icon-mobile').className = vidIcon;
            }

            function hideOverlay(immediate = false) {
                const overlay = document.getElementById('join-overlay');
                if (overlay) { if (immediate) { overlay.remove(); return; } overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; setTimeout(() => overlay.remove(), 500); }
            }

            const audioBtn = document.getElementById('audio-toggle'), videoBtn = document.getElementById('video-toggle'), screenBtn = document.getElementById('screen-share-btn'), recordBtn = document.getElementById('record-toggle'), recordMobileBtn = document.getElementById('record-toggle-mobile');
            if (audioBtn) audioBtn.onclick = () => { if (provider === 'jaas') jitsiApi.executeCommand('toggleAudio'); if (provider === 'daily') dailyCall.setLocalAudio(meetingState.audioMuted); };
            if (videoBtn) videoBtn.onclick = () => { if (provider === 'jaas') jitsiApi.executeCommand('toggleVideo'); if (provider === 'daily') dailyCall.setLocalVideo(meetingState.videoMuted); };
            if (screenBtn) screenBtn.onclick = () => { if (provider === 'jaas') jitsiApi.executeCommand('toggleShareScreen'); if (provider === 'daily') { if (meetingState.screenSharing) dailyCall.stopScreenShare(); else dailyCall.startScreenShare(); meetingState.screenSharing = !meetingState.screenSharing; } };
            if (recordBtn) recordBtn.onclick = () => toggleRecording();
            if (recordMobileBtn) recordMobileBtn.onclick = () => toggleRecording();

            function updateRecordingUi(isRecording) {
                const indicator = document.getElementById('recording-indicator'), iconClass = isRecording ? 'ri-stop-circle-line text-red-500' : 'ri-record-circle-line';
                if (indicator) indicator.classList.toggle('hidden', !isRecording);
                if (indicator) indicator.classList.toggle('flex', isRecording);
                if (document.getElementById('record-icon')) document.getElementById('record-icon').className = `text-xl ${iconClass}`;
                if (document.getElementById('record-icon-mobile')) document.getElementById('record-icon-mobile').className = `text-2xl ${iconClass}`;
            }

            async function toggleRecording() { if (recordingState.mediaRecorder && recordingState.mediaRecorder.state === 'recording') { await stopRecordingAndUpload(); return; } await beginRecording(false); }

            async function beginRecording(isAutoStart) {
                if (!bookingId || recordingState.mediaRecorder) return;
                try {
                    const stream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });
                    const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp9,opus') ? 'video/webm;codecs=vp9,opus' : 'video/webm';
                    recordingState.stream = stream; recordingState.chunks = []; recordingState.startedAt = new Date().toISOString();
                    recordingState.mediaRecorder = new MediaRecorder(stream, { mimeType });
                    recordingState.mediaRecorder.ondataavailable = (event) => { if (event.data && event.data.size > 0) recordingState.chunks.push(event.data); };
                    recordingState.mediaRecorder.onstop = () => { recordingState.uploadPromise = uploadRecording(); };
                    stream.getVideoTracks().forEach((track) => { track.addEventListener('ended', () => { if (recordingState.mediaRecorder && recordingState.mediaRecorder.state === 'recording') recordingState.mediaRecorder.stop(); }); });
                    recordingState.mediaRecorder.start(1000); updateRecordingUi(true);
                } catch (error) { if (!isAutoStart) alert('Recording could not start.'); console.warn('Recording start skipped:', error); }
            }

            async function stopRecordingAndUpload() {
                if (!recordingState.mediaRecorder) return;
                const recorder = recordingState.mediaRecorder; if (recorder.state !== 'inactive') recorder.stop();
                if (recordingState.stream) recordingState.stream.getTracks().forEach((track) => track.stop());
                updateRecordingUi(false);
                const pendingUpload = recordingState.uploadPromise || new Promise((resolve) => {
                    const check = setInterval(() => { if (recordingState.uploadPromise) { clearInterval(check); resolve(recordingState.uploadPromise); } }, 150);
                    setTimeout(() => { clearInterval(check); resolve(null); }, 4000);
                });
                await pendingUpload; recordingState.mediaRecorder = null; recordingState.stream = null; recordingState.uploadPromise = null;
            }

            async function uploadRecording() {
                if (!recordingState.chunks.length || !bookingId) return;
                const blob = new Blob(recordingState.chunks, { type: recordingState.chunks[0].type || 'video/webm' }), extension = blob.type.includes('mp4') ? 'mp4' : 'webm';
                const formData = new FormData();
                formData.append('booking_id', String(bookingId)); formData.append('provider', provider);
                formData.append('room_name', channel); formData.append('start_time', meetingStartedAt || recordingState.startedAt || new Date().toISOString());
                formData.append('end_time', new Date().toISOString()); formData.append('recording', blob, `session-recording-${bookingId}.${extension}`);
                try { await fetch(uploadRecordingUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData }); } catch (error) { console.error('Recording upload failed:', error); } finally { recordingState.chunks = []; }
            }
        });
    <script>
        let currentRating = 0;
        function setRating(rating) {
            currentRating = rating;
            const stars = document.querySelectorAll('.star-rating i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.replace('ri-star-line', 'ri-star-fill');
                    star.classList.add('text-[#FFD166]');
                } else {
                    star.classList.replace('ri-star-fill', 'ri-star-line');
                    star.classList.remove('text-[#FFD166]');
                }
            });
            document.getElementById('rating-input').value = rating;
        }

        async function submitReview() {
            const btn = document.getElementById('submit-review-btn');
            const review = document.getElementById('review-text').value;
            const practitionerId = "{{ $booking->profile_id ?? '' }}";
            const csrfToken = "{{ csrf_token() }}";

            if (!currentRating) { showZayaToast('Please provide a star rating.', 'warning', 'Review'); return; }
            if (!review.trim()) { showZayaToast('Please share a few words about your experience.', 'warning', 'Review'); return; }

            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Submitting...';

            try {
                const response = await fetch("{{ route('reviews.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        practitioner_id: practitionerId,
                        rating: currentRating,
                        review: review
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('feedback-section').innerHTML = `
                        <div class="py-10 text-center animate-fade-in">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-checkbox-circle-fill text-3xl"></i>
                            </div>
                            <h4 class="font-black text-secondary uppercase tracking-widest text-xs">Review Submitted</h4>
                            <p class="text-sm text-gray-500 mt-2">Thank you for sharing your story of transformation!</p>
                        </div>
                    `;
                } else {
                    showZayaToast(data.message || 'Failed to submit review.', 'error', 'Error');
                    btn.disabled = false;
                    btn.innerText = 'Submit Review';
                }
            } catch (error) {
                console.error('Review submission error:', error);
                showZayaToast('A connection error occurred.', 'error', 'Error');
                btn.disabled = false;
                btn.innerText = 'Submit Review';
            }
        }
    </script>
@endsection
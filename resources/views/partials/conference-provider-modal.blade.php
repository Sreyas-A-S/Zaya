<!-- Platform Selection Modal -->
<div id="conference-platform-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 px-4">
    <div class="absolute inset-0 bg-[#07110B]/60 backdrop-blur-md" onclick="closePlatformModal()"></div>
    <div class="relative bg-white rounded-[40px] p-8 md:p-12 w-full max-w-2xl shadow-2xl scale-95 transition-transform overflow-hidden" id="platform-modal-content">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-secondary/5 rounded-full -ml-12 -mb-12"></div>

        <div class="relative">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold text-secondary mb-2 font-sans!">Choose Platform</h3>
                    <p class="text-gray-400 text-sm md:text-base">Select your preferred video platform to start the consultation.</p>
                </div>
                <button onclick="closePlatformModal()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-secondary hover:bg-gray-100 transition-all">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <!-- Google Meet -->
                <button onclick="redirectToPlatform('google_meet')" class="group p-8 bg-[#FDFDFD] border border-[#2E4B3D]/10 rounded-[32px] hover:border-secondary hover:bg-secondary/5 transition-all text-left relative overflow-hidden">
                    <div class="w-14 h-14 bg-white border border-gray-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-gray-200/50">
                        <svg class="w-8 h-8" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            <path fill="none" d="M0 0h48v48H0z"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-xl font-bold text-secondary mb-1">Google Meet</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Start an instant meeting via Google Meet in a new browser tab.</p>
                    </div>
                    <div class="absolute bottom-6 right-8 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i class="ri-arrow-right-line text-secondary text-2xl"></i>
                    </div>
                </button>


                <!-- Help Info -->
                <div class="p-6 bg-gray-50 rounded-[32px] flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm shrink-0">
                        <i class="ri-information-line text-secondary"></i>
                    </div>
                    <p class="text-[11px] text-gray-400 font-medium">All platforms are end-to-end encrypted and HIPAA compliant.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentChannel = '';

    window.openPlatformModal = (channel = '') => {
        currentChannel = channel || 'zaya-instant-placeholder';
        const modal = document.getElementById('conference-platform-modal');
        const content = document.getElementById('platform-modal-content');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    };

    window.closePlatformModal = () => {
        const modal = document.getElementById('conference-platform-modal');
        const content = document.getElementById('platform-modal-content');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.classList.remove('opacity-100');
        content.classList.add('scale-95');
        content.classList.remove('scale-100');
        document.body.style.overflow = '';
    };

    window.redirectToPlatform = (provider) => {
        // If we have a currentChannel (from a scheduled booking), use it
        // If not, it's an ad-hoc instant meeting
        if (currentChannel && !currentChannel.startsWith('zaya-instant-')) {
            const url = provider === 'zegocloud'
                ? "{{ route('zego.join', ['channel' => ':channel']) }}".replace(':channel', currentChannel)
                : "{{ route('conference.join', ['channel' => ':channel']) }}".replace(':channel', currentChannel);

            window.open(url + '?provider=' + provider, '_blank');
            closePlatformModal();
        } else {
            // It's an ad-hoc meeting, use the handshake flow
            if (typeof startInstantMeeting === 'function') {
                startInstantMeeting(provider);
            } else {
                console.error('startInstantMeeting function not found');
            }
        }
    };
</script>

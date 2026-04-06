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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- JaaS (8x8) -->
                <button onclick="redirectToPlatform('jaas')" class="group p-6 bg-[#FDFDFD] border border-[#2E4B3D]/10 rounded-[32px] hover:border-secondary hover:bg-secondary/5 transition-all text-left relative overflow-hidden">
                    <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20">
                        <i class="ri-shield-user-fill text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">JaaS (8x8)</h4>
                    <p class="text-gray-400 text-xs leading-relaxed">High-quality, secure enterprise video by Jitsi. Best for standard sessions.</p>
                    <div class="absolute bottom-4 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i class="ri-arrow-right-line text-secondary text-xl"></i>
                    </div>
                </button>

                <!-- Daily.co -->
                <button onclick="redirectToPlatform('daily')" class="group p-6 bg-[#FDFDFD] border border-[#2E4B3D]/10 rounded-[32px] hover:border-secondary hover:bg-secondary/5 transition-all text-left relative overflow-hidden">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-orange-500/20">
                        <i class="ri-flashlight-fill text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Daily.co</h4>
                    <p class="text-gray-400 text-xs leading-relaxed">Simple, fast, and reliable browser-based video. Modern prebuilt interface.</p>
                    <div class="absolute bottom-4 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i class="ri-arrow-right-line text-secondary text-xl"></i>
                    </div>
                </button>

                <!-- Agora -->
                <button onclick="redirectToPlatform('agora')" class="group p-6 bg-[#FDFDFD] border border-[#2E4B3D]/10 rounded-[32px] hover:border-secondary hover:bg-secondary/5 transition-all text-left relative overflow-hidden">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-emerald-500/20">
                        <i class="ri-broadcast-fill text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Agora</h4>
                    <p class="text-gray-400 text-xs leading-relaxed">Low-latency global real-time engagement. Custom embedded experience.</p>
                    <div class="absolute bottom-4 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i class="ri-arrow-right-line text-secondary text-xl"></i>
                    </div>
                </button>

                <!-- ZEGOCLOUD -->
                <button onclick="redirectToPlatform('zegocloud')" class="group p-6 bg-[#FDFDFD] border border-[#2E4B3D]/10 rounded-[32px] hover:border-secondary hover:bg-secondary/5 transition-all text-left relative overflow-hidden">
                    <div class="w-12 h-12 bg-fuchsia-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-fuchsia-500/20">
                        <i class="ri-apps-2-fill text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">ZEGOCLOUD</h4>
                    <p class="text-gray-400 text-xs leading-relaxed">Prebuilt ZEGOCLOUD meeting UI for fast hosted video sessions.</p>
                    <div class="absolute bottom-4 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i class="ri-arrow-right-line text-secondary text-xl"></i>
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
        currentChannel = channel || 'zaya-' + Math.random().toString(36).substring(2, 12);
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
        const url = provider === 'zegocloud'
            ? "{{ route('zego.join', ['channel' => ':channel']) }}".replace(':channel', currentChannel)
            : "{{ route('conference.join', ['channel' => ':channel']) }}".replace(':channel', currentChannel);

        window.open(url + '?provider=' + provider, '_blank');
        closePlatformModal();
    };
</script>

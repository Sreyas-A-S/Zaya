<!-- Join As Modal -->
<div id="joinAsModal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="popup-backdrop absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-200"
        onclick="closeJoinAsModal()"></div>

    <!-- Modal Content -->
    <div class="popup-content relative w-full max-w-[720px] bg-white rounded-[32px] shadow-2xl p-8 md:p-10 opacity-0 scale-95 transition-all duration-200">
        <button type="button" onclick="closeJoinAsModal()"
            class="absolute top-5 right-6 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <h3 class="text-2xl md:text-3xl font-serif font-bold text-primary text-center mb-3">{{ __('Join Us As') }}</h3>
        <p class="text-gray-500 text-center mb-8">{{ __('Select your role to continue') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('practitioner-register') }}"
                class="border border-gray-200 rounded-2xl p-5 hover:border-secondary hover:bg-[#FFF7EF] transition-all text-center">
                <div class="text-secondary text-2xl mb-2"><i class="ri-briefcase-line"></i></div>
                <div class="text-gray-900 font-medium">{{ __('Practitioner') }}</div>
            </a>

            <a href="{{ route('join.register', ['role' => 'doctor']) }}"
                class="border border-gray-200 rounded-2xl p-5 hover:border-secondary hover:bg-[#FFF7EF] transition-all text-center">
                <div class="text-secondary text-2xl mb-2"><i class="ri-stethoscope-line"></i></div>
                <div class="text-gray-900 font-medium">{{ __('Doctor') }}</div>
            </a>

            <a href="{{ route('join.register', ['role' => 'yoga-therapist']) }}"
                class="border border-gray-200 rounded-2xl p-5 hover:border-secondary hover:bg-[#FFF7EF] transition-all text-center">
                <div class="text-secondary text-2xl mb-2"><i class="ri-leaf-line"></i></div>
                <div class="text-gray-900 font-medium">{{ __('Yoga Therapist') }}</div>
            </a>

            <a href="{{ route('join.register', ['role' => 'mindfulness-practitioner']) }}"
                class="border border-gray-200 rounded-2xl p-5 hover:border-secondary hover:bg-[#FFF7EF] transition-all text-center">
                <div class="text-secondary text-2xl mb-2"><i class="ri-mental-health-line"></i></div>
                <div class="text-gray-900 font-medium">{{ __('Mindfulness Practitioner') }}</div>
            </a>

            <a href="{{ route('join.register', ['role' => 'translator']) }}"
                class="border border-gray-200 rounded-2xl p-5 hover:border-secondary hover:bg-[#FFF7EF] transition-all text-center">
                <div class="text-secondary text-2xl mb-2"><i class="ri-translate-2"></i></div>
                <div class="text-gray-900 font-medium">{{ __('Translator') }}</div>
            </a>
        </div>

        <div class="mt-8 flex justify-center">
            <button type="button" onclick="closeJoinAsModal()"
                class="bg-[#F5A623] text-[#423131] py-3 px-10 rounded-full font-normal text-base transition-all duration-300 hover:bg-[#A87139] hover:text-white">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

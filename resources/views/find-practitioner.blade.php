@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto text-center pt-0 pb-8 md:py-10">
            <h1 class="text-2xl  sm:text-3xl md:text-5xl font-serif font-bold text-primary mb-5">Experts in Your
                Neighborhood</h1>
            <p class="text-xl md:text-2xl text-secondary font-normal font-serif mb-8">Verified practitioners ready to
                support your journey</p>
            <p class="text-sm md:text-base text-gray-500 max-w-2xl mx-auto">
                Find the support you need, right in your community. Every practitioner listed here is part
                of ZAYA's practitioner-led network, committed to ethical care and holistic healing.
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="px-4 md:px-6 mb-10 md:mb-20 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row gap-4 lg:gap-6 items-center">
                <!-- Pincode Input -->
                <div class="relative w-full md:w-1/3">
                    <input type="text" placeholder="Enter Pincode"
                        class="w-full border border-[#db8871] rounded-full px-6 py-3.5 pr-12 text-base md:text-lg text-[#db8871] placeholder-[#db8871] focus:outline-none bg-white transition-colors">
                    <button
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#F39551] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                </div>

                <!-- Select Service Custom Dropdown -->
                <div class="relative w-full md:w-1/3 custom-dropdown">
                    <input type="hidden" name="service" value="">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-base md:text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">Select Service</span>
                        <i
                            class="ri-arrow-down-s-line text-[#db8871] text-xl transition-transform duration-300 pointer-events-none dropdown-icon"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="ayurveda">Ayurveda & Panchakarma</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="mindfulness">Mindfulness Practitioner</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="yoga">Yoga Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="art">Art Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none"
                                data-value="clinical">Clinical Psychology</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="sound">Sound Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="hypno">Hypnotherapy</button>
                        </div>
                    </div>
                </div>

                <!-- Select Mode Custom Dropdown -->
                <div class="relative w-full md:w-1/3 custom-dropdown">
                    <input type="hidden" name="mode" value="">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-base md:text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">Select Mode</span>
                        <i
                            class="ri-arrow-down-s-line text-[#db8871] text-xl transition-transform duration-300 pointer-events-none dropdown-icon"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="online">Online</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="offline">Offline</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="both">Online & Offline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="px-4 md:px-6 pb-16 md:pb-24 bg-white">
        <div class="container mx-auto max-w-6xl">
            <!-- Results Heading -->
            <h2 class="text-center text-lg md:text-3xl font-semibold text-primary font-sans! mb-10 md:mb-24">
                Search Results Based on <span class="font-bold text-gray-900">'Kazhakuttam, Trivandrum, Kerala,
                    India'</span>
            </h2>

            <!-- Practitioner Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-12">
                @foreach($practitioners as $p)
                    <a href="{{ $p->slug ? route('practitioner-detail', ['slug' => $p->slug]) : '#' }}"
                        class="flex flex-col items-center text-center group cursor-pointer">
                        <!-- Avatar -->
                        <div
                            class="w-32 h-32 md:w-[150px] md:h-[150px] mb-4 overflow-hidden rounded-full border border-gray-100">
                            <img src="{{ $p->profile_photo_path ? asset('storage/' . $p->profile_photo_path) : asset('frontend/assets/lilly-profile-pic.png') }}"
                                alt="{{ $p->first_name }}"
                                class="w-full h-full object-cover rounded-full transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <!-- Name -->
                        <h3
                            class="font-sans! text-base md:text-lg lg:text-xl font-medium text-primary group-hover:opacity-80 transition-opacity duration-300">
                            {{ $p->first_name }} {{ $p->last_name }}
                        </h3>

                        <!-- Role -->
                        <p class="font-serif text-sm md:text-base lg:text-lg italic text-secondary mt-0.5">
                            {{ $p->other_modalities[0] ?? ($p->consultations[0] ?? 'Holistic Practitioner') }}
                        </p>

                        <!-- Location -->
                        <div class="mt-2 text-xs lg:text-sm text-gray-500">
                            <i class="ri-map-pin-line text-gray-800"></i>
                            <span>{{ $p->city_state }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Load More Button -->
            <!-- ⚠️ Hide this button if full list is loaded -->
            <div class="text-center mt-16">
                <button
                    class="border border-secondary text-secondary px-8 py-2.5 rounded-full text-[15px] font-medium hover:border-primary hover:text-primary transition-all duration-300 cursor-pointer bg-white">
                    Load More Profiles
                </button>
            </div>
        </div>
    </section>

    <!-- Dropdown Styles directly in section to bypass missing @stack('styles') -->
    <style>
        /* Custom Scrollbar for Dropdowns */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #E5E7EB;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #D1D5DB;
        }

        /* Open State Classes added via JS */
        .dropdown-open .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        .dropdown-open .dropdown-button .dropdown-icon {
            transform: rotate(180deg);
        }
    </style>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdowns = document.querySelectorAll('.custom-dropdown');

            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('.dropdown-button');
                const menu = dropdown.querySelector('.dropdown-menu');
                const items = dropdown.querySelectorAll('.dropdown-item');
                const selectedText = dropdown.querySelector('.dropdown-selected');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');

                // Toggle dropdown
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Close all other dropdowns first
                    dropdowns.forEach(other => {
                        if (other !== dropdown) {
                            other.classList.remove('dropdown-open');
                        }
                    });
                    dropdown.classList.toggle('dropdown-open');
                });

                // Handle Item Click
                items.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const value = item.getAttribute('data-value');
                        const text = item.textContent;

                        // Update selected value
                        selectedText.textContent = text;
                        hiddenInput.value = value;

                        // Close dropdown
                        dropdown.classList.remove('dropdown-open');

                        // Add active styling to selected item
                        items.forEach(i => {
                            i.classList.remove('font-medium', 'text-[#db8871]');
                        });
                        item.classList.add('font-medium', 'text-[#db8871]');
                    });
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.custom-dropdown')) {
                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('dropdown-open');
                    });
                }
            });
        });
    </script>
@endpush
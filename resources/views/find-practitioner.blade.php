@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto text-center py-8 md:py-10">
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-primary mb-5">Experts in Your Neighborhood
            </h1>
            <p class="text-xl md:text-2xl text-secondary font-normal font-serif mb-8">Verified practitioners ready to
                support
                your journey</p>
            <p class="text-sm md:text-base text-gray-500 max-w-2xl mx-auto">
                Find the support you need, right in your community. Every practitioner listed here is part
                of ZAYA's practitioner-led network, committed to ethical care and holistic healing.
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="px-4 md:px-6 pb-4 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row gap-4 lg:gap-6 items-center">
                <!-- Pincode Input -->
                <div class="relative w-full md:w-1/3">
                    <input type="text" placeholder="695582"
                        class="w-full border border-[#db8871] rounded-full px-6 py-3.5 pr-12 text-lg text-[#db8871] placeholder-[#db8871] focus:outline-none bg-white transition-colors">
                    <button
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#F39551] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                </div>

                <!-- Select Service Custom Dropdown -->
                <div class="relative w-full md:w-1/3 custom-dropdown">
                    <input type="hidden" name="service" value="">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">Select Service</span>
                        <i
                            class="ri-arrow-down-s-line text-[#db8871] text-xl transition-transform duration-300 pointer-events-none dropdown-icon"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="ayurveda">Ayurveda & Panchakarma</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="mindfulness">Mindfulness Practitioner</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="yoga">Yoga Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="art">Art Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none"
                                data-value="clinical">Clinical Psychology</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="sound">Sound Therapy</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="hypno">Hypnotherapy</button>
                        </div>
                    </div>
                </div>

                <!-- Select Mode Custom Dropdown -->
                <div class="relative w-full md:w-1/3 custom-dropdown">
                    <input type="hidden" name="mode" value="">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">Select Mode</span>
                        <i
                            class="ri-arrow-down-s-line text-[#db8871] text-xl transition-transform duration-300 pointer-events-none dropdown-icon"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="online">Online</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="offline">Offline</button>
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="both">Online & Offline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="px-4 md:px-6 pt-10 pb-16 md:pb-24 bg-white">
        <div class="container mx-auto max-w-6xl">
            <!-- Results Heading -->
            <h2 class="text-center text-lg md:text-3xl font-semibold text-primary font-sans! mb-14">
                Search Results Based on <span class="font-bold text-gray-900">'Kazhakuttam, Trivandrum, Kerala,
                    India'</span>
            </h2>

            @php
                $practitioners = [
                    ['name' => 'Diya', 'role' => 'Life Coach', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Hriday', 'role' => 'Yoga Therapist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Lily', 'role' => 'Art Therapist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Jeeva', 'role' => 'Spiritual Guide', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&h=300&fit=crop&crop=face'],

                    ['name' => 'Jeeva', 'role' => 'Spiritual Guide', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Nahala', 'role' => 'Sophrologist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Hriday', 'role' => 'Yoga Therapist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Diya', 'role' => 'Life Coach', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&h=300&fit=crop&crop=face'],

                    ['name' => 'Hriday', 'role' => 'Yoga Therapist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Diya', 'role' => 'Life Coach', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Jeeva', 'role' => 'Spiritual Guide', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=300&h=300&fit=crop&crop=face'],
                    ['name' => 'Lily', 'role' => 'Art Therapist', 'location' => 'Kazhakuttam', 'avatar' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=300&h=300&fit=crop&crop=face'],
                ];
            @endphp

            <!-- Practitioner Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-12">
                @foreach($practitioners as $practitioner)
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <!-- Avatar -->
                        <div class="w-32 h-32 md:w-[150px] md:h-[150px] mb-4 overflow-hidden rounded-full">
                            <img src="{{ $practitioner['avatar'] }}" alt="{{ $practitioner['name'] }}"
                                class="w-full h-full object-cover rounded-full transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <!-- Name -->
                        <h3
                            class="font-sans! text-xl md:text-2xl font-medium text-primary group-hover:opacity-80 transition-opacity duration-300">
                            {{ $practitioner['name'] }}
                        </h3>

                        <!-- Role -->
                        <p class="font-serif text-lg italic text-secondary mt-0.5">{{ $practitioner['role'] }}</p>

                        <!-- Location -->
                        <div class="flex items-center justify-center gap-1 mt-2 text-sm text-gray-500">
                            <i class="ri-map-pin-line text-gray-800"></i>
                            <span>{{ $practitioner['location'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
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
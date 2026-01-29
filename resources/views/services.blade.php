@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
    <div class="container mx-auto">
        <!-- Text Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20 mb-12 md:mb-16">
            <!-- Left Text -->
            <div>
                <div class="mb-8 animate-on-scroll">
                    <span class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                        Our Services
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 leading-tight">
                    Embrace Holistic <br> Wellness
                </h1>
            </div>

            <!-- Right Text -->
            <div class="col-span-2 pt-2 lg:pt-4">
                <h2 class="text-2xl md:text-[28px] font-serif text-secondary mb-6 leading-snug">
                    Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.
                </h2>
                <p class="text-gray-500 leading-relaxed text-base font-light">
                    ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in
                    traditional Indian wisdom. Every service offered on our platform is provided by a practitioner whose
                    background in Ayurveda, Yoga, or holistic health has been rigorously reviewed by our Approval
                    Commission.
                </p>
            </div>
        </div>

        <!-- Full Width Image -->
        <div class="w-full overflow-hidden group">
            <img src="{{ asset('frontend/assets/services-page-bg.png') }}" alt="Holistic Wellness"
                class="w-full h-[400px] object-cover align-top scale-110 transition-all duration-1000 group-hover:scale-125">
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white px-4 md:px-6">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat 1 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 class="text-5xl font-medium !font-sans text-black mb-4">300</h3>
                <p class="text-gray-500 font-medium text-[15px]">Sessions Completed</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 class="text-5xl font-medium !font-sans text-black mb-4">50+</h3>
                <p class="text-gray-500 font-medium text-[15px]">Certified Practitioners</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 class="text-5xl font-medium !font-sans text-black mb-4">99%</h3>
                <p class="text-gray-500 font-medium text-[15px]">Positive Feedbacks</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 class="text-5xl font-medium !font-sans text-black mb-4">10</h3>
                <p class="text-gray-500 font-medium text-[15px]">Years of Tradition</p>
            </div>
        </div>
    </div>
</section>

    <!-- Search Section -->
    <section class="pb-20 bg-white px-4 md:px-6">
        <div class="container mx-auto">
            <div class="mx-auto">
                <div class="flex flex-col md:flex-row gap-6">

                <!-- Category Custom Dropdown -->
                <div class="flex-1 relative" id="category-dropdown">
                    <button type="button" onclick="toggleDropdown()"
                        class="w-full h-[72px] flex items-center justify-between border border-[#D4A58E] rounded-full pl-8 pr-8 bg-white text-[#C5896B] text-lg shadow-sm hover:shadow-md transition-all outline-none focus:ring-1 focus:ring-[#D4A58E] font-sans cursor-pointer">
                        <span id="selected-category">Select Category</span>
                        <i class="ri-arrow-down-s-line text-2xl transition-transform duration-300"
                            id="dropdown-arrow"></i>
                    </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu"
                            class="absolute top-full left-0 w-full mt-2 bg-white rounded-[2rem] shadow-xl border border-[#efe6e1] overflow-hidden opacity-0 invisible transform -translate-y-2 transition-all duration-300 z-50">
                            <div class="px-2 py-2 my-2 flex flex-col gap-1 max-h-[300px] overflow-y-auto">
                                <a href="javascript:void(0)" onclick="selectCategory('Ayurveda')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Ayurveda</a>
                                <a href="javascript:void(0)" onclick="selectCategory('Yoga')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Yoga</a>
                                <a href="javascript:void(0)" onclick="selectCategory('Mindfulness')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Mindfulness</a>
                                <a href="javascript:void(0)" onclick="selectCategory('Spiritual Guidance')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Spiritual
                                    Guidance</a>
                                <a href="javascript:void(0)" onclick="selectCategory('Eat better')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Eat
                                    better</a>
                                <a href="javascript:void(0)" onclick="selectCategory('Stress management')"
                                    class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">Stress
                                    management</a>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div
                        class="flex-1 relative flex items-center border border-[#D4A58E] rounded-full p-2 bg-white shadow-sm hover:shadow-md transition-shadow h-[72px]">
                        <div class="pl-6 pr-4 text-[#C5896B]">
                            <i class="ri-search-line text-2xl"></i>
                        </div>
                        <input type="text" placeholder="Search"
                            class="flex-1 outline-none text-[#A67B5B] text-lg bg-transparent placeholder-[#C5896B] font-sans h-full">
                        <button
                            class="bg-[#C5896B] hover:bg-[#B07459] cursor-pointer text-white w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-md">
                            <i class="ri-search-line text-xl"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    <!-- Inline Script for Dropdown -->
    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');

            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.add('opacity-100', 'translate-y-0');
                arrow.classList.add('rotate-180');
            } else {
                menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
                arrow.classList.remove('rotate-180');
            }
        }

        function selectCategory(value) {
            document.getElementById('selected-category').textContent = value;
            toggleDropdown();
        }

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('category-dropdown');
            const menu = document.getElementById('dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');

                if (dropdown && !dropdown.contains(event.target) && !menu.classList.contains('invisible')) {
                    menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                    menu.classList.remove('opacity-100', 'translate-y-0');
                    arrow.classList.remove('rotate-180');
                }
            });
        </script>
    </section>

    <!-- Services Grid Section -->
    <section class="px-4 md:px-6">
        <div class="container mx-auto">
            <div class="flex flex-wrap -mx-2 justify-center">
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <!-- Service Card 1 -->
                    <a href="{{ route('service-detail', 'ayurveda-panchakarma') }}" class="block h-full">
                        <div
                            class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
                            <!-- Image -->
                            <div class="h-64 overflow-hidden">
                                <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma.png') }}"
                                    alt="Ayurveda & Panchakarma"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <!-- Content -->
                            <div class="p-8">
                                <h3 class="text-2xl font-serif text-[#C5896B] mb-3">Ayurveda & Panchakarma</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                    Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized
                                    detoxification
                                    and
                                    rejuvenation.
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                                    <span
                                        class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book
                                        a Session</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <!-- Service Card 2 -->
                    <a href="{{ route('service-detail', 'yoga-therapy') }}" class="block h-full">
                        <div
                            class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
                            <!-- Image -->
                            <div class="h-64 overflow-hidden">
                                <img src="{{ asset('frontend/assets/yoga-therapy.png') }}" alt="Yoga Therapy"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <!-- Content -->
                            <div class="p-8">
                                <h3 class="text-2xl font-serif text-[#C5896B] mb-3">Yoga Therapy</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                    Yoga Therapy goes beyond flexibility. It is a clinical approach to healing that combines
                                    specific asanas, breathwork...
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                                    <span
                                        class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book
                                        a Session</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <!-- Service Card 3 -->
                    <a href="{{ route('service-detail', 'spiritual-guidance') }}" class="block h-full">
                        <div
                            class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
                            <!-- Image -->
                            <div class="h-64 overflow-hidden">
                                <img src="{{ asset('frontend/assets/spiritual-guidance.png') }}" alt="Spiritual Guidance"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <!-- Content -->
                            <div class="p-8">
                                <h3 class="text-2xl font-serif text-[#C5896B] mb-3">Spiritual Guidance</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                    Explore the deeper aspects of your existence. These sessions provide a safe space
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                                    <span
                                        class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book
                                        a Session</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <!-- Service Card 4 -->
                    <a href="{{ route('service-detail', 'mindfulness-counselling') }}" class="block h-full">
                        <div
                            class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
                            <!-- Image -->
                            <div class="h-64 overflow-hidden">
                                <img src="{{ asset('frontend/assets/mindfulness-counselling.png') }}"
                                    alt="Mindfulness Counselling"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <!-- Content -->
                            <div class="p-8">
                                <h3 class="text-2xl font-serif text-[#C5896B] mb-3">Mindfulness Counselling</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                    Cultivate a non-judgmental awareness of the present moment. Our sessions bridge
                                    traditional
                                    psychology...
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                                    <span
                                        class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book
                                        a Session</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <!-- Service Card 5 -->
                    <a href="{{ route('service-detail', 'ayurveda-panchakarma') }}" class="block h-full">
                        <div
                            class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
                            <!-- Image -->
                            <div class="h-64 overflow-hidden">
                                <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma.png') }}"
                                    alt="Ayurveda & Panchakarma"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <!-- Content -->
                            <div class="p-8">
                                <h3 class="text-2xl font-serif text-[#C5896B] mb-3">Ayurveda & Panchakarma</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                    Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized
                                    detoxification
                                    and
                                    rejuvenation.
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                                    <span
                                        class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book
                                        a Session</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

@endsection
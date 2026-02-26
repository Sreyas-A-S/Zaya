<!-- Footer -->
<footer class="text-[#2E2E2E]">
    <div class="container-fluid mx-auto relative z-10">
        <!-- Newsletter Section -->
        <div class="bg-[#79584B] px-6 py-8 md:px-12 flex flex-col md:flex-row items-center justify-center gap-8">
            <h3 class="text-white text-lg md:text-xl font-regular text-center md:text-left font-sans!">
                Join our newsletter for weekly wellness tips.
            </h3>
            <div class="flex w-full md:w-auto gap-2">
                <input type="email" placeholder="Your email..."
                    class="bg-[#F2F2F2] text-[#2E4B3C] placeholder-gray-400 rounded-lg px-4 py-3 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-[#DFAF7F]">
                <button
                    class="bg-[#FFD28D] hover:bg-[#e0caaa] text-[#2E4B3C] rounded-lg px-6 py-3 transition-all flex items-center justify-center">
                    <i class="ri-send-plane-2-fill text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="bg-gradient-to-b from-[#FFE7CF] to-[#DFAF7F] ">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 px-6 py-12 sm:px-12 md:px-12 md:py-16 relative">
                <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt=""
                    class="absolute bottom-0 left-0 w-100 z-0 pointer-events-none">
                <!-- Column 1: Logo & Tagline -->
                <div class="flex flex-col items-start space-y-6 z-1">
                    <a href="{{ route('home') }}" class="block">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                            class="h-24 w-auto object-contain">
                    </a>
                    <p class="text-[#252525] text-base/8 font-light max-w-xs">
                        Empowering your wellness journey through ancient wisdom and modern science.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="z-1">
                    <h4 class="font-medium font-sans! mb-6 text-xl text-[#2E2E2E]">Quick Links</h4>
                    <ul class="space-y-6 text-base font-regular text-[#252525]">
                        <li><a href="{{ route('home') }}" class="hover:text-[#79584B] transition-colors">Home</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Who we are</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">What we do</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Our Team</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-[#79584B] transition-colors">Gallery</a>
                        </li>
                        <li><a href="{{ route('blogs') }}" class="hover:text-[#79584B] transition-colors">Blog</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-[#79584B] transition-colors">Contact
                                Us</a></li>
                    </ul>
                </div>

                <!-- Column 3: Conditions -->
                <div class="z-1">
                    <h4 class="font-medium font-sans! mb-6 text-xl text-[#2E2E2E]">Conditions We Support</h4>
                    <ul class="space-y-6 text-base font-regular text-[#252525]">
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Life Transitions</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Mental Imbalance</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Stress Reduction</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Toxin Removal</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Chronic Pain</a></li>
                        <li><a href="#" class="hover:text-[#79584B] transition-colors">Immune Support</a></li>
                    </ul>
                </div>

                <!-- Column 4: Pincode & Socials -->
                <div class="z-1">
                    <h4 class="font-medium font-sans! mb-4 text-base text-[#2E2E2E]">Save your pincode & find nearby
                        care.</h4>
                    <form class="flex gap-2 mb-10">
                        <input type="text" placeholder="Enter Pincode"
                            class="bg-[#F9F9F9] placeholder-gray-400 text-gray-800 rounded px-4 py-2 w-full text-sm focus:outline-none border border-transparent focus:border-[#79584B]">
                        <button type="button"
                            class="bg-primary text-white font-medium rounded px-6 py-2 text-sm hover:bg-primary/80 transition-colors shadow-sm cursor-pointer">
                            Save
                        </button>
                    </form>

                    <div class="flex flex-wrap gap-3 xl:gap-8">
                        <a href="#" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-facebook-fill text-lg"></i>
                        </a>
                        <a href="#" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-instagram-line text-lg"></i>
                        </a>
                        <a href="#" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-youtube-fill text-lg"></i>
                        </a>
                        <a href="#" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-linkedin-fill text-lg"></i>
                        </a>
                    </div>

                    <div class="flex flex-col justify-between text-sm text-[#252525] gap-4 mt-8 mb-20">
                        <a href="#" class="hover:text-[#79584B] transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-[#79584B] transition-colors">Cookie Policy</a>
                        <a href="#" class="hover:text-[#79584B] transition-colors">Terms & Conditions</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Links & Copy -->
            <div class="flex flex-col items-center gap-6 py-6 border-t border-[#252525]/80">
                <div class="text-center text-sm text-[#252525] cursor-default">
                    <p>All rights reserved. &copy; {{ date('Y') }} Zaya Wellness</p>
                </div>
            </div>
        </div>


    </div>
</footer>
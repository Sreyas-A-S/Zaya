    <!-- Footer -->
    <footer class="mt-10 lg:mt-20">
        <div class="relative z-1">
            <!-- Decorative Images -->
            <img src="{{ asset('frontend/assets/leaf-03.png') }}" alt="Leaf Image"
                class="absolute bottom-full translate-y-[70px] left-0 w-30 md:w-[100px] opacity-80 pointer-events-none z-10">
            <img src="{{ asset('frontend/assets/tulsi-image.png') }}" alt="Tulsi Image"
                class="absolute bottom-full translate-y-[80px] right-0 w-30 md:w-[163px] opacity-80 pointer-events-none z-10">

        </div>
        <div class="p-4 relative z-10">
            <div class="container-fluid bg-[#F2F2F2] pl-10 pr-10 pt-16 pb-8 rounded-xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <!-- Column 1 -->
                    <div class="col-span-1 md:col-span-1">
                        <!-- Logo (Centered) -->
                        <a href="#" class="text-2xl md:text-3xl font-serif font-bold tracking-widest text-secondary">
                            <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="h-24">
                        </a>
                        <p class="text-secondary text-sm leading-relaxed mt-4">Embracing the ancient wisdom of Ayurveda
                            to
                            bring harmony to your modern life.</p>
                    </div>

                    <!-- Spacer -->
                    <div class="hidden md:block"></div>

                    <!-- Links -->
                    <div>
                        <h4 class="font-bold font-sans text-primary mb-6 text-lg">Quick Links</h4>
                        <ul class="space-y-3 text-sm text-secondary">
                            <li><a href="#" class="hover:text-primary transition-colors">Home</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Practitioners</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Treatments</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">E-Consultation</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h4 class="font-bold font-sans text-primary mb-6 text-lg">Stay Connected</h4>
                        <p class="text-xs text-secondary mb-4">Join our newsletter for wellness tips.</p>
                        <div class="flex gap-2">
                            <input type="email" placeholder="Your email..."
                                class="bg-[#95B2A3] placeholder-[#2E4B3C]/70 text-[#2E4B3C] rounded-lg px-4 py-3 w-full text-base focus:outline-none">
                            <button
                                class="bg-[#F8E0BB] text-[#2E4B3C] rounded-lg px-5 hover:bg-opacity-90 transition-colors flex items-center justify-center">
                                <i class="ri-send-plane-2-fill text-xl"></i>
                            </button>
                        </div>
                        <div class="flex gap-4 mt-6">
                            <a href="#" class="w-6 h-6 hover:opacity-80 transition-opacity">
                                <img src="{{ asset('frontend/assets/web-icon.svg') }}" alt="Website" class="w-full h-full">
                            </a>
                            <a href="#" class="w-6 h-6 hover:opacity-80 transition-opacity">
                                <img src="{{ asset('frontend/assets/instagram-icon.svg') }}" alt="Instagram" class="w-full h-full">
                            </a>
                            <a href="#" class="w-6 h-6 hover:opacity-80 transition-opacity">
                                <img src="{{ asset('frontend/assets/facebook-icon.svg') }}" alt="Facebook" class="w-full h-full">
                            </a>
                            <a href="#" class="w-6 h-6 hover:opacity-80 transition-opacity">
                                <img src="{{ asset('frontend/assets/whatsapp-icon.svg') }}" alt="WhatsApp" class="w-full h-full">
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
                    <p>&copy; 2026 Zaya Wellness. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-primary">Privacy Policy</a>
                        <a href="#" class="hover:text-primary">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
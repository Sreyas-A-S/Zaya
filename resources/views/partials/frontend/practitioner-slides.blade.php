@foreach($practitioners as $practitioner)
    @php
        $details = $practitioner;
        $user = $practitioner->user;
        $name = $user ? $user->name : 'Unknown';
        $roleName = 'Practitioner';
        if (!empty($details->consultations) && is_array($details->consultations) && count($details->consultations) > 0) {
            $roleName = $details->consultations[0];
        }

        $image = asset('frontend/assets/dummy-practitioner-img.webp'); // Default image
        if ($details->profile_photo_path) {
            $image = asset('storage/' . $details->profile_photo_path);
        }
    @endphp
    <div class="swiper-slide h-auto">
        <div class="group relative">
            <!-- Image Card -->
            <div class="relative h-[280px] md:h-[360px] xl:h-[400px] overflow-hidden mb-6">
                <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">

                <!-- Rating Badge -->
                <div
                    class="absolute top-4 right-4 bg-[#FDFEF3] border-[#E8E8D8] backdrop-blur-sm rounded-full px-3 py-2 flex items-center gap-1 shadow-sm">
                    <i class="ri-star-fill text-secondary text-sm leading-none"></i>
                    <span
                        class="text-secondary text-sm leading-none font-bold">{{ number_format($details->average_rating, 1) }}</span>
                </div>

                <!-- Book Now Button -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-max z-10">
                    <a href="{{ route('book-session', ['practitioner' => $details->slug]) }}"
                       class="bg-white text-primary px-8 py-2.5 rounded-full font-medium shadow-lg hover:bg-primary hover:text-white transition-all text-sm block">
                        {{ $settings['practitioners_button_text'] ?? 'Book Now' }}
                    </a>
                </div>

                <!-- Clickable Overlay for Image -->
                <a href="{{ $details->slug ? route('practitioner-detail', ['slug' => $details->slug]) : '#' }}" class="absolute inset-0 z-0"></a>
            </div>

            <!-- Info Section -->
            <div class="text-center">
                <h3 class="text-2xl font-serif font-medium text-primary mb-3 leading-none">
                    <a href="{{ $details->slug ? route('practitioner-detail', ['slug' => $details->slug]) : '#' }}" class="hover:text-secondary transition-colors">
                        {{ $name }}
                    </a>
                </h3>
                <p class="text-secondary text-base md:text-lg font-serif italic mb-4 cursor-default">
                    {{ $roleName }}
                </p>
                <div
                    class="flex items-center justify-center gap-1 text-[#434343] text-sm md:text-base font-regular cursor-default">
                    <i class="ri-map-pin-line text-sm md:text-base"></i>
                    <span>{{ $details->city ?? 'Zaya' }}</span>
                </div>
            </div>
        </div>
    </div>
@endforeach

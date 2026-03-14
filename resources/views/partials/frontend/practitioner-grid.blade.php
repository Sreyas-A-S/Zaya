<!-- Results Heading -->
<div class="text-center mb-10 md:mb-16">
    <h2 class="text-lg md:text-3xl font-semibold text-primary font-sans! mb-2">
        @if(isset($pincode) && $pincode)
            Search Results Based on <span class="font-bold text-gray-900">'{{ $pincode }}'</span>
        @else
            All Practitioners
        @endif
    </h2>
</div>

<!-- Practitioner Grid -->
<div id="practitioner-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-12">
    @forelse($practitioners as $p)
        <a href="{{ $p->slug ? route('practitioner-detail', ['slug' => $p->slug]) : '#' }}"
            class="flex flex-col items-center text-center group cursor-pointer animate-on-scroll">
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
    @empty
        <div class="col-span-full py-20 text-center">
            <div class="mb-4">
                <i class="ri-search-line text-5xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">No practitioners found</h3>
            <p class="text-gray-400">Try adjusting your filters or searching in a different area.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($practitioners->total() > $practitioners->perPage())
    <div class="mt-16 flex justify-center custom-pagination min-h-[50px]">
        {{ $practitioners->links() }}
    </div>
@endif

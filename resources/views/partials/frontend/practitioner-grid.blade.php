<!-- Results Heading -->
<div class="text-center mb-10 md:mb-16">
    <h2 class="text-lg md:text-3xl font-semibold text-primary font-sans! mb-2">
        @if(isset($zipcode) && $zipcode)
            Search Results Based on <span class="font-bold text-gray-900">'{{ $zipcode }}'</span>
        @else
            All Practitioners
        @endif
    </h2>
</div>

<!-- Practitioner Items -->
<div class="container mx-auto">
    <div id="practitioner-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-12">
    @forelse($practitioners as $p)
        <a href="{{ $p->slug ? route('practitioner-detail', request('service') ? ['slug' => $p->slug, 'service' => request('service')] : ['slug' => $p->slug]) : '#' }}"
            class="flex flex-col items-center text-center group cursor-pointer animate-on-scroll">
            <!-- Avatar -->
            <div
                class="w-32 h-32 md:w-[150px] md:h-[150px] mb-4 overflow-hidden rounded-full border border-gray-100">
                <img src="{{ optional($p->user)->profile_pic_url ?? asset('frontend/assets/lilly-profile-pic.png') }}"
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
                {{ optional($selectedService)->title ?: $p->subtitle_display }}
            </p>

            <!-- Location -->
            <div class="mt-2 text-xs lg:text-sm text-gray-500">
                <i class="ri-map-pin-line text-gray-800"></i>
                <span>{{ $p->city_state }}</span>
            </div>

            <!-- Bio -->
            @if($p->profile_bio)
                <p class="mt-2 text-xs text-gray-400 line-clamp-2 px-4 italic">
                    {{ Str::limit(strip_tags($p->profile_bio), 100) }}
                </p>
            @endif

            @php
                $conditions = array_slice($p->expertises_list, 0, 3);
            @endphp

            @if(!empty($conditions))
                <div class="mt-3 flex flex-wrap justify-center gap-1.5 max-w-[220px]">
                    @foreach($conditions as $cond)
                        <span class="px-2.5 py-1 rounded-full text-[10px] md:text-[11px] font-semibold bg-[#F3F6F4] text-primary border border-[#2E4B3D]/10">
                            {{ $cond }}
                        </span>
                    @endforeach
                </div>
            @endif
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
</div>

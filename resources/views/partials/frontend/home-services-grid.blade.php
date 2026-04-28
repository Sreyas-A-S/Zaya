<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 lg:gap-14 px-4">
    @foreach($services as $index => $service)
        <a href="{{ $service->slug ? route('service-detail', $service->slug) : '#' }}"
            class="group cursor-pointer hover:-translate-y-2 transition-transform duration-500"
            style="transition-delay: {{ $index * 100 }}ms;">
            <div class="h-64 overflow-hidden mb-4 relative">
                @php
                    $imagePath = $service->image ? (str_starts_with($service->image, 'frontend/') ? asset($service->image) : asset('storage/' . $service->image)) : asset('frontend/assets/service-placeholder.png');
                @endphp
                <img src="{{ $imagePath }}" alt="{{ $service->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            </div>
            <h3 class="text-xl font-serif text-secondary mb-1">{{ $service->title }}</h3>
        </a>
    @endforeach
</div>

<div class="mt-12 flex flex-col items-center gap-6 px-4">
    @if(method_exists($services, 'links'))
        <div class="w-full flex justify-center items-center min-h-[50px] home-services-pagination">
            {{ $services->fragment('services')->links('vendor.pagination.zaya') }}
        </div>
    @endif
</div>

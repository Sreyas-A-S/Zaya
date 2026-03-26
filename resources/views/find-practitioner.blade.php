@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto text-center pt-0 pb-8 md:py-10">
            <h1 class="text-2xl  sm:text-3xl md:text-5xl font-serif font-bold text-primary mb-5">
                {{ $settings['find_practitioner_title'] ?? 'Experts in Your Neighborhood' }}</h1>
            <p class="text-xl md:text-2xl text-secondary font-normal font-serif mb-8">
                {{ $settings['find_practitioner_subtitle'] ?? 'Verified practitioners ready to support your journey' }}</p>
            <p class="text-sm md:text-base text-gray-500 max-w-2xl mx-auto">
                {{ $settings['find_practitioner_description'] ?? "Find the support you need, right in your community. Every practitioner listed here is part of ZAYA's practitioner-led network, committed to ethical care and holistic healing." }}
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="px-4 md:px-6 mb-10 md:mb-20 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row gap-4 lg:gap-6 items-center">
                <!-- Search Input -->
                <div class="relative w-full md:w-1/4 search-container">
                    <input id="find_practitioner_search_input" type="text" 
                        placeholder="{{ $settings['find_practitioner_search_placeholder'] ?? 'Practitioners, Treatments...' }}"
                        autocomplete="off"
                        class="w-full border border-[#db8871] rounded-full px-6 py-3.5 pr-12 text-base md:text-lg text-[#db8871] placeholder-[#db8871] focus:outline-none bg-white transition-colors">
                    <button class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#db8871] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                    <!-- Search Results Dropdown -->
                    <div id="find-practitioner-search-results" class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px] overflow-hidden text-left">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <!-- Results will be injected here -->
                        </div>
                    </div>
                </div>

                <!-- Pincode Input -->
                <div class="relative w-full md:w-1/4">
                    <input id="find-practitioner-pincode-input" type="text" 
                        maxlength="6"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                        placeholder="{{ $settings['find_practitioner_pincode_placeholder'] ?? 'Enter Pincode' }}"
                        value="{{ session('global_pincode') }}"
                        {{ session('global_pincode') ? 'readonly' : '' }}
                        class="w-full border border-[#db8871] rounded-full px-6 py-3.5 pr-12 text-base md:text-lg text-[#db8871] placeholder-[#db8871] focus:outline-none bg-white transition-colors">
                    <button id="find-practitioner-pincode-btn" style="{{ session('global_pincode') ? 'display:none;' : '' }}"
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#F39551] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                    <button id="find-practitioner-pincode-delete" style="{{ session('global_pincode') ? '' : 'display:none;' }}"
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#F39551] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-delete-bin-line text-white text-lg"></i>
                    </button>
                </div>

                <!-- Select Service Custom Dropdown -->
                <div class="relative w-full md:w-1/4 custom-dropdown">
                    <input type="hidden" name="service" value="{{ $selectedService->id ?? request('service') }}">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-base md:text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">{{ $selectedService->title ?? ($settings['find_practitioner_service_placeholder'] ?? 'Select Service') }}</span>
                        <i
                            class="ri-arrow-down-s-line text-[#db8871] text-xl transition-transform duration-300 pointer-events-none dropdown-icon"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="dropdown-menu absolute z-50 left-0 right-0 top-[calc(100%+16px)] bg-white border border-gray-100 rounded-2xl shadow-[0_5px_30px_rgba(0,0,0,0.1)] py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                        <div class="max-h-[360px] overflow-y-auto px-1 custom-scrollbar flex flex-col gap-0.5">
                            <button type="button"
                                class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer"
                                data-value="">All Services</button>
                            @foreach($services as $service)
                                <button type="button"
                                    class="dropdown-item w-full text-left px-5 py-3.5 text-base md:text-lg text-gray-800 hover:text-[#db8871] bg-transparent rounded-lg transition-colors font-medium border-none outline-none cursor-pointer {{ isset($selectedService) && $selectedService->id === $service->id ? 'font-medium text-[#db8871]' : '' }}"
                                    data-value="{{ $service->id }}">{{ $service->title }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Select Mode Custom Dropdown -->
                <div class="relative w-full md:w-1/4 custom-dropdown">
                    <input type="hidden" name="mode" value="">
                    <button type="button"
                        class="dropdown-button w-full border border-[#db8871] rounded-full px-6 py-3.5 text-base md:text-lg text-[#db8871] bg-white flex justify-between items-center transition-colors focus:outline-none shadow-sm cursor-pointer">
                        <span class="dropdown-selected truncate">{{ $settings['find_practitioner_mode_placeholder'] ?? 'Select Mode' }}</span>
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
                                data-value="offline">In-person</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="px-4 md:px-6 pb-16 md:pb-24 bg-white">
        <div class="container mx-auto max-w-6xl">
            <!-- Practitioner Results Wrapper -->
            <div id="practitioner-results-container">
                @include('partials.frontend.practitioner-grid', ['practitioners' => $practitioners, 'pincode' => $pincode])
            </div>

            <style>
                /* Main Container Fix */
                .custom-pagination nav {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 1rem;
                    width: 100%;
                }
                
                /* Hide only the 'Showing X to Y' summary text, but keep the pagination links container visible */
                .custom-pagination nav p,
                .custom-pagination nav > div:first-child:not(:last-child) { 
                    display: none !important; 
                }

                /* Ensure the main pagination container (which is either the first or last div in nav) is visible and centered */
                .custom-pagination nav > div {
                    display: flex !important;
                    justify-content: center !important;
                    width: 100% !important;
                }

                /* Clean up internal link containers */
                .custom-pagination nav > div > div {
                   display: flex !important;
                   justify-content: center !important;
                   width: auto !important;
                }

                /* Individual Pill Styles - Clean, no border, no shadow */
                .custom-pagination nav a,
                .custom-pagination nav span[aria-current="page"] > span,
                .custom-pagination nav span[aria-disabled="true"] > span {
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    width: 44px !important;
                    height: 44px !important;
                    border-radius: 9999px !important;
                    margin: 0 4px !important;
                    padding: 0 !important;
                    border: none !important; /* Remove stroke */
                    color: #79584B !important;
                    font-weight: 600;
                    text-decoration: none !important;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    background-color: transparent !important; /* Remove white background */
                    box-shadow: none !important; /* Remove shadow/rectangle feel */
                }

                /* Active Page Pill */
                .custom-pagination nav span[aria-current="page"] > span {
                    background-color: #db8871 !important;
                    border: none !important;
                    box-shadow: none !important;
                    color: white !important;
                }

                /* Reset container layout - Force transparent background and no shadow on ALL nested elements */
                .custom-pagination nav,
                .custom-pagination nav div,
                .custom-pagination nav span,
                .custom-pagination nav a {
                    background: transparent !important;
                    background-color: transparent !important;
                    box-shadow: none !important;
                    border: none !important;
                    outline: none !important;
                }
                
                /* Hide the specific Tailwind shadow/border wrapper usually generated by Laravel */
                .custom-pagination nav [class*="shadow"],
                .custom-pagination nav [class*="border"],
                .custom-pagination nav [class*="rounded"] {
                    box-shadow: none !important;
                    border: none !important;
                }

                .custom-pagination nav > div,
                .custom-pagination nav > div > div {
                    display: flex !important;
                    justify-content: center !important;
                    padding: 10px 0 !important; /* Added vertical padding for breathing room */
                    margin: 0 !important;
                }

                /* Individual Pill Styles - Clean, no border, no shadow */
                .custom-pagination nav a,
                .custom-pagination nav span[aria-current="page"] > span,
                .custom-pagination nav span[aria-disabled="true"] > span,
                .custom-pagination nav span[aria-disabled="true"] {
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    width: 44px !important;
                    height: 44px !important;
                    border-radius: 9999px !important;
                    margin: 0 8px !important; /* Increased spacing between items */
                    padding: 0 !important;
                    color: #79584B !important;
                    font-weight: 600;
                    text-decoration: none !important;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    background-color: transparent !important;
                    border: none !important;
                    box-shadow: none !important;
                }

                /* Fix for 'Blurry' (Disabled) Arrow - Make it clean but faded */
                .custom-pagination nav span[aria-disabled="true"],
                .custom-pagination nav span[aria-disabled="true"] > span {
                    opacity: 0.3 !important;
                    cursor: not-allowed !important;
                    filter: none !important; /* Ensure no blur filters are applied */
                }

                /* Active Page Pill */
                .custom-pagination nav span[aria-current="page"] > span {
                    background-color: #db8871 !important;
                    color: white !important;
                }

                /* Hover & Interaction */
                .custom-pagination nav a:hover {
                    background-color: #FDF2F0 !important;
                    color: #db8871 !important;
                    transform: translateY(-1px);
                }

                /* SVG (Arrow) Scaling */
                .custom-pagination nav svg {
                    width: 20px !important;
                    height: 20px !important;
                }
            </style>
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

                        updateSearchResults();
                    });
                });
            });

            function updateSearchResults() {
                const service = $('input[name="service"]').val();
                const mode = $('input[name="mode"]').val();
                let url = new URL(window.location.href);
                
                if (service) url.searchParams.set('service', service);
                else url.searchParams.delete('service');
                
                if (mode) url.searchParams.set('mode', mode);
                else url.searchParams.delete('mode');
                
                url.searchParams.delete('page'); // Reset to page 1 on filter change
                
                fetchResults(url.toString());
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.custom-dropdown')) {
                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('dropdown-open');
                    });
                }
                if (!e.target.closest('.search-container')) {
                    document.querySelectorAll('.search-container').forEach(container => {
                        container.classList.remove('dropdown-open');
                    });
                }
            });

            // Name/Treatment Search Logic
            function setupSearch(inputId, resultsId) {
                const searchInput = document.getElementById(inputId);
                const resultsDropdown = document.getElementById(resultsId);
                if (!searchInput || !resultsDropdown) return;

                const resultsContainer = resultsDropdown.querySelector('.custom-scrollbar');
                const container = searchInput.closest('.search-container');

                searchInput.addEventListener('input', function() {
                    const query = this.value;

                    if (query.length < 1) {
                        container.classList.remove('dropdown-open');
                        setTimeout(() => { 
                            if(!container.classList.contains('dropdown-open')) resultsContainer.innerHTML = ''; 
                        }, 300);
                        return;
                    }

                    $.ajax({
                        url: "{{ route('search') }}",
                        type: "GET",
                        data: { query: query },
                        success: function(data) {
                            resultsContainer.innerHTML = '';

                            const hasPractitioners = data.practitioners && data.practitioners.length > 0;
                            const hasTreatments = data.treatments && data.treatments.length > 0;

                            if (hasPractitioners || hasTreatments) {
                                if (hasPractitioners) {
                                    resultsContainer.insertAdjacentHTML('beforeend', '<div class="px-5 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50/50">Practitioners</div>');
                                    data.practitioners.forEach(function(item) {
                                        const resultItem = `
                                            <a href="/practitioner/${item.slug}" class="dropdown-item w-full flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 text-gray-800 hover:text-[#db8871] rounded-lg transition-colors group">
                                                <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                </div>
                                                <div class="flex flex-col text-left">
                                                    <span class="font-sans! text-base md:text-lg font-medium leading-tight">${item.name}</span>
                                                    <span class="text-xs text-gray-400 mt-0.5 font-normal">${item.subtitle}</span>
                                                </div>
                                            </a>
                                        `;
                                        resultsContainer.insertAdjacentHTML('beforeend', resultItem);
                                    });
                                }

                                if (hasTreatments) {
                                    resultsContainer.insertAdjacentHTML('beforeend', '<div class="px-5 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50/50">Treatments</div>');
                                    data.treatments.forEach(function(item) {
                                        const resultItem = `
                                            <a href="/service/${item.slug}" class="dropdown-item w-full flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 text-gray-800 hover:text-[#db8871] rounded-lg transition-colors group">
                                                <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                </div>
                                                <div class="flex flex-col text-left">
                                                    <span class="font-sans! text-base md:text-lg font-medium leading-tight">${item.name}</span>
                                                    <span class="text-xs text-gray-400 mt-0.5 font-normal">${item.subtitle}</span>
                                                </div>
                                            </a>
                                        `;
                                        resultsContainer.insertAdjacentHTML('beforeend', resultItem);
                                    });
                                }
                                container.classList.add('dropdown-open');
                            } else {
                                resultsContainer.innerHTML = '<div class="px-5 py-4 text-gray-500 italic text-center">No results found</div>';
                                container.classList.add('dropdown-open');
                            }
                        }
                    });
                });
            }

            setupSearch('find_practitioner_search_input', 'find-practitioner-search-results');

            // Pincode Search Logic
            const pincodeInput = document.getElementById('find-practitioner-pincode-input');
            const pincodeBtn = document.getElementById('find-practitioner-pincode-btn');
            const pincodeDelete = document.getElementById('find-practitioner-pincode-delete');

            function savePincode(pincode) {
                if (pincode.length === 6) {
                    $.ajax({
                        url: "{{ route('admin.pincode.store') }}",
                        type: "POST",
                        data: {
                            pincode: pincode,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                // Sync search input itself
                                $(pincodeInput).prop('readonly', true);
                                $(pincodeBtn).hide();
                                $(pincodeDelete).show();

                                // Sync with footer
                                const footerInput = $('#footer-pincode-input');
                                footerInput.val("{{ __('Your Pincode') }}: " + pincode)
                                    .prop('readonly', true)
                                    .attr('maxlength', '')
                                    .removeClass('bg-[#F9F9F9] border-gray-200')
                                    .addClass('bg-green-50 border-green-200');
                                
                                $('#footer-pincode-save').hide();
                                $('#footer-pincode-delete').show();

                                // Reload search results to apply pincode filter
                                updateSearchResults(); 
                            }
                        }
                    });
                }
            }

            function deletePincode() {
                $.ajax({
                    url: "{{ route('admin.pincode.delete') }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            // Sync search input itself
                            $(pincodeInput).val('').prop('readonly', false);
                            $(pincodeBtn).show();
                            $(pincodeDelete).hide();

                            // Sync with footer
                            const footerInput = $('#footer-pincode-input');
                            if (footerInput.length) {
                                footerInput.val('')
                                    .prop('readonly', false)
                                    .attr('maxlength', '6')
                                    .removeClass('bg-green-50 border-green-200')
                                    .addClass('bg-[#F9F9F9] border-gray-200');
                                
                                $('#footer-pincode-delete').hide();
                                $('#footer-pincode-save').show();
                            }

                            // Reload to show all practitioners again
                            updateSearchResults();
                        }
                    }
                });
            }

            if (pincodeBtn) {
                pincodeBtn.addEventListener('click', () => {
                    savePincode(pincodeInput.value);
                });
            }

            if (pincodeDelete) {
                pincodeDelete.addEventListener('click', () => {
                    deletePincode();
                });
            }

            if (pincodeInput) {
                pincodeInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        savePincode(pincodeInput.value);
                    }
                });
            }

            // AJAX Pagination Handling
            $(document).on('click', '.custom-pagination a', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent app.blade.php global listener from interfering
                const url = $(this).attr('href');
                fetchResults(url);
            });

            function fetchResults(url) {
                if (window.showPreloader) window.showPreloader();
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (data) {
                        // Check if we accidentally got the full page
                        if (data.indexOf('<!DOCTYPE html>') !== -1) {
                            console.warn('Received full page instead of partial. Reloading as fallback.');
                            window.location.href = url;
                            return;
                        }

                        $('#practitioner-results-container').html(data);
                        
                        // Push to history (original URL without ajax param)
                        window.history.pushState({path: url}, '', url);
                        
                        // Re-initialize scroll animations
                        setupScrollAnimations();
                        
                        // Scroll to results top smoothly
                        const container = document.getElementById('practitioner-results-container');
                        if (container) {
                            window.scrollTo({
                                top: container.offsetTop - 150,
                                behavior: 'smooth'
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error fetching search results:', xhr.status);
                        // Fallback to traditional load on error
                        window.location.href = url;
                    },
                    complete: function() {
                        if (window.hidePreloader) window.hidePreloader();
                    }
                });
            }

            function setupScrollAnimations() {
                const observerOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.1
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.animate-on-scroll').forEach(el => {
                    // Start hidden
                    el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
                    el.classList.remove('opacity-100', 'translate-y-0');
                    
                    // Observe
                    observer.observe(el);
                    
                    // Safety: Force show after a short delay if it's in viewport but didn't trigger
                    setTimeout(() => {
                        const rect = el.getBoundingClientRect();
                        if (rect.top < window.innerHeight && rect.bottom >= 0) {
                            el.classList.add('opacity-100', 'translate-y-0');
                            el.classList.remove('opacity-0', 'translate-y-10');
                        }
                    }, 500);
                });
            }

            // Initial setup
            setupScrollAnimations();
        });
    </script>
@endpush

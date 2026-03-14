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
                    <input id="find-practitioner-pincode-input" type="text" placeholder="Enter Pincode"
                        value="{{ session('global_pincode') }}"
                        {{ session('global_pincode') ? 'readonly' : '' }}
                        class="w-full border border-[#db8871] rounded-full px-6 py-3.5 pr-12 text-base md:text-lg text-[#db8871] placeholder-[#db8871] focus:outline-none bg-white transition-colors">
                    <button id="find-practitioner-pincode-btn" style="{{ session('global_pincode') ? 'display:none;' : '' }}"
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#F39551] rounded-full flex items-center justify-center hover:opacity-90 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                    <button id="find-practitioner-pincode-delete" style="{{ session('global_pincode') ? '' : 'display:none;' }}"
                        class="absolute right-[10px] top-1/2 -translate-y-1/2 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center hover:bg-red-600 transition-all cursor-pointer border-none outline-none">
                        <i class="ri-delete-bin-line text-white text-lg"></i>
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
                
                /* Hide the 'Showing X to Y of Z results' text and its container */
                .custom-pagination nav > div:first-child,
                .custom-pagination nav p { 
                    display: none !important; 
                }

                /* Ensure absolute transparency for all containers to remove the "rectangle shape" */
                .custom-pagination nav,
                .custom-pagination nav > div,
                .custom-pagination nav > div > div {
                    background-color: transparent !important;
                    background: none !important;
                    border: none !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                }

                /* Container for pagination links */
                .custom-pagination nav > div:last-child {
                    display: flex !important;
                    justify-content: center !important;
                    width: 100% !important;
                }

                /* Mobile-specific: Hide the 'Previous' and 'Next' text buttons from standard desktop view if they appear twice */
                .custom-pagination nav > div:last-child > div:first-child {
                    display: none !important;
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

                /* Reset container layout */
                .custom-pagination nav > div:last-child {
                    background: transparent !important;
                    box-shadow: none !important;
                    border: none !important;
                    padding: 0 !important;
                }
                
                .custom-pagination nav > div:last-child > div {
                    display: flex !important;
                    gap: 4px;
                }

                /* Disable circular styles for the 'Showing' summary text */
                .custom-pagination nav p span {
                    display: inline !important;
                    min-width: 0 !important;
                    height: auto !important;
                    border-radius: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: none !important;
                    background-color: transparent !important;
                    box-shadow: none !important;
                    color: inherit !important;
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
            });

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
                            footerInput.val('')
                                .prop('readonly', false)
                                .attr('maxlength', '6')
                                .removeClass('bg-green-50 border-green-200')
                                .addClass('bg-[#F9F9F9] border-gray-200');
                            
                            $('#footer-pincode-delete').hide();
                            $('#footer-pincode-save').show();

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
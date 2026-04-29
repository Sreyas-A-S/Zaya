<!-- Footer -->
<footer class="text-[#2E2E2E]" style="{{ request()->routeIs('client-register') ? 'background: #FFEAC6;' : '' }}">
    <div class="container-fluid mx-auto relative z-10">
        <!-- Newsletter Section -->
        <div
            class="px-6 py-8 md:px-12 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8"
            style="{{ request()->routeIs('client-register') ? 'background: #FFEAC6;' : 'background: #79584B;' }}">
            <h3 id="footer-newsletter-title"
                class="{{ request()->routeIs('client-register') ? 'text-secondary' : 'text-white' }} text-sm sm:text-base md:text-xl font-normal text-center md:text-left font-sans!"
                data-i18n="{{ $site_settings['newsletter_title'] ?? 'Join our newsletter for weekly wellness tips.' }}">
                {{ __($site_settings['newsletter_title'] ?? 'Join our newsletter for weekly wellness tips.') }}
            </h3>
            <div class="flex w-full md:w-auto gap-2">
                <input id="footer-newsletter-input" type="email" placeholder="{{ __('Your email...') }}"
                    data-i18n="Your email..."
                    class="bg-[#F2F2F2] text-secondary placeholder-gray-400 rounded-lg px-4 py-3 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-[#DFAF7F]">
                <button id="footer-newsletter-btn"
                    class="bg-[#FFD28D] hover:bg-[#e0caaa] text-secondary rounded-lg px-6 py-3 transition-all flex items-center justify-center">
                    <i class="ri-send-plane-2-fill text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div
            class="relative"
            style="{{ request()->routeIs('client-register') ? 'background: #FFEAC6;' : 'background: linear-gradient(120deg,#FFE7CF,#DFAF7F);' }}">
            <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt="Mobile Footer Leaves Image"
                class="block lg:hidden absolute bottom-0 left-0 h-8 md:h-18 z-0 pointer-events-none">
            <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt="Mobile Footer Leaves Image"
                class="block lg:hidden scale-x-[-1] absolute bottom-0 right-0 h-8 md:h-18 z-0 pointer-events-none">

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-10 px-8 pt-6 xl:pt-15 pb-4 lg:pb-40 xl:pb-18 sm:px-12 md:px-12 relative">
                <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt="Desktop Footer Leaves Image"
                    class="hidden lg:block absolute bottom-0 left-0 w-100 z-0 pointer-events-none">
                <!-- Column 1: Logo & Tagline -->
                <div
                    class="order-last md:order-first md:col-span-2 lg:col-span-3 xl:col-span-1 flex flex-col items-center justify-center xl:items-start lg:justify-start space-y-3 md:space-y-6 z-1">
                    <a href="{{ route('home') }}" class="block">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                            class="h-16 md:h-24 w-auto object-contain">
                    </a>
                    <p id="footer-tagline"
                        class="text-[#2E2E2E] text-center xl:text-start opacity-80 text-xs md:text-base leading-relaxed max-w-xs"
                        data-i18n="{{ $site_settings['footer_description'] ?? 'Empowering your wellness journey through ancient wisdom and modern science.' }}">
                        {{ __($site_settings['footer_description'] ?? 'Empowering your wellness journey through ancient wisdom and modern science.') }}
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="order-first border-b border-[#DABEA2] md:border-none pb-4 md:pb-0">
                    <div class="flex justify-between items-center cursor-pointer md:cursor-auto"
                        onclick="toggleFooterMenu('quick-links')">
                        <h4 id="footer-quick-links-title"
                            class="font-medium font-sans! mb-0 md:mb-6 text-xl text-[#2E2E2E]"
                            data-i18n="{{ $site_settings['quick_links_heading'] ?? 'Quick Links' }}">
                            {{ __($site_settings['quick_links_heading'] ?? 'Quick Links') }}
                        </h4>
                        <i class="ri-add-line text-2xl md:hidden text-[#2E2E2E]" id="quick-links-icon"></i>
                    </div>
                    <ul id="quick-links-menu"
                        class="hidden md:block! space-y-4 text-base font-regular text-[#2E2E2E]/80 pt-6 md:pt-0">
                        <li><a id="footer-home" href="{{ route('home') }}"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Home">{{ __('Home') }}</a>
                        </li>
                        <li><a id="footer-who-we-are" href="{{ route('about-us') }}#who-we-are"
                                class="hover:text-[#79584B] transition-colors"
                                data-i18n="Who we are">{{ __('Who we are') }}</a></li>
                        <li><a id="footer-what-we-do" href="{{ route('about-us') }}#what-we-do"
                                class="hover:text-[#79584B] transition-colors"
                                data-i18n="What we do">{{ __('What we do') }}</a></li>
                        <li><a id="footer-our-team" href="{{ route('about-us') }}#our-team"
                                class="hover:text-[#79584B] transition-colors"
                                data-i18n="Our Team">{{ __('Our Team') }}</a></li>
                        <li><a id="footer-gallery" href="{{ route('gallery') }}"
                                class="hover:text-[#79584B] transition-colors"
                                data-i18n="Gallery">{{ $site_settings['footer_link_gallery'] ?? __('Gallery') }}</a>
                        </li>
                        <li><a id="footer-blog" href="{{ route('blogs') }}"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Blog">{{ __('Blog') }}</a>
                        </li>
                        <li><a id="footer-contact-us" href="{{ route('contact-us') }}"
                                class="hover:text-[#79584B] transition-colors"
                                data-i18n="Contact Us">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>

                <!-- Column 3: Conditions -->
                <div class="order-2 z-1 pb-4 md:pb-0">
                    <div class="flex justify-between items-center cursor-pointer md:cursor-auto"
                        onclick="toggleFooterMenu('conditions')">
                        <h4 id="footer-conditions-title"
                            class="font-medium font-sans! mb-0 md:mb-6 text-xl text-[#2E2E2E]"
                            data-i18n="{{ $site_settings['conditions_heading'] ?? 'Conditions We Support' }}">
                            {{ __($site_settings['conditions_heading'] ?? 'Conditions We Support') }}
                        </h4>
                        <i class="ri-add-line text-2xl md:hidden text-[#2E2E2E]" id="conditions-icon"></i>
                    </div>
                    <ul id="conditions-menu"
                        class="hidden md:block! space-y-4 text-base font-regular text-[#2E2E2E]/80 pt-6 md:pt-0">
                        @foreach($global_health_conditions ?? [] as $condition)
                            <li><a href="{{ route('find-practitioner', ['query' => $condition]) }}"
                                    class="hover:text-[#79584B] transition-colors">{{ $condition }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 4: Zipcode & Socials -->
                <div class="order-3 z-1 md:col-span-2 lg:col-span-1">
                    <h4 id="footer-zipcode-title"
                        class="font-sans! text-base font-medium text-[#252525] mb-4 text-center lg:text-start"
                        data-i18n="{{ $site_settings['pincode_heading'] ?? 'Save your zipcode & find nearby care.' }}">
                        {{ __($site_settings['pincode_heading'] ?? 'Save your zipcode & find nearby care.') }}
                    </h4>

                    <form class="flex gap-2 mb-2 items-center">
                        <div class="relative flex-1">
                            @php
                                $savedZipcode = session('global_zipcode');
                                $displayValue = $savedZipcode ? __('Your Zipcode') . ': ' . $savedZipcode : '';
                            @endphp
                            <input id="footer-zipcode-input" type="text" placeholder="{{ __('Enter Zipcode') }}"
                                data-i18n="Enter Zipcode" data-zipcode="{{ $savedZipcode ?? '' }}"
                                maxlength="{{ $savedZipcode ? '20' : '6' }}" {{ $savedZipcode ? 'readonly' : '' }}
                                oninput="if(!this.readOnly) this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');"
                                value="{{ $displayValue }}"
                                class="{{ $savedZipcode ? 'bg-[#4DB286] border-green-200' : 'bg-[#F9F9F9] border-gray-200' }} placeholder-gray-400 text-gray-800 rounded px-4 h-11 w-full text-sm focus:outline-none focus:border-[#79584B] transition-all border">
                        </div>
                        <button id="footer-zipcode-save" type="button"
                            style="{{ session('global_zipcode') ? 'display:none;' : '' }}"
                            class="bg-primary h-11 text-white font-medium rounded px-6 text-sm hover:bg-[#5e4339] transition-all shadow-sm flex items-center justify-center gap-2 whitespace-nowrap min-w-[90px]"
                            data-i18n="Save">
                            <span>{{ __('Save') }}</span>
                        </button>
                        <button id="footer-zipcode-delete" type="button"
                            style="{{ session('global_zipcode') ? '' : 'display:none;' }}"
                            class="bg-primary h-11 text-white font-medium rounded px-4 text-lg hover:bg-[#5e4339] transition-all shadow-sm flex items-center justify-center whitespace-nowrap"
                            title="{{ __('Delete') }}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>

                    <div id="zipcode-message" class="min-h-[24px] mb-2 text-xs font-bold"></div>

                    <div class="flex flex-wrap gap-3 xl:gap-8 justify-center lg:justify-start">
                        <a href="{{ $site_settings['social_facebook'] ?? '#' }}" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-facebook-fill text-lg"></i>
                        </a>
                        <a href="{{ $site_settings['social_instagram'] ?? '#' }}" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-instagram-line text-lg"></i>
                        </a>
                        <a href="{{ $site_settings['social_youtube'] ?? '#' }}" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-youtube-fill text-lg"></i>
                        </a>
                        <a href="{{ $site_settings['social_linkedin'] ?? '#' }}" target="_blank"
                            class="w-10 h-10 border border-primary rounded-full flex items-center justify-center text-primary hover:bg-primary hover:border-primary hover:text-white transition-all duration-300">
                            <i class="ri-linkedin-fill text-lg"></i>
                        </a>
                    </div>

                    <div
                        class="flex lg:flex-col justify-center lg:justify-start text-xs md:text-sm text-[#252525] gap-6 md:gap-8 lg:gap-6 mt-6">
                        <a id="footer-privacy" href="{{ route('privacy-policy') }}"
                            class="hover:text-[#79584B] transition-colors text-center lg:text-start"
                            data-i18n="Privacy Policy">{{ __('Privacy Policy') }}</a>
                        <a id="footer-cookie" href="{{ route('cookie-policy') }}"
                            class="hover:text-[#79584B] transition-colors text-center lg:text-start"
                            data-i18n="Cookie Policy">{{ __('Cookie Policy') }}</a>
                        <a id="footer-terms" href="{{ route('terms-and-conditions') }}"
                            class="hover:text-[#79584B] transition-colors text-center lg:text-start"
                            data-i18n="Terms & Conditions">{{ __('Terms & Conditions') }}</a>
                    </div>
                </div>
            </div>

            <div
                class="text-center text-[10px] md:text-sm text-[#252525] cursor-default lg:border-t border-[#252525]/80 py-4">
                <p>
                    <span
                        id="copyright_text">{{ __($site_settings['copyright_text'] ?? 'All rights reserved.') }}</span>
                </p>
            </div>
        </div>


    </div>

    <style>
        /* Hide ALL native Tawk.to UI elements across all states */
        .tawk-min-container,
        .tawk-bubble-container,
        .tawk-tooltip,
        .tawk-custom-color,
        #tawkchat-container > iframe:not([title="chat window"]),
        iframe[title="chat widget"],
        iframe[title="1jnb8ik6a"], /* Specific ID fallback */
        [id^="tawk-"] { 
            display: none !important; 
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
        
        /* Ensure ONLY the main chat window iframe is allowed to show */
        iframe[title="chat window"] {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        #zaya-chat-widget {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 100000 !important;
            /* Default bottom position */
            bottom: 24px;
            transition: bottom 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Position when announcement is present */
        #zaya-chat-widget.has-announcement {
            bottom: 112px; /* Standard height above the bar */
        }

        @media (min-width: 768px) {
            #zaya-chat-widget.has-announcement {
                bottom: 128px;
            }
        }

        .zaya-chat-hidden {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
    </style>

    <!-- Custom Chat Widget -->
    <div id="zaya-chat-widget" class="fixed right-6">
        <button onclick="openZayaChat()" class="relative group">
            <div class="absolute inset-0 bg-[#183126] rounded-full animate-ping opacity-20 group-hover:opacity-30"></div>
            <div class="relative w-14 h-14 md:w-16 md:h-16 bg-[#183126] rounded-full shadow-2xl flex items-center justify-center border-2 border-white/20 hover:scale-110 transition-transform duration-300">
                <i class="ri-chat-smile-3-line text-white text-2xl md:text-3xl"></i>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white animate-pulse"></div>
            </div>
            <div class="absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-white px-4 py-2 rounded-xl shadow-xl border border-gray-100 whitespace-nowrap opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-300 pointer-events-none hidden md:block">
                <p class="text-[11px] font-black text-secondary uppercase tracking-widest">Chat with us</p>
                <div class="absolute top-1/2 -right-1.5 -translate-y-1/2 w-3 h-3 bg-white border-r border-t border-gray-100 rotate-45"></div>
            </div>
        </button>
    </div>

    <!-- Tawk.to Logic -->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        
        Tawk_API.onLoad = function() {
            Tawk_API.hideWidget();
        };

        Tawk_API.onChatMaximized = function() {
            document.getElementById('zaya-chat-widget').classList.add('zaya-chat-hidden');
        };

        Tawk_API.onChatMinimized = function() {
            document.getElementById('zaya-chat-widget').classList.remove('zaya-chat-hidden');
        };

        function openZayaChat() {
            if (typeof Tawk_API !== 'undefined' && Tawk_API.maximize) {
                Tawk_API.maximize();
            }
        }

        // Dynamic positioning & Visibility Guard
        function updateWidgetPosition() {
            const widget = document.getElementById('zaya-chat-widget');
            const announcement = document.getElementById('announcement-card');
            
            if (widget) {
                // If announcement exists and is visible (not hidden by opacity-0)
                if (announcement && announcement.classList.contains('opacity-100')) {
                    widget.classList.add('has-announcement');
                } else {
                    widget.classList.remove('has-announcement');
                }

                // Force visibility
                if (!widget.classList.contains('zaya-chat-hidden')) {
                    if (window.getComputedStyle(widget).display === 'none' || window.getComputedStyle(widget).opacity === '0') {
                        widget.style.setProperty('display', 'flex', 'important');
                        widget.style.setProperty('opacity', '1', 'important');
                    }
                }
            }
        }

        setInterval(updateWidgetPosition, 500);

        (function() {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ config('services.tawk_to.src') }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <script>
        const conditionsMenu = document.getElementById('conditions-menu');
        const defaultConditionsHtml = `
        @foreach($global_health_conditions ?? [] as $condition)
            <li><a href="{{ route('find-practitioner', ['query' => $condition]) }}" class="hover:text-[#79584B] transition-colors">{{ $condition }}</a></li>
        @endforeach
    `;

        function renderSupportedConditions(conditions, isLocal = false) {
            if (!conditionsMenu) return;

            if (!conditions || !conditions.length) {
                conditionsMenu.innerHTML = defaultConditionsHtml;
                return;
            }

            const baseUrl = @json(route('find-practitioner'));
            const rawZip = document.getElementById('footer-zipcode-input') ? (document.getElementById('footer-zipcode-input').getAttribute('data-zipcode') || '') : '';
            const zipParam = (rawZip && rawZip.length === 6) ? ('&zipcode=' + encodeURIComponent(rawZip)) : '';

            conditionsMenu.innerHTML = conditions.slice(0, 6).map(function (label) {
                const safe = String(label).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                const href = baseUrl + '?query=' + encodeURIComponent(label) + zipParam;
                const icon = isLocal ? '<i class="ri-map-pin-line text-[10px] ml-1 opacity-50"></i>' : '';
                return '<li><a href="' + href + '" class="hover:text-[#79584B] transition-colors flex items-center">' + safe + icon + '</a></li>';
            }).join('');
        }

        function refreshSupportedConditions(zipcode) {
            const url = @json(route('zipcode.conditions'));
            const params = zipcode ? ('?zipcode=' + encodeURIComponent(zipcode)) : '';

            // Prefer jQuery if present to match existing stack
            if (window.$ && $.ajax) {
                $.ajax({
                    url: url + params,
                    type: 'GET',
                    success: function (res) {
                        if (res && res.success) renderSupportedConditions(res.conditions || [], res.is_local || false);
                    },
                    error: function () {
                        // fallback to default static list
                        renderSupportedConditions([]);
                    }
                });
                return;
            }

            fetch(url + params, { headers: { 'Accept': 'application/json' } })
                .then(r => r.ok ? r.json() : null)
                .then(res => { if (res && res.success) renderSupportedConditions(res.conditions || [], res.is_local || false); })
                .catch(() => renderSupportedConditions([]));
        }

        $('#footer-zipcode-input').on('input', function () {
            $('#zipcode-message').empty();
        });

        $('#footer-zipcode-save').click(function () {

            var zipcodeInput = $('#footer-zipcode-input');
            var zipcode = zipcodeInput.val();

            if (zipcode.length < 6) {
                $('#zipcode-message').html('<span class="text-red-600 font-bold">{{ __("Please enter 6 digits") }}</span>');
                return;
            }

            $.ajax({
                url: "{{ route('admin.zipcode.store') }}",
                type: "POST",
                data: {
                    zipcode: zipcode,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    if (response.status) {
                        var btn = $('#footer-zipcode-save');
                        var originalHtml = btn.html();

                        btn.html('<span>' + "{{ __('Saved') }}" + '</span>')
                            .prop('disabled', true)
                            .removeClass('bg-[#79584B] hover:bg-[#5e4339]')
                            .addClass('bg-green-600 text-white');

                        // Show formatted text inside input and make readonly
                        zipcodeInput.val("{{ __('Your Zipcode') }}: " + zipcode)
                            .prop('readonly', true)
                            .attr('maxlength', '')
                            .removeClass('bg-[#F9F9F9] border-gray-200')
                            .addClass('bg-green-50 border-green-200');

                        // Sync with find-practitioner-zipcode-input if it exists on the page
                        $('#find-practitioner-zipcode-input').val(zipcode).prop('readonly', true);
                        $('#find-practitioner-zipcode-btn').hide();
                        $('#find-practitioner-zipcode-delete').show();

                        // Sync with hero search bar postal code input if empty
                        var heroPincodeInput = $('#hero_search_placeholder_2');
                        if (heroPincodeInput.length && !heroPincodeInput.val()) {
                            heroPincodeInput.val(zipcode);
                        }

                        $('#zipcode-message').html(
                            '<span class="text-green-600 font-bold">' + response.message + '</span>'
                        );

                        refreshSupportedConditions(zipcode);

                        setTimeout(function () {
                            $('#zipcode-message').empty();
                            btn.html(originalHtml)
                                .prop('disabled', false)
                                .removeClass('bg-green-600')
                                .addClass('bg-[#79584B] hover:bg-[#5e4339]')
                                .hide();

                            $('#footer-zipcode-delete').removeClass('bg-green-600').addClass('bg-primary hover:bg-[#5e4339]').show();
                        }, 2000);
                    }

                },
                error: function (xhr) {
                    var errorMessage = 'Validation error';
                    if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.zipcode) {
                        errorMessage = xhr.responseJSON.errors.zipcode[0];
                    }
                    $('#zipcode-message').html(
                        '<span class="text-red-600 font-bold">' + errorMessage + '</span>'
                    );
                }
            });

        });

        $('#footer-zipcode-delete').click(function () {
            $.ajax({
                url: "{{ route('admin.zipcode.delete') }}",
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status) {
                        $('#footer-zipcode-input').val('')
                            .prop('readonly', false)
                            .attr('maxlength', '6')
                            .removeClass('bg-green-50 border-green-200')
                            .addClass('bg-[#F9F9F9] border-gray-200');

                        // Sync with find-practitioner-zipcode-input if it exists on the page
                        $('#find-practitioner-zipcode-input').val('').prop('readonly', false);
                        $('#find-practitioner-zipcode-btn').show();
                        $('#find-practitioner-zipcode-delete').hide();

                        $('#footer-zipcode-delete').removeClass('bg-green-600').addClass('bg-primary').hide();
                        $('#footer-zipcode-save').removeClass('bg-green-600 hover:bg-green-700').addClass('bg-[#79584B] hover:bg-[#5e4339]').show();

                        $('#zipcode-message').html(
                            '<span class="text-blue-600 font-bold">' + response.message + '</span>'
                        );

                        refreshSupportedConditions(null);
                        setTimeout(function () {
                            $('#zipcode-message').empty();
                        }, 2000);
                    }
                }
            });
        });

        // If a zipcode is already saved, refresh the supported conditions on load.
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('footer-zipcode-input');
            const saved = input ? (input.getAttribute('data-zipcode') || '') : '';
            refreshSupportedConditions(saved && saved.length === 6 ? saved : null);
        });

        function toggleFooterMenu(menuId) {
            if (window.innerWidth >= 768) return; // Only run on mobile

            // Toggle menu visibility
            $('#' + menuId + '-menu').slideToggle('fast');

            // Toggle icon
            var icon = $('#' + menuId + '-icon');
            if (icon.hasClass('ri-add-line')) {
                icon.removeClass('ri-add-line').addClass('ri-subtract-line');
            } else {
                icon.removeClass('ri-subtract-line').addClass('ri-add-line');
            }
        }
    </script>
</footer>

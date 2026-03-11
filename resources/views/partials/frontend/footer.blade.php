<!-- Footer -->
<footer class="text-[#2E2E2E]">
    <div class="container-fluid mx-auto relative z-10">
        <!-- Newsletter Section -->
        <div class="bg-[#79584B] px-6 py-8 md:px-12 flex flex-col md:flex-row items-center justify-center gap-8">
            <h3 id="footer-newsletter-title"
                class="text-white text-lg md:text-xl font-regular text-center md:text-left font-sans!" data-i18n="Join our newsletter for weekly wellness tips.">
                {{ __('Join our newsletter for weekly wellness tips.') }}
            </h3>
            <div class="flex w-full md:w-auto gap-2">
                <input id="footer-newsletter-input" type="email" placeholder="{{ __('Your email...') }}" data-i18n="Your email..."
                    class="bg-[#F2F2F2] text-[#2E4B3C] placeholder-gray-400 rounded-lg px-4 py-3 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-[#DFAF7F]">
                <button id="footer-newsletter-btn"
                    class="bg-[#FFD28D] hover:bg-[#e0caaa] text-[#2E4B3C] rounded-lg px-6 py-3 transition-all flex items-center justify-center">
                    <i class="ri-send-plane-2-fill text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="bg-gradient-to-b from-[#FFE7CF] to-[#DFAF7F] ">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 px-6 pt-12 pb-18 sm:px-12 md:px-12 md:py-16 relative">
                <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt=""
                    class="absolute bottom-0 left-0 w-100 z-0 pointer-events-none">
                <!-- Column 1: Logo & Tagline -->
                <div class="flex flex-col items-start space-y-6 z-1">
                    <a href="{{ route('home') }}" class="block">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                            class="h-24 w-auto object-contain">
                    </a>
                    <p id="footer-tagline" class="text-[#2E2E2E] opacity-80 text-sm leading-relaxed max-w-xs" data-i18n="Empowering your wellness journey through ancient wisdom and modern science.">
                        {{ __('Empowering your wellness journey through ancient wisdom and modern science.') }}
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h4 id="footer-quick-links-title" class="font-medium font-sans! mb-6 text-xl text-[#2E2E2E]" data-i18n="Quick Links">
                        {{ __('Quick Links') }}</h4>
                    <ul class="space-y-4 text-base font-regular text-[#2E2E2E]/80">
                        <li><a id="footer-home" href="{{ route('home') }}"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Home">{{ __('Home') }}</a></li>
                        <li><a id="footer-who-we-are" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Who we are">{{ __('Who we are') }}</a></li>
                        <li><a id="footer-what-we-do" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="What we do">{{ __('What we do') }}</a></li>
                        <li><a id="footer-our-team" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Our Team">{{ __('Our Team') }}</a></li>
                        <li><a id="footer-blog" href="{{ route('blogs') }}"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Blog">{{ __('Blog') }}</a></li>
                        <li><a id="footer-contact-us" href="{{ route('contact-us') }}"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Contact Us">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>

                <!-- Column 3: Conditions -->
                <div class="z-1">
                    <h4 id="footer-conditions-title" class="font-medium font-sans! mb-6 text-xl text-[#2E2E2E]" data-i18n="Conditions We Support">
                        {{ __('Conditions We Support') }}</h4>
                    <ul class="space-y-4 text-base font-regular text-[#2E2E2E]/80">
                        <li><a id="footer-life-transitions" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Life Transitions">{{ __('Life Transitions') }}</a></li>
                        <li><a id="footer-mental-imbalance" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Mental Imbalance">{{ __('Mental Imbalance') }}</a></li>
                        <li><a id="footer-stress-reduction" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Stress Reduction">{{ __('Stress Reduction') }}</a></li>
                        <li><a id="footer-toxin-removal" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Toxin Removal">{{ __('Toxin Removal') }}</a></li>
                        <li><a id="footer-chronic-pain" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Chronic Pain">{{ __('Chronic Pain') }}</a></li>
                        <li><a id="footer-immune-support" href="#"
                                class="hover:text-[#79584B] transition-colors" data-i18n="Immune Support">{{ __('Immune Support') }}</a></li>
                    </ul>
                </div>

                <!-- Column 4: Pincode & Socials -->
                <div class="z-1">
                    <h4 id="footer-pincode-title" class="text-lg font-semibold text-[#79584B] mb-4" data-i18n="Save your pincode & find nearby care.">
                        {{ __('Save your pincode & find nearby care.') }}</h4>
                    
                    <div id="pincode-message" class="min-h-[24px] mb-2 text-xs font-bold"></div>

                    <form class="flex gap-2 mb-10 items-center">
                        <div class="relative flex-1">
                            @php
                                $savedPincode = session('global_pincode');
                                $displayValue = $savedPincode ? __('Your Pincode') . ': ' . $savedPincode : '';
                            @endphp
                            <input id="footer-pincode-input" type="text" placeholder="{{ __('Enter Pincode') }}" data-i18n="Enter Pincode"
                                maxlength="{{ $savedPincode ? '' : '6' }}" 
                                {{ $savedPincode ? 'readonly' : '' }}
                                oninput="if(!this.readOnly) this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                value="{{ $displayValue }}"
                                class="{{ $savedPincode ? 'bg-green-50 border-green-200' : 'bg-[#F9F9F9] border-gray-200' }} placeholder-gray-400 text-gray-800 rounded px-4 h-11 w-full text-sm focus:outline-none focus:border-[#79584B] transition-all border">
                        </div>
                        <button id="footer-pincode-save" type="button" style="{{ session('global_pincode') ? 'display:none;' : '' }}"
                            class="bg-[#79584B] h-11 text-white font-medium rounded px-6 text-sm hover:bg-[#5e4339] transition-all shadow-sm flex items-center justify-center gap-2 whitespace-nowrap min-w-[110px]" data-i18n="Save">
                            <span>{{ __('Save') }}</span>
                        </button>
                        <button id="footer-pincode-delete" type="button" style="{{ session('global_pincode') ? '' : 'display:none;' }}"
                            class="bg-red-500 h-11 text-white font-medium rounded px-4 text-lg hover:bg-red-600 transition-all shadow-sm flex items-center justify-center whitespace-nowrap" title="{{ __('Delete') }}">
                            <i class="ri-delete-bin-line"></i>
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
                        <a id="footer-privacy" href="#"
                            class="hover:text-[#79584B] transition-colors" data-i18n="Privacy Policy">{{ __('Privacy Policy') }}</a>
                        <a id="footer-cookie" href="#"
                            class="hover:text-[#79584B] transition-colors" data-i18n="Cookie Policy">{{ __('Cookie Policy') }}</a>
                        <a id="footer-terms" href="#"
                            class="hover:text-[#79584B] transition-colors" data-i18n="Terms & Conditions">{{ __('Terms & Conditions') }}</a>
                        <a id="footer-gdpr" href="#"
                            class="hover:text-[#79584B] transition-colors" data-i18n="GDPR & Data Protection">{{ __('GDPR & Data Protection') }}</a>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-[#252525] cursor-default border-t border-[#252525]/80 py-4"> 
                <p id="footer-all-rights" data-i18n="All rights reserved.">{{ __('All rights reserved.') }} &copy; {{ date('Y') }} Zaya Wellness</p>
            </div>
        </div>


    </div>

    <script>
$('#footer-pincode-input').on('input', function() {
    $('#pincode-message').empty();
});

$('#footer-pincode-save').click(function(){

    var pincodeInput = $('#footer-pincode-input');
    var pincode = pincodeInput.val();
    
    if(pincode.length < 6) {
        $('#pincode-message').html('<span class="text-red-600 font-bold">{{ __("Please enter 6 digits") }}</span>');
        return;
    }

    $.ajax({
        url: "{{ route('admin.pincode.store') }}",
        type: "POST",
        data: {
            pincode: pincode,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            if(response.status){
                var btn = $('#footer-pincode-save');
                var originalHtml = btn.html();
                
                btn.html('<span>' + "{{ __('Saved') }}" + '</span>')
                   .prop('disabled', true)
                   .removeClass('bg-[#79584B] hover:bg-[#5e4339]')
                   .addClass('bg-green-600 text-white');
                
                // Show formatted text inside input and make readonly
                pincodeInput.val("{{ __('Your Pincode') }}: " + pincode)
                            .prop('readonly', true)
                            .attr('maxlength', '')
                            .removeClass('bg-[#F9F9F9] border-gray-200')
                            .addClass('bg-green-50 border-green-200');

                $('#pincode-message').html(
                    '<span class="text-green-600 font-bold">'+response.message+'</span>'
                );

                setTimeout(function(){
                    $('#pincode-message').empty();
                    btn.html(originalHtml)
                       .prop('disabled', false)
                       .removeClass('bg-green-600')
                       .addClass('bg-[#79584B] hover:bg-[#5e4339]')
                       .hide(); 
                    
                    $('#footer-pincode-delete').removeClass('bg-green-600').addClass('bg-red-500 hover:bg-red-600').show(); 
                }, 2000);
            }

        },
        error: function(xhr){
             var errorMessage = 'Validation error';
             if(xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.pincode) {
                 errorMessage = xhr.responseJSON.errors.pincode[0];
             }
            $('#pincode-message').html(
                '<span class="text-red-600 font-bold">'+errorMessage+'</span>'
            );
        }
    });

});

$('#footer-pincode-delete').click(function(){
    $.ajax({
        url: "{{ route('admin.pincode.delete') }}",
        type: "DELETE",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response){
            if(response.status){
                $('#footer-pincode-input').val('')
                                         .prop('readonly', false)
                                         .attr('maxlength', '6')
                                         .removeClass('bg-green-50 border-green-200')
                                         .addClass('bg-[#F9F9F9] border-gray-200');
                $('#footer-pincode-delete').removeClass('bg-green-600').addClass('bg-red-500').hide();
                $('#footer-pincode-save').removeClass('bg-green-600 hover:bg-green-700').addClass('bg-[#79584B] hover:bg-[#5e4339]').show();
                
                $('#pincode-message').html(
                    '<span class="text-blue-600 font-bold">'+response.message+'</span>'
                );
                setTimeout(function(){
                    $('#pincode-message').empty();
                }, 2000);
            }
        }
    });
});
</script>
</footer>
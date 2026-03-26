<aside class="page-sidebar">



    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
    <div class="main-sidebar" id="main-sidebar">
        <ul class="sidebar-menu" id="simple-bar">
            @php
                $adminPanelSettings = \App\Models\HomepageSetting::getSectionValues('admin_panel', session('locale', 'en'));
            @endphp
            <li class="pin-title sidebar-main-title">
                <div>
                    <h5 class="sidebar-title f-w-700">{{ $adminPanelSettings['admin_panel_sidebar_pinned'] ?? 'Pinned' }}</h5>
                </div>
            </li>
            @if(auth()->user()->hasPermission('dashboard-view') || auth()->user()->hasPermission('doctors-view') || auth()->user()->hasPermission('practitioners-view') || auth()->user()->hasPermission('mindfulness-practitioners-view') || auth()->user()->hasPermission('yoga-therapists-view') || auth()->user()->hasPermission('clients-view') || auth()->user()->hasPermission('translators-view') || auth()->user()->hasPermission('testimonials-view') || auth()->user()->hasPermission('services-view') || auth()->user()->hasPermission('practitioner-reviews-view'))
            <li class="sidebar-main-title">
                <div>
                    <h5 class="lan-1 f-w-700 sidebar-title">{{ $adminPanelSettings['admin_panel_sidebar_general'] ?? 'General' }}</h5>
                </div>
            </li>
            @endif
            @if(auth()->user()->hasPermission('dashboard-view'))
            <li class="sidebar-list"><a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Home-dashboard') }}"></use>
                    </svg>
                    <h6>{{ $adminPanelSettings['admin_panel_sidebar_dashboard'] ?? 'Dashboard' }}</h6>
                </a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link" href="{{ route('admin.profile') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6>{{ $adminPanelSettings['admin_panel_sidebar_my_profile'] ?? 'My Profile' }}</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('doctors-view') || auth()->user()->hasPermission('practitioners-view') || auth()->user()->hasPermission('mindfulness-practitioners-view') || auth()->user()->hasPermission('yoga-therapists-view') || auth()->user()->hasPermission('clients-view') || auth()->user()->hasPermission('translators-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_users'] ?? 'Users' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('doctors-view'))
                    <li> <a href="{{ route('admin.doctors.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_doctors'] ?? 'Doctors' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('practitioners-view'))
                    <li> <a href="{{ route('admin.practitioners.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_practitioners'] ?? 'Practitioners' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('mindfulness-practitioners-view'))
                    <li> <a href="{{ route('admin.mindfulness-practitioners.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_mindfulness_practitioners'] ?? 'Mindfulness Practitioners' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('yoga-therapists-view'))
                    <li> <a href="{{ route('admin.yoga-therapists.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_yoga_therapists'] ?? 'Yoga Therapists' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('clients-view'))
                    <li> <a href="{{ route('admin.clients.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_clients'] ?? 'Clients' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('translators-view'))
                    <li> <a href="{{ route('admin.translators.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_translators'] ?? 'Translators' }}</a></li>
                    @endif
                </ul>
            </li>
            @endif
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_backend_users'] ?? 'Backend Users' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('admins-view'))
                    <li> <a href="{{ route('admin.admins.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_admins'] ?? 'Admins' }}</a></li>
                    <li> <a href="{{ route('admin.finance-managers.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_finance_manager'] ?? 'Finance Manager' }}</a></li>
                    <li> <a href="{{ route('admin.content-managers.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_content_manager'] ?? 'Content Manager' }}</a></li>
                    <li> <a href="{{ route('admin.user-managers.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_user_manager'] ?? 'User Manager' }}</a></li>


                    @endif
                </ul>
            </li>

            @if(auth()->user()->hasPermission('credentials-view'))
            <li class="sidebar-list d-none"> <a class="sidebar-link" href="{{ route('admin.credentials.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Password') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_credentials'] ?? 'Credentials' }}</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('testimonials-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="{{ route('admin.testimonials.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Chat') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_testimonials'] ?? 'Testimonials' }}</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('services-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_services'] ?? 'Services' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.services.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_all_services'] ?? 'All Services' }}</a></li>
                    @if(auth()->user()->hasPermission('master-data-view'))
                    <li> <a href="{{ route('admin.master-data.index', 'service_categories') }}">{{ $adminPanelSettings['admin_panel_sidebar_service_categories'] ?? 'Service Categories' }}</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('packages-view') || auth()->user()->hasPermission('other-fees-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Wallet') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_finance'] ?? 'Finance' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('packages-view'))
                    <li> <a href="{{ route('admin.packages.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_packages'] ?? 'Packages' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('promo-codes-view'))
                    <li> <a href="{{ route('admin.promo-codes.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_promo_codes'] ?? 'Promo Codes' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('other-fees-view'))
                    <li> <a href="{{ route('admin.other-fees.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_other_fees'] ?? 'Other Fees' }}</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('practitioner-reviews-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#star') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_reviews'] ?? 'Reviews' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.reviews.practitioners.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_practitioner_reviews'] ?? 'Practitioner' }}</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('roles-view') || auth()->user()->hasPermission('settings-view') || auth()->user()->hasPermission('master-data-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Setting') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_master_settings'] ?? 'Master Settings' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('roles-view'))
                    <li> <a href="{{ route('admin.roles.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_roles'] ?? 'Roles' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('settings-view'))
                    <li> <a href="{{ route('admin.general-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_site_settings'] ?? 'Site Settings' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('master-data-view'))
                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_doctor_settings'] ?? 'Doctor Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'specializations') }}">{{ $adminPanelSettings['admin_panel_sidebar_specializations'] ?? 'Specializations' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'expertises') }}">{{ $adminPanelSettings['admin_panel_sidebar_ayurveda_expertises'] ?? 'Ayurveda Expertises' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'conditions') }}">{{ $adminPanelSettings['admin_panel_sidebar_health_conditions'] ?? 'Health Conditions' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'therapies') }}">{{ $adminPanelSettings['admin_panel_sidebar_external_therapies'] ?? 'External Therapies' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_practitioner_settings'] ?? 'Practitioner Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'wellness_consultations') }}">{{ $adminPanelSettings['admin_panel_sidebar_wellness_consultations'] ?? 'Wellness Consultations' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'body_therapies') }}">{{ $adminPanelSettings['admin_panel_sidebar_body_therapies'] ?? 'Massage & Body Therapies' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'practitioner_modalities') }}">{{ $adminPanelSettings['admin_panel_sidebar_other_modalities'] ?? 'Other Modalities' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_client_settings'] ?? 'Client Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'client_consultation_preferences') }}">{{ $adminPanelSettings['admin_panel_sidebar_consultation_preferences'] ?? 'Consultation Preferences' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_mindfulness_settings'] ?? 'Mindfulness Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'mindfulness_services') }}">{{ $adminPanelSettings['admin_panel_sidebar_services_offered'] ?? 'Services Offered' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'client_concerns') }}">{{ $adminPanelSettings['admin_panel_sidebar_client_concerns'] ?? 'Client Concerns' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_translator_settings'] ?? 'Translator Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'translator_services') }}">{{ $adminPanelSettings['admin_panel_sidebar_translator_services'] ?? 'Services Offered' }}</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'translator_specializations') }}">{{ $adminPanelSettings['admin_panel_sidebar_translator_specializations'] ?? 'Specializations' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>{{ $adminPanelSettings['admin_panel_sidebar_yoga_therapist_settings'] ?? 'Yoga Therapist Settings' }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'yoga_expertises') }}">{{ $adminPanelSettings['admin_panel_sidebar_yoga_expertise'] ?? 'Expertise' }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.countries.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_countries'] ?? 'Countries' }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.languages.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_languages'] ?? 'Languages' }}</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('home-page-view') || auth()->user()->hasPermission('about-page-view') || auth()->user()->hasPermission('services-page-view') || auth()->user()->hasPermission('settings-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Document') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_page_settings'] ?? 'Page Settings' }}</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('home-page-view'))
                    <li> <a href="{{ route('admin.homepage-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_homepage_settings'] ?? 'Homepage Settings' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('about-page-view'))
                    <li> <a href="{{ route('admin.about-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_about_settings'] ?? 'About Us Settings' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('services-page-view'))
                    <li> <a href="{{ route('admin.services-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_services_page_settings'] ?? 'Services Page Settings' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('gallery-page-view'))
                    <li> <a href="{{ route('admin.gallery-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_gallery_settings'] ?? 'Gallery Settings' }}</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('home-page-view'))
                    <li> <a href="{{ route('admin.find-practitioner-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_find_practitioner_settings'] ?? 'Find Practitioner Settings' }}</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('settings-view'))
                    <li> <a href="{{ route('admin.contact-us.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_contact_us_settings'] ?? 'Contact Us Settings' }}</a></li>
                    @endif
                    <li> <a href="{{ route('admin.admin-panel-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_admin_panel_settings'] ?? 'Admin Panel Settings' }}</a></li>
                    
                    <li> <a href="{{ route('admin.footer-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_footer_settings'] ?? 'Footer Page Settings' }}</a></li>
                    
                    <li> <a href="{{ route('admin.client-pannel-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_client_pannel_settings'] ?? 'Client Pannel Settings' }}</a></li>
                    
                    <li> <a href="{{ route('admin.invoice-settings.index') }}">{{ $adminPanelSettings['admin_panel_sidebar_invoice_settings'] ?? 'Invoice Settings' }}</a></li>

                </ul>

               
            </li>
            @endif
           
            @if(auth()->user()->hasPermission('contact-messages-view'))
            <li class="sidebar-list">
                <a class="sidebar-link" href="{{ route('admin.contact-us.messages') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Message') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_contact_messages'] ?? 'Contact Messages' }}</h6>
                </a>
            </li>
            <li class="sidebar-list">
                <a class="sidebar-link" href="{{ route('admin.newsletters.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Message') }}"></use>
                    </svg>
                    <h6 class="f-w-600">{{ $adminPanelSettings['admin_panel_sidebar_newsletters'] ?? 'Newsletters' }}</h6>
                </a>
            </li>
            @endif
            
        </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    <div class="sidebar-footer-image">
        <img src="{{ asset('assets/leaves/admin-leaf-img.webp') }}" alt="leafs">
    </div>
</aside>

@push('scripts')
<script>
    (function() {
        function getScrollContainer(el) {
            let node = el;
            while (node && node !== document.body) {
                const style = window.getComputedStyle(node);
                if ((style.overflowY === 'auto' || style.overflowY === 'scroll') && node.scrollHeight > node.clientHeight) {
                    return node;
                }
                node = node.parentElement;
            }
            return document.scrollingElement || document.documentElement;
        }

        function bringIntoView(el) {
            if (!el) return;
            const container = getScrollContainer(el);
            if (!container) return;

            const cRect = container.getBoundingClientRect();
            const eRect = el.getBoundingClientRect();
            const padding = 16;

            if (eRect.bottom > cRect.bottom) {
                container.scrollTop += (eRect.bottom - cRect.bottom) + padding;
            } else if (eRect.top < cRect.top) {
                container.scrollTop -= (cRect.top - eRect.top) + padding;
            }
        }

        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.sidebar-link, .submenu-title');
            if (!toggle) return;

            const target = toggle.closest('li') || toggle;
            // Wait for collapse animation to finish
            setTimeout(function() {
                bringIntoView(target);
            }, 220);
        });
    })();
</script>
@endpush

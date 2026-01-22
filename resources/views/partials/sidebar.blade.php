<aside class="page-sidebar">
    <div class="logo-wrapper d-flex align-items-center col-auto d-lg-none p-3">
        <a href="{{ route('admin.dashboard') }}">
            <img class="light-logo img-fluid" src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="max-height: 50px;" />

        </a>
        <a class="close-btn toggle-sidebar ms-auto" href="javascript:void(0)">
            <svg class="svg-color">
                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
            </svg>
        </a>
    </div>


    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
    <div class="main-sidebar" id="main-sidebar">
        <ul class="sidebar-menu" id="simple-bar">
            <li class="pin-title sidebar-main-title">
                <div>
                    <h5 class="sidebar-title f-w-700">Pinned</h5>
                </div>
            </li>
            <li class="sidebar-main-title">
                <div>
                    <h5 class="lan-1 f-w-700 sidebar-title">General</h5>
                </div>
            </li>
            <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Home-dashboard') }}"></use>
                    </svg>
                    <h6>Dashboard</h6>
                </a>
            </li>
            <li class="sidebar-list"> <i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Users</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                    <li> <a href="{{ route('admin.practitioners.index') }}">Practitioners</a></li>
                    <li> <a href="{{ route('admin.mindfulness-practitioners.index') }}">Mindfulness Counsellors</a></li>
                    <li> <a href="{{ route('admin.yoga-therapists.index') }}">Yoga Therapists</a></li>
                    <li> <a href="{{ route('admin.clients.index') }}">Clients</a></li>
                    <li> <a href="{{ route('admin.translators.index') }}">Translators</a></li>
                </ul>
            </li>

            <li class="sidebar-list"> <i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Setting') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Master Settings</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.roles.index') }}">Roles</a></li>

                    <li>
                        <a class="submenu-title mt-2 fw-bold text-primary d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Doctor Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'specializations') }}">Specializations</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'expertises') }}">Ayurveda Expertises</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'conditions') }}">Health Conditions</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'therapies') }}">External Therapies</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 fw-bold text-primary d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Practitioner Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'wellness_consultations') }}">Wellness Consultations</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'body_therapies') }}">Massage & Body Therapies</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'practitioner_modalities') }}">Other Modalities</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 fw-bold text-primary d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Client Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'client_consultation_preferences') }}">Consultation Preferences</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 fw-bold text-primary d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Mindfulness Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'mindfulness_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'client_concerns') }}">Client Concerns</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 fw-bold text-primary d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Translator Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'translator_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'translator_specializations') }}">Specializations</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</aside>
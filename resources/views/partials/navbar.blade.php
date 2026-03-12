<header class="page-header row">
    <style>
        .language-menu-list .lang,
        .country-menu-list .lang {
            padding: 8px 12px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .language-menu-list .lang:hover,
        .country-menu-list .lang:hover {
            background-color: #f4f4f4 !important;
            color: #97563D !important;
        }

        .language-menu-list .lang.active,
        .country-menu-list .lang.active {
            background-color: #f0f0f0;
        }

        .lang-dropdown-trigger::before,
        .lang-dropdown-trigger::after,
        .country-dropdown-trigger::before,
        .country-dropdown-trigger::after {
            display: none !important;
        }

        .header-right>li {
            position: relative;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
    </style>
    <div class="logo-wrapper d-flex align-items-center col-auto">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none me-3">
            <img src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="width: 60px;">
        </a>
        <a class="close-btn toggle-sidebar" href="javascript:void(0)">
            <svg class="svg-color">
                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
            </svg>
        </a>
    </div>
    <div class="logo-icon-wrapper col-auto px-0 d-none">
    </div>
    <div class="page-main-header col">
        <div class="header-left">
        </div>
        <div class="nav-right">
            <ul class="header-right">
                <li class="custom-dropdown">
                    @php
                    $allLanguages = \App\Models\Language::all();
                    $user = auth()->user();
                    $role = $user ? $user->roleData() : null;

                    if ($role && $role->name === 'Super Admin') {
                    $languages = $allLanguages;
                    } else {
                    $assignedIds = $user?->languages;
                    if (is_array($assignedIds)) {
                    $languages = $allLanguages->whereIn('id', $assignedIds);
                    } elseif ($assignedIds) {
                    $languages = $allLanguages->where('id', $assignedIds);
                    } else {
                    $languages = collect();
                    }
                    }

                    // Filter unique base languages by Name (e.g., only one "English" even if regional variants exist)
                    $languages = $languages->groupBy('name')->map(function($group) {
                    // Prefer the shortest code (e.g., 'en' over 'en-GB')
                    return $group->sortBy(fn($l) => strlen($l->code))->first();
                    });

                    if (!function_exists('getCountryCode')) {
                    function getCountryCode($lang) {
                    if (!$lang) return 'us';

                    // Map language codes to country codes for flags
                    $mapping = [
                    'en' => 'us', 'ar' => 'sa', 'zh' => 'cn', 'ja' => 'jp',
                    'ko' => 'kr', 'hi' => 'in', 'bn' => 'bd', 'uk' => 'ua',
                    'en-GB' => 'gb', 'en-US' => 'us', 'en-AU' => 'au', 'en-CA' => 'ca',
                    'fr-FR' => 'fr', 'fr-CA' => 'ca', 'es-ES' => 'es', 'es-US' => 'us',
                    'pt-PT' => 'pt', 'pt-BR' => 'br',
                    'el' => 'gr', // Greek -> Greece
                    'hy' => 'am', // Armenian -> Armenia
                    'cs' => 'cz', // Czech -> Czech Republic
                    'sq' => 'al', // Albanian -> Albania
                    'af' => 'za', // Afrikaans -> South Africa
                    'am' => 'et', // Amharic -> Ethiopia
                    'sv' => 'se', // Swedish -> Sweden
                    'da' => 'dk', // Danish -> Denmark
                    'nb' => 'no', // Norwegian -> Norway
                    'nn' => 'no', // Norwegian -> Norway
                    'no' => 'no', // Norwegian -> Norway
                    'he' => 'il', // Hebrew -> Israel
                    'fa' => 'ir', // Persian -> Iran
                    'ms' => 'my', // Malay -> Malaysia
                    'vi' => 'vn', // Vietnamese -> Vietnam
                    'th' => 'th', // Thailand
                    ];

                    $code = $lang->code;
                    if (isset($mapping[$code])) return $mapping[$code];

                    $base = explode('-', $code)[0];
                    if (isset($mapping[$base])) return $mapping[$base];

                    // If mapping fails, try converting emoji if available
                    if ($lang->flag) {
                    $emojiIso = emojiToISO($lang->flag);
                    if ($emojiIso && $emojiIso !== 'us') return $emojiIso;
                    }

                    return strtolower($base);
                    }
                    }

                    if (!function_exists('emojiToISO')) {
                    function emojiToISO($emoji) {
                    if (!$emoji) return 'us';
                    $chars = preg_split('//u', $emoji, -1, PREG_SPLIT_NO_EMPTY);
                    $iso = '';
                    foreach ($chars as $char) {
                    $code = mb_ord($char, 'UTF-8') - 127397;
                    if ($code >= 65 && $code <= 90) {
                        $iso .=chr($code);
                        }
                        }
                        return strtolower($iso) ?: 'us' ;
                        }
                        }
                        $currentLocale=session('locale', config('app.locale', 'en' ));
                        $currentLanguage=$allLanguages->where('code', $currentLocale)->first();

                        $allCountries = \App\Models\Country::all();
                        if ($role && $role->name === 'Super Admin') {
                        $userCountries = $allCountries;
                        } else {
                        $assignedCountryIds = $user?->national_id;
                        if (is_array($assignedCountryIds)) {
                        $userCountries = $allCountries->whereIn('id', $assignedCountryIds);
                        } elseif ($assignedCountryIds) {
                        $userCountries = $allCountries->where('id', $assignedCountryIds);
                        } else {
                        $userCountries = collect();
                        }
                        }

                        $currentCountryCode = session('admin_country', 'us');
                        // Fallback to first assigned country if current session country is not in assigned list
                        if (!$userCountries->where('code', strtoupper($currentCountryCode))->first()) {
                        $firstAssigned = $userCountries->first();
                        $currentCountryCode = $firstAssigned ? strtolower($firstAssigned->code) : 'us';
                        session(['admin_country' => $currentCountryCode]);
                        }
                        $currentCountry = $userCountries->where('code', strtoupper($currentCountryCode))->first();
                        @endphp

                        <a class="lang lang-dropdown-trigger" href="javascript:void(0)" style="min-width: 100px; display: flex; align-items: center; text-decoration: none; padding: 10px 0; gap: 6px; background: none !important; border-radius: 0 !important;">
                            <i class="fa-solid fa-language text-muted" style="font-size: 14px;"></i>
                            <img src="{{ asset('admiro/assets/fonts/flag-icon/' . ($currentLanguage ? getCountryCode($currentLanguage) : 'us') . '.svg') }}" style="width: 20px; height: 14px; border: 1px solid #eee; border-radius: 2px;" alt="flag">
                            <h6 class="lang-txt f-w-700 mb-0" style="color: #2b2b2b; font-size: 13px;">{{ $currentLanguage ? strtoupper($currentLanguage->code) : 'EN' }}</h6>
                        </a>

                        <div class="custom-menu overflow-hidden">
                            <div class="dropdown-header py-2 px-3 border-bottom bg-light">
                                <span class="f-w-700 text-dark small">SELECT LANGUAGE</span>
                            </div>
                            <ul class="profile-body language-menu-list" style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                @if($languages->isEmpty())
                                <li class="p-3 text-center text-muted small">No assigned languages</li>
                                @endif
                                @foreach($languages as $lang)
                                <li class="d-flex align-items-center last-0" style="cursor: pointer;">
                                    <a href="javascript:void(0)"
                                        class="lang d-flex align-items-center w-100 {{ $currentLocale == $lang->code ? 'active text-primary' : '' }}"
                                        data-value="{{ $lang->code }}"
                                        data-flag="{{ asset('admiro/assets/fonts/flag-icon/' . getCountryCode($lang) . '.svg') }}"
                                        onclick="changeLanguage(this)"
                                        style="text-decoration: none; color: inherit; padding: 8px 12px !important;">
                                        <img src="{{ asset('admiro/assets/fonts/flag-icon/' . getCountryCode($lang) . '.svg') }}" style="width: 18px; height: 13px; margin-right: 10px; border: 1px solid #f0f0f0; border-radius: 1px;" alt="flag">
                                        <span class="f-w-600 small">{{ strtoupper($lang->name) }}</span>
                                        @if($currentLocale == $lang->code)
                                        <i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>
                                        @endif
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                </li>

                <li class="custom-dropdown">
                    <a class="lang country-dropdown-trigger" href="javascript:void(0)" style="min-width: 100px; display: flex; align-items: center; text-decoration: none; padding: 10px 0; gap: 6px; background: none !important; border-radius: 0 !important;">
                        <i class="fa-solid fa-earth-americas text-muted" style="font-size: 14px;"></i>
                        <img src="{{ asset('admiro/assets/fonts/flag-icon/' . ($currentCountry ? strtolower($currentCountry->code) : 'us') . '.svg') }}" style="width: 20px; height: 14px; border: 1px solid #eee; border-radius: 2px;" alt="flag">
                        <h6 class="country-txt f-w-700 mb-0" style="color: #2b2b2b; font-size: 13px;">{{ $currentCountry ? strtoupper($currentCountry->code) : 'US' }}</h6>
                    </a>

                    <div class="custom-menu overflow-hidden">
                        <div class="dropdown-header py-2 px-3 border-bottom bg-light">
                            <span class="f-w-700 text-dark small">SELECT REGION</span>
                        </div>
                        <ul class="profile-body country-menu-list" style="max-height: 350px; overflow-y: auto; padding: 5px;">
                            @if($userCountries->isEmpty())
                            <li class="p-3 text-center text-muted small">No assigned regions</li>
                            @endif
                            @foreach($userCountries as $country)
                            <li class="d-flex align-items-center last-0" style="cursor: pointer;">
                                <a href="javascript:void(0)"
                                    class="lang d-flex align-items-center w-100 {{ strtoupper($currentCountryCode) == $country->code ? 'active text-primary' : '' }}"
                                    data-value="{{ $country->code }}"
                                    data-flag="{{ asset('admiro/assets/fonts/flag-icon/' . strtolower($country->code) . '.svg') }}"
                                    onclick="changeCountry(this)"
                                    style="text-decoration: none; color: inherit; padding: 8px 12px !important;">
                                    <img src="{{ asset('admiro/assets/fonts/flag-icon/' . strtolower($country->code) . '.svg') }}" style="width: 18px; height: 13px; margin-right: 10px; border: 1px solid #f0f0f0; border-radius: 1px;" alt="flag">
                                    <span class="f-w-600 small">{{ strtoupper($country->name) }}</span>
                                    @if(strtoupper($currentCountryCode) == $country->code)
                                    <i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="profile-nav custom-dropdown">
                    <div class="user-wrap">
                        <div class="user-img"><img src="{{ asset('admiro/assets/images/profile.png') }}" alt="user" /></div>
                        <div class="user-content">
                            <h6>{{ $user?->name ?? 'Guest' }}</h6>
                            <p class="mb-0 text-capitalize">{{ $user?->role ?? 'User' }}<i class="fa-solid fa-chevron-down"></i></p>
                        </div>
                    </div>
                    <div class="custom-menu overflow-hidden">
                        <ul class="profile-body">
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                                </svg><a class="ms-2" href="{{ route('admin.profile') }}">My Profile</a>
                            </li>
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Login') }}"></use>
                                </svg>
                                <a class="ms-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<script>
    // Sync localStorage with current session locale
    localStorage.setItem('selectedLanguage', '{{ $currentLocale }}');

    function changeLanguage(element) {
        let id = element.getAttribute("data-value");
        let flagUrl = element.getAttribute("data-flag");
        if (!id) return;

        const langName = id.toUpperCase();

        fetch(`{{ url('admin/change-language') }}/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // 1. Update Navbar UI Immediately
                    const currentLangImg = document.querySelector('.lang-dropdown-trigger img');
                    const currentLangText = document.querySelector('.lang-dropdown-trigger .lang-txt');

                    if (currentLangImg && flagUrl) currentLangImg.src = flagUrl;
                    if (currentLangText) currentLangText.textContent = langName;

                    // 2. Update localStorage
                    localStorage.setItem('selectedLanguage', id);

                    // 3. Update active state in dropdown
                    document.querySelectorAll('.language-menu-list .lang').forEach(el => {
                        el.classList.remove('active', 'text-primary');
                        const checkIcon = el.querySelector('.fa-check');
                        if (checkIcon) checkIcon.remove();
                    });

                    element.classList.add('active', 'text-primary');
                    element.insertAdjacentHTML('beforeend', '<i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>');

                    // 4. DYNAMIC FIELD UPDATES:
                    // We can only update form fields dynamically on "Settings" pages.
                    // For the rest of the admin panel (sidebar, dashboard, etc.), 
                    // a page reload is required because those elements are server-side rendered.
                    const settingsForm = document.querySelector('form[id*="SettingsForm"]');
                    if (settingsForm) {
                        const editingLangBadge = document.getElementById('current-editing-lang');
                        if (editingLangBadge) editingLangBadge.textContent = id.toUpperCase();

                        const inputs = settingsForm.querySelectorAll('input[type="text"], input[type="number"], textarea');
                        inputs.forEach(input => {
                            input.value = '';
                            if (data.data && data.data[input.name] !== undefined) {
                                input.value = data.data[input.name];
                            }
                        });

                        if (typeof showToast === 'function') {
                            showToast(`Switched to ${langName} successfully.`);
                        }
                    } else {
                        // For standard CRUD pages, we must reload to refresh the translated UI
                        location.reload();
                    }

                    console.log("Language changed dynamically to:", id);
                } else {
                    console.warn(data.message);
                }
            })
            .catch(error => console.error('Error changing language:', error));
    }

    function changeCountry(element) {
        let code = element.getAttribute("data-value");
        let flagUrl = element.getAttribute("data-flag");
        if (!code) return;

        fetch(`{{ url('admin/change-country') }}/${code}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const currentImg = document.querySelector('.country-dropdown-trigger img');
                    const currentTxt = document.querySelector('.country-dropdown-trigger .country-txt');

                    if (currentImg && flagUrl) currentImg.src = flagUrl;
                    if (currentTxt) currentTxt.textContent = code.toUpperCase();

                    // Update active state
                    document.querySelectorAll('.country-menu-list .lang').forEach(el => {
                        el.classList.remove('active', 'text-primary');
                        const checkIcon = el.querySelector('.fa-check');
                        if (checkIcon) checkIcon.remove();
                    });

                    element.classList.add('active', 'text-primary');
                    element.insertAdjacentHTML('beforeend', '<i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>');

                    if (typeof showToast === 'function') {
                        showToast(`Country changed to ${code.toUpperCase()} successfully.`);
                    }
                }
            })
            .catch(error => console.error('Error changing country:', error));
    }
</script>

<header class="page-header row">
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
            <form class="form-inline search-full col" action="#" method="get">
                <div class="form-group w-100">
                    <div class="Typeahead Typeahead--twitterUsers">
                        <div class="u-posRelative">
                            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Admiro .." name="q" title="" autofocus="autofocus" />
                            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
                        </div>
                        <div class="Typeahead-menu"></div>
                    </div>
                </div>
            </form>
            <div class="form-group-header d-lg-block d-none">
                <div class="Typeahead Typeahead--twitterUsers">
                    <div class="u-posRelative d-flex align-items-center">
                        <input class="demo-input py-0 Typeahead-input form-control-plaintext w-100" type="text" placeholder="Type to Search..." name="q" title="" /><i class="search-bg iconly-Search icli"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-right">
            <ul class="header-right">
                <li class="custom-dropdown">
                    <div class="translate_wrapper">
                        @php
                        $languages = \App\Models\Language::all();
                        if (!function_exists('emojiToISO')) {
                        function emojiToISO($emoji) {
                        $chars = preg_split('//u', $emoji, -1, PREG_SPLIT_NO_EMPTY);
                        $iso = '';
                        foreach ($chars as $char) {
                        $code = mb_ord($char, 'UTF-8') - 127397;
                        $iso .= chr($code);
                        }
                        return strtolower($iso);
                        }
                        }
                        $currentLocale = session('locale', config('app.locale', 'en'));
                        $currentLanguage = $languages->where('code', $currentLocale)->first();
                        @endphp
                        <div class="current_lang">
                            <a class="lang" href="javascript:void(0)">
                                <i class="flag-icon flag-icon-{{ $currentLanguage ? emojiToISO($currentLanguage->flag) : 'us' }}"></i>
                                <h6 class="lang-txt f-w-700">{{ $currentLanguage ? strtoupper($currentLanguage->code) : 'ENG' }}</h6>
                            </a>
                        </div>

                        <ul class="custom-menu profile-menu language-menu py-0 more_lang      onclick=" changeLanguage(this)"
                            style="max-height:350px; overflow-y:auto;">
                            @foreach($languages as $lang)
                            @php
                            // dd($lang);
                            @endphp
                            <li>
                                <a href="#"
                                    class="lang {{ \Illuminate\Support\Facades\Session::get('locale', 'en') == $lang->code ? 'active' : '' }}"
                                    data-value="{{ $lang->code }}">
                                    <i class="flag-icon flag-icon-{{ emojiToISO($lang->flag) }}"></i> {{ strtoupper($lang->name) }}
                                </a>
                            </li>
                            @endforeach

                            <li class="d-block">
                                <a class="lang"
                                    href="javascript:void(0)"
                                    data-value="af"
                                    onclick="changeLanguage(this)">
                                    <i class="flag-icon flag-icon-za"></i>
                                    <div class="lang-txt">Afrikaans</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="javascript:void(0)" data-value="sq">
                                    <i class="flag-icon flag-icon-al"></i>
                                    <div class="lang-txt">Albanian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="am">
                                    <i class="flag-icon flag-icon-et"></i>
                                    <div class="lang-txt">Amharic</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ar">
                                    <i class="flag-icon flag-icon-sa"></i>
                                    <div class="lang-txt">Arabic</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="hy">
                                    <i class="flag-icon flag-icon-am"></i>
                                    <div class="lang-txt">Armenian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="az">
                                    <i class="flag-icon flag-icon-az"></i>
                                    <div class="lang-txt">Azerbaijani</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="eu">
                                    <i class="flag-icon flag-icon-es"></i>
                                    <div class="lang-txt">Basque</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="be">
                                    <i class="flag-icon flag-icon-by"></i>
                                    <div class="lang-txt">Belarusian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="bn">
                                    <i class="flag-icon flag-icon-bd"></i>
                                    <div class="lang-txt">Bengali</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="bs">
                                    <i class="flag-icon flag-icon-ba"></i>
                                    <div class="lang-txt">Bosnian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="bg">
                                    <i class="flag-icon flag-icon-bg"></i>
                                    <div class="lang-txt">Bulgarian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ca">
                                    <i class="flag-icon flag-icon-es"></i>
                                    <div class="lang-txt">Catalan</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ceb">
                                    <i class="flag-icon flag-icon-ph"></i>
                                    <div class="lang-txt">Cebuano</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ny">
                                    <i class="flag-icon flag-icon-mw"></i>
                                    <div class="lang-txt">Chichewa</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="zh">
                                    <i class="flag-icon flag-icon-cn"></i>
                                    <div class="lang-txt">Chinese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="co">
                                    <i class="flag-icon flag-icon-fr"></i>
                                    <div class="lang-txt">Corsican</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="hr">
                                    <i class="flag-icon flag-icon-hr"></i>
                                    <div class="lang-txt">Croatian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="cs">
                                    <i class="flag-icon flag-icon-cz"></i>
                                    <div class="lang-txt">Czech</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="da">
                                    <i class="flag-icon flag-icon-dk"></i>
                                    <div class="lang-txt">Danish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="nl">
                                    <i class="flag-icon flag-icon-nl"></i>
                                    <div class="lang-txt">Dutch</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="en">
                                    <i class="flag-icon flag-icon-gb"></i>
                                    <div class="lang-txt">English</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="eo">
                                    <i class="flag-icon flag-icon-pl"></i>
                                    <div class="lang-txt">Esperanto</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="et">
                                    <i class="flag-icon flag-icon-ee"></i>
                                    <div class="lang-txt">Estonian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="fi">
                                    <i class="flag-icon flag-icon-fi"></i>
                                    <div class="lang-txt">Finnish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="fr">
                                    <i class="flag-icon flag-icon-fr"></i>
                                    <div class="lang-txt">French</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="gl">
                                    <i class="flag-icon flag-icon-es"></i>
                                    <div class="lang-txt">Galician</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ka">
                                    <i class="flag-icon flag-icon-ge"></i>
                                    <div class="lang-txt">Georgian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="de">
                                    <i class="flag-icon flag-icon-de"></i>
                                    <div class="lang-txt">German</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="el">
                                    <i class="flag-icon flag-icon-gr"></i>
                                    <div class="lang-txt">Greek</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="gu">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Gujarati</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ht">
                                    <i class="flag-icon flag-icon-ht"></i>
                                    <div class="lang-txt">Haitian Creole</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ha">
                                    <i class="flag-icon flag-icon-ng"></i>
                                    <div class="lang-txt">Hausa</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="he">
                                    <i class="flag-icon flag-icon-il"></i>
                                    <div class="lang-txt">Hebrew</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="hi">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Hindi</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="hu">
                                    <i class="flag-icon flag-icon-hu"></i>
                                    <div class="lang-txt">Hungarian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="is">
                                    <i class="flag-icon flag-icon-is"></i>
                                    <div class="lang-txt">Icelandic</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="id">
                                    <i class="flag-icon flag-icon-id"></i>
                                    <div class="lang-txt">Indonesian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ga">
                                    <i class="flag-icon flag-icon-ie"></i>
                                    <div class="lang-txt">Irish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="it">
                                    <i class="flag-icon flag-icon-it"></i>
                                    <div class="lang-txt">Italian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ja">
                                    <i class="flag-icon flag-icon-jp"></i>
                                    <div class="lang-txt">Japanese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="jw">
                                    <i class="flag-icon flag-icon-id"></i>
                                    <div class="lang-txt">Javanese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="kn">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Kannada</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="kk">
                                    <i class="flag-icon flag-icon-kz"></i>
                                    <div class="lang-txt">Kazakh</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="km">
                                    <i class="flag-icon flag-icon-kh"></i>
                                    <div class="lang-txt">Khmer</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ko">
                                    <i class="flag-icon flag-icon-kr"></i>
                                    <div class="lang-txt">Korean</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ku">
                                    <i class="flag-icon flag-icon-iq"></i>
                                    <div class="lang-txt">Kurdish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ky">
                                    <i class="flag-icon flag-icon-kg"></i>
                                    <div class="lang-txt">Kyrgyz</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="lo">
                                    <i class="flag-icon flag-icon-la"></i>
                                    <div class="lang-txt">Lao</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="lv">
                                    <i class="flag-icon flag-icon-lv"></i>
                                    <div class="lang-txt">Latvian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="lt">
                                    <i class="flag-icon flag-icon-lt"></i>
                                    <div class="lang-txt">Lithuanian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mk">
                                    <i class="flag-icon flag-icon-mk"></i>
                                    <div class="lang-txt">Macedonian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mg">
                                    <i class="flag-icon flag-icon-mg"></i>
                                    <div class="lang-txt">Malagasy</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ms">
                                    <i class="flag-icon flag-icon-my"></i>
                                    <div class="lang-txt">Malay</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ml">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Malayalam</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mt">
                                    <i class="flag-icon flag-icon-mt"></i>
                                    <div class="lang-txt">Maltese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mi">
                                    <i class="flag-icon flag-icon-nz"></i>
                                    <div class="lang-txt">Maori</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mr">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Marathi</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="mn">
                                    <i class="flag-icon flag-icon-mn"></i>
                                    <div class="lang-txt">Mongolian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="my">
                                    <i class="flag-icon flag-icon-mm"></i>
                                    <div class="lang-txt">Myanmar (Burmese)</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ne">
                                    <i class="flag-icon flag-icon-np"></i>
                                    <div class="lang-txt">Nepali</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="no">
                                    <i class="flag-icon flag-icon-no"></i>
                                    <div class="lang-txt">Norwegian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="or">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Odia</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ps">
                                    <i class="flag-icon flag-icon-af"></i>
                                    <div class="lang-txt">Pashto</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="fa">
                                    <i class="flag-icon flag-icon-ir"></i>
                                    <div class="lang-txt">Persian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="pl">
                                    <i class="flag-icon flag-icon-pl"></i>
                                    <div class="lang-txt">Polish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="pt">
                                    <i class="flag-icon flag-icon-pt"></i>
                                    <div class="lang-txt">Portuguese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="pa">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Punjabi</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ro">
                                    <i class="flag-icon flag-icon-ro"></i>
                                    <div class="lang-txt">Romanian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ru">
                                    <i class="flag-icon flag-icon-ru"></i>
                                    <div class="lang-txt">Russian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="sr">
                                    <i class="flag-icon flag-icon-rs"></i>
                                    <div class="lang-txt">Serbian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="si">
                                    <i class="flag-icon flag-icon-lk"></i>
                                    <div class="lang-txt">Sinhala</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="sk">
                                    <i class="flag-icon flag-icon-sk"></i>
                                    <div class="lang-txt">Slovak</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="sl">
                                    <i class="flag-icon flag-icon-si"></i>
                                    <div class="lang-txt">Slovenian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="so">
                                    <i class="flag-icon flag-icon-so"></i>
                                    <div class="lang-txt">Somali</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="es">
                                    <i class="flag-icon flag-icon-es"></i>
                                    <div class="lang-txt">Spanish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="sw">
                                    <i class="flag-icon flag-icon-ke"></i>
                                    <div class="lang-txt">Swahili</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="sv">
                                    <i class="flag-icon flag-icon-se"></i>
                                    <div class="lang-txt">Swedish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ta">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Tamil</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="te">
                                    <i class="flag-icon flag-icon-in"></i>
                                    <div class="lang-txt">Telugu</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="th">
                                    <i class="flag-icon flag-icon-th"></i>
                                    <div class="lang-txt">Thai</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="tr">
                                    <i class="flag-icon flag-icon-tr"></i>
                                    <div class="lang-txt">Turkish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="uk">
                                    <i class="flag-icon flag-icon-ua"></i>
                                    <div class="lang-txt">Ukrainian</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="ur">
                                    <i class="flag-icon flag-icon-pk"></i>
                                    <div class="lang-txt">Urdu</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="uz">
                                    <i class="flag-icon flag-icon-uz"></i>
                                    <div class="lang-txt">Uzbek</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="vi">
                                    <i class="flag-icon flag-icon-vn"></i>
                                    <div class="lang-txt">Vietnamese</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="cy">
                                    <i class="flag-icon flag-icon-gb"></i>
                                    <div class="lang-txt">Welsh</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="xh">
                                    <i class="flag-icon flag-icon-za"></i>
                                    <div class="lang-txt">Xhosa</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="yi">
                                    <i class="flag-icon flag-icon-il"></i>
                                    <div class="lang-txt">Yiddish</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="yo">
                                    <i class="flag-icon flag-icon-ng"></i>
                                    <div class="lang-txt">Yoruba</div>
                                </a>
                            </li>

                            <li class="d-block">
                                <a class="lang" href="#" data-value="zu">
                                    <i class="flag-icon flag-icon-za"></i>
                                    <div class="lang-txt">Zulu</div>
                                </a>
                            </li>

                        </ul>
                <li class="d-b
                <li class=" search d-lg-none d-flex"> <a href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Search') }}"></use>
                        </svg></a></li>
                <li> <a class="dark-mode" href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#moondark') }}"></use>
                        </svg></a></li>
                <li class="custom-dropdown"><a href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#cart-icon') }}"></use>
                        </svg></a><span class="badge rounded-pill badge-primary">2</span>
                    <div class="custom-menu cart-dropdown py-0 overflow-hidden">
                        <h3 class="title dropdown-title">Cart</h3>
                        <ul class="pb-0">
                            <li>
                                <div class="d-flex"><img class="img-fluid b-r-5 me-3 img-60" src="{{ asset('admiro/assets/images/dashboard-2/1.png') }}" alt="" />
                                    <div class="flex-grow-1"><span class="f-w-600">Watch multicolor</span>
                                        <div class="qty-box">
                                            <div class="input-group"><span class="input-group-prepend">
                                                    <button class="btn quantity-left-minus" type="button" data-type="minus" data-field="">-</button></span>
                                                <input class="form-control input-number" type="text" name="quantity" value="1" /><span class="input-group-prepend">
                                                    <button class="btn quantity-right-plus" type="button" data-type="plus" data-field="">+</button></span>
                                            </div>
                                        </div>
                                        <h6 class="font-primary">$500</h6>
                                    </div>
                                    <div class="close-circle"><a class="bg-danger" href="#"><i data-feather="x"></i></a></div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex"><img class="img-fluid b-r-5 me-3 img-60" src="{{ asset('admiro/assets/images/dashboard-2/2.png') }}" alt="" />
                                    <div class="flex-grow-1"><span class="f-w-600">Airpods</span>
                                        <div class="qty-box">
                                            <div class="input-group"><span class="input-group-prepend">
                                                    <button class="btn quantity-left-minus" type="button" data-type="minus" data-field="">-</button></span>
                                                <input class="form-control input-number" type="text" name="quantity" value="1" /><span class="input-group-prepend">
                                                    <button class="btn quantity-right-plus" type="button" data-type="plus" data-field="">+</button></span>
                                            </div>
                                        </div>
                                        <h6 class="font-primary">$500.00</h6>
                                    </div>
                                    <div class="close-circle"><a class="bg-danger" href="#"><i data-feather="x"></i></a></div>
                                </div>
                            </li>
                            <li class="total">
                                <h6 class="mb-0">Order Total : <span class="f-w-600">$1000.00</span></h6>
                            </li>
                            <li class="text-center"><a class="d-block mb-3 view-cart f-w-700 text-primary" href="#">Go to your cart</a><a class="btn btn-primary view-checkout text-white" href="#">Checkout</a></li>
                        </ul>
                    </div>
                </li>
                <li class="custom-dropdown"><a href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#notification') }}"></use>
                        </svg></a><span class="badge rounded-pill badge-primary">4</span>
                    <div class="custom-menu notification-dropdown py-0 overflow-hidden">
                        <h3 class="title bg-primary-light dropdown-title">Notification <span class="font-primary">View all</span></h3>
                        <ul class="activity-timeline">
                            <li class="d-flex align-items-start">
                                <div class="activity-line"></div>
                                <div class="activity-dot-primary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-primary">30-04-2024<span>Today</span><span class="circle-dot-primary float-end">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Alice Goodwin</h5>
                                    <p class="mb-0">Fashion should be fun. It shouldn't be labelled intellectual.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-secondary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-secondary">28-06-2024<span>1 hour ago</span><span class="float-end circle-dot-secondary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Herry Venter</h5>
                                    <p>I am convinced that there can be luxury in simplicity.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-primary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-primary">04-08-2024<span>Today</span><span class="float-end circle-dot-primary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Loain Deo</h5>
                                    <p>I feel that things happen for open new opportunities.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-secondary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-secondary">12-11-2024<span>Yesterday</span><span class="float-end circle-dot-secondary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Fenter Jessy</h5>
                                    <p>Sometimes the simplest things are the most profound.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li><a class="full-screen" href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#scanfull') }}"></use>
                        </svg></a></li>
                <li class="custom-dropdown"><a href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#bookmark') }}"></use>
                        </svg></a>
                    <div class="custom-menu bookmark-dropdown py-0 overflow-hidden">
                        <h3 class="title bg-primary-light dropdown-title">Bookmark</h3>
                        <ul>
                            <li>
                                <form class="mb-0">
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Search Bookmark..." /><span class="input-group-text">
                                            <svg class="svg-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Search') }}"></use>
                                            </svg></span>
                                    </div>
                                </form>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 btn-activity-primary"><a href="#">
                                        <svg class="svg-color">
                                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#cube') }}"></use>
                                        </svg></a></div>
                                <div class="d-flex justify-content-between align-items-center w-100"><a href="#">Dashboard</a>
                                    <svg class="svg-color icon-star">
                                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#star') }}"></use>
                                    </svg>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 btn-activity-secondary"><a href="#">
                                        <svg class="svg-color">
                                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#check') }}"></use>
                                        </svg></a></div>
                                <div class="d-flex justify-content-between align-items-center w-100"><a href="#">To-do</a>
                                    <svg class="svg-color icon-star">
                                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#star') }}"></use>
                                    </svg>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 btn-activity-danger"><a href="#">
                                        <svg class="svg-color">
                                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#pie') }}"></use>
                                        </svg></a></div>
                                <div class="d-flex justify-content-between align-items-center w-100"><a href="#">Chart</a>
                                    <svg class="svg-color icon-star">
                                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#star') }}"></use>
                                    </svg>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="cloud-design"><a class="cloud-mode">
                        <svg class="climacon climacon_cloudDrizzle" id="cloudDrizzle" version="1.1" viewBox="15 15 70 70">
                            <g class="climacon_iconWrap climacon_iconWrap-cloudDrizzle">
                                <g class="climacon_wrapperComponent climacon_wrapperComponent-drizzle">
                                    <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-left" d="M42.001,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2.001-0.895-2.001-2v-3.998C40,54.538,40.896,53.644,42.001,53.644z"></path>
                                    <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-middle" d="M49.999,53.644c1.104,0,2,0.896,2,2v4c0,1.104-0.896,2-2,2s-1.998-0.896-1.998-2v-4C48.001,54.54,48.896,53.644,49.999,53.644z"></path>
                                    <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-right" d="M57.999,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2-0.895-2-2v-3.998C55.999,54.538,56.894,53.644,57.999,53.644z"></path>
                                </g>
                                <g class="climacon_wrapperComponent climacon_wrapperComponent-cloud">
                                    <path class="climacon_component climacon_component-stroke climacon_component-stroke_cloud" d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"></path>
                                </g>
                            </g>
                        </svg>
                        <h3>15</h3>
                    </a></li>
                <li class="profile-nav custom-dropdown">
                    <div class="user-wrap">
                        <div class="user-img"><img src="{{ asset('admiro/assets/images/profile.png') }}" alt="user" /></div>
                        <div class="user-content">
                            <h6>{{ auth()->user()->name ?? 'Guest' }}</h6>
                            <p class="mb-0 text-capitalize">{{ auth()->user()->role ?? 'User' }}<i class="fa-solid fa-chevron-down"></i></p>
                        </div>
                    </div>
                    <div class="custom-menu overflow-hidden">
                        <ul class="profile-body">
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                                </svg><a class="ms-2" href="#">Account</a>
                            </li>
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Message') }}"></use>
                                </svg><a class="ms-2" href="#">Inbox</a>
                            </li>
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Document') }}"></use>
                                </svg><a class="ms-2" href="#">Task</a>
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

    document.querySelectorAll(".lang").forEach(function(element) {

        element.addEventListener("click", function(e) {

            e.preventDefault();

            let id = this.getAttribute("data-value");
            if (!id) {
                return;
            }
            console.log(id);

            // Store selected language in localStorage
            localStorage.setItem('selectedLanguage', id);

            fetch(`change-language/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {

                    if (data.status) {

                        Object.keys(data.data).forEach(function(key) {

                            let element = document.getElementById(key);

                            if (element) {
                                element.value = data.data[key] ?? '';
                            }

                        });

                    } else {

                        // Clear all inputs if not found
                        document.querySelectorAll("input, textarea").forEach(el => {
                            el.value = '';
                        });

                        console.warn(data.message);
                    }

                });

        });

    });
</script>
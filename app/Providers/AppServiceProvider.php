<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Apple\AppleExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         App::setLocale(Session::get('locale', 'en'));
        Schema::defaultStringLength(191);

        Event::listen(SocialiteWasCalled::class, [AppleExtendSocialite::class, 'handle']);

        view()->composer('*', function ($view) {
            $language = App::getLocale();
            
            // Fetch available languages that have homepage settings
            $availableLocales = \App\Models\HomepageSetting::distinct('language')->pluck('language')->toArray();
            
            // Get full language models for these locales
            $availableLanguages = \App\Models\Language::whereIn('code', $availableLocales)
                ->orderByRaw("CASE WHEN code = 'en' THEN 1 WHEN code = 'fr' THEN 2 ELSE 3 END ASC")
                ->get()
                ->unique('code'); // Ensure uniqueness if DB has multiple entries for same code
            
            $settings = \App\Models\HomepageSetting::where('language', $language)->pluck('value', 'key');
            
            // Fallback to English if no settings found for current language
            if ($settings->isEmpty() && $language !== 'en') {
                $settings = \App\Models\HomepageSetting::where('language', 'en')->pluck('value', 'key');
            }
            
            $userBalance = 0;
            $currentUser = auth()->user();
            if ($currentUser) {
                // Calculate balance from practitioner shares and referrer shares
                $earned = \App\Models\Transaction::where('practitioner_id', $currentUser->id)->sum('practitioner_share');
                $referralEarned = \App\Models\Transaction::where('referrer_id', $currentUser->id)->sum('referrer_share');
                $userBalance = $earned + $referralEarned;
            }
            
            $view->with([
                'site_settings' => $settings,
                'available_languages' => $availableLanguages,
                'current_locale' => $language,
                'user_balance' => $userBalance
            ]);
        });
    }
}

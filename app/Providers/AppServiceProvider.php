<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        \Log::info('AppServiceProvider boot started');
        Relation::morphMap([
            'practitioner' => \App\Models\Practitioner::class,
            'doctor' => \App\Models\Doctor::class,
            'mindfulness_practitioner' => \App\Models\MindfulnessPractitioner::class,
            'yoga_therapist' => \App\Models\YogaTherapist::class,
            'translator' => \App\Models\Translator::class,
        ]);

        Schema::defaultStringLength(191);

        Event::listen(SocialiteWasCalled::class, [AppleExtendSocialite::class, 'handle']);

        // Set locale from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        view()->composer('*', function ($view) {
            $language = App::getLocale();
            $settings = collect();
            $availableLanguages = collect();
            $userBalance = 0;
            $activePromoCodes = collect();
            $globalHealthConditions = collect();

            try {
                // Fetch active health conditions for footer/global use
                $globalHealthConditions = \App\Models\HealthCondition::where('status', true)->take(6)->pluck('name');

                // Fetch available languages that have homepage settings
                $availableLocales = \App\Models\HomepageSetting::distinct('language')->pluck('language')->toArray();
                
                // Get full language models for these locales
                $availableLanguages = \App\Models\Language::whereIn('code', $availableLocales)
                    ->where('status', 'active')
                    ->orderByRaw("CASE WHEN code = 'en' THEN 1 WHEN code = 'fr' THEN 2 ELSE 3 END ASC")
                    ->get()
                    ->unique('code'); // Ensure uniqueness if DB has multiple entries for same code
                
                $settings = \App\Models\HomepageSetting::where('language', $language)->pluck('value', 'key');
                
                // Fallback to English if no settings found for current language
                if ($settings->isEmpty() && $language !== 'en') {
                    $settings = \App\Models\HomepageSetting::where('language', 'en')->pluck('value', 'key');
                }
                
                $currentUser = auth()->user();
                if ($currentUser) {
                    // Calculate balance from practitioner shares and referrer shares
                    $earned = \App\Models\Transaction::where('practitioner_id', $currentUser->id)->sum('practitioner_share');
                    $referralEarned = \App\Models\Transaction::where('referrer_id', $currentUser->id)->sum('referrer_share');
                    $userBalance = $earned + $referralEarned;

                    // Fetch active promo codes (Global + User Specific)
                    $globalCodes = \App\Models\PromoCode::where('status', true)
                        ->where(function($q) {
                            $q->where('expiry_date', '>=', now()->toDateString())
                              ->orWhereNull('expiry_date');
                        })
                        ->get();
                    
                    $userLinkedCodes = \App\Models\UserPromoCode::where('user_id', $currentUser->id)->pluck('promo_code')->toArray();
                    $specificCodes = \App\Models\PromoCode::whereIn('code', $userLinkedCodes)
                        ->where('status', true)
                        ->where(function($q) {
                            $q->where('expiry_date', '>=', now()->toDateString())
                              ->orWhereNull('expiry_date');
                        })
                        ->get();

                    $activePromoCodes = $globalCodes->concat($specificCodes)->unique('code');
                }
            } catch (\Exception $e) {
                \Log::error("Database error in AppServiceProvider: " . $e->getMessage());
            }
            
            $view->with([
                'site_settings' => $settings,
                'available_languages' => $availableLanguages,
                'current_locale' => $language,
                'user_balance' => $userBalance,
                'active_promo_codes' => $activePromoCodes,
                'global_health_conditions' => $globalHealthConditions
            ]);
        });
    }
}

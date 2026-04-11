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
        Relation::morphMap([
            'App\Models\Practitioner' => \App\Models\Practitioner::class,
            'AppModelsPractitioner' => \App\Models\Practitioner::class,
            'practitioner' => \App\Models\Practitioner::class,
            'App\Models\Doctor' => \App\Models\Doctor::class,
            'AppModelsDoctor' => \App\Models\Doctor::class,
            'doctor' => \App\Models\Doctor::class,
            'App\Models\MindfulnessPractitioner' => \App\Models\MindfulnessPractitioner::class,
            'AppModelsMindfulnessPractitioner' => \App\Models\MindfulnessPractitioner::class,
            'mindfulness_practitioner' => \App\Models\MindfulnessPractitioner::class,
            'App\Models\YogaTherapist' => \App\Models\YogaTherapist::class,
            'AppModelsYogaTherapist' => \App\Models\YogaTherapist::class,
            'yoga_therapist' => \App\Models\YogaTherapist::class,
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

            try {
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

                    // Fetch active promo codes
                    $activePromoCodes = \App\Models\PromoCode::where('status', true)
                        ->where(function($q) {
                            $q->where('expiry_date', '>=', now()->toDateString())
                              ->orWhereNull('expiry_date');
                        })
                        ->whereIn('usage_type', ['booking', 'both'])
                        ->get();
                }
            } catch (\Exception $e) {
                \Log::error("Database error in AppServiceProvider: " . $e->getMessage());
            }
            
            $view->with([
                'site_settings' => $settings,
                'available_languages' => $availableLanguages,
                'current_locale' => $language,
                'user_balance' => $userBalance,
                'active_promo_codes' => $activePromoCodes
            ]);
        });
    }
}

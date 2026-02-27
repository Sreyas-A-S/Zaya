<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

        view()->composer('*', function ($view) {
            $language = App::getLocale();
            $settings = \App\Models\HomepageSetting::where('language', $language)->pluck('value', 'key');
            
            // Fallback to English if no settings found for current language
            if ($settings->isEmpty() && $language !== 'en') {
                $settings = \App\Models\HomepageSetting::where('language', 'en')->pluck('value', 'key');
            }
            
            $view->with('site_settings', $settings);
        });
    }
}

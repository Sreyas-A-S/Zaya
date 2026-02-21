<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
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
         App::setLocale(session('locale', 'en'));
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $view->with('site_settings', \App\Models\HomepageSetting::pluck('value', 'key'));
        });
    }
}

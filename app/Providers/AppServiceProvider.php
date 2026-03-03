<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SurveyResponse;
use App\Observers\SurveyResponseObserver;
use Illuminate\Support\Facades\URL;

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
        SurveyResponse::observe(SurveyResponseObserver::class);


        // if (app()->environment('production')) {
        //     URL::forceScheme('https');
        // }
    }
}

<?php

namespace App\Providers;

use App\Models\SchoolInfo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $schoolInfo = SchoolInfo::first();

        // Share with all views
        View::share('schoolInfo', $schoolInfo);
    }
}

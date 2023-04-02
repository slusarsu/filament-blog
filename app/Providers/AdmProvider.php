<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Spatie\Valuestore\Valuestore;
use Storage;

class AdmProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $siteSetting = Valuestore::make(storage_path('app/site_settings.json'));

        View::share('siteSetting', $siteSetting);
    }
}

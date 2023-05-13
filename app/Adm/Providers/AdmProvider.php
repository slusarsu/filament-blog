<?php

namespace App\Adm\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Valuestore\Valuestore;

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

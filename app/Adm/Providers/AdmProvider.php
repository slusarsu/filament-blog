<?php

namespace App\Adm\Providers;

use App\Adm\Services\TemplateService;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Saade\FilamentLaravelLog\Pages\ViewLog;

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
        $this->loadViewsFrom(__DIR__.'/../Views', 'adm');
        $admSite = siteSettingsAll();
        $admTpl = templateSettings();
        resolve(TemplateService::class)->getTemplateFunctions($admSite['template']);
        View::share('admSite', $admSite);
        View::share('admTpl', $admTpl);

//        Filament::registerRenderHook(
//            'global-search.end',
//            fn (): string => Blade::render("test")
//        );
    }
}

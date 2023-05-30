<?php

namespace App\Adm\Providers;

use App\Adm\Services\TemplateService;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        $admSite = siteSetting()->all();
        $admTpl = resolve(TemplateService::class)->getTemplateSettings($admSite['template']);
        resolve(TemplateService::class)->getTemplateFunctions($admSite['template']);
        View::share('admSite', $admSite);
        View::share('admTpl', $admTpl);

//        Filament::registerRenderHook(
//            'global-search.end',
//            fn (): string => Blade::render("test")
//        );
    }
}

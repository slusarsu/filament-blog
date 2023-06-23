<?php

use App\Adm\Services\PageService;
use App\Adm\Services\PostService;
use App\Adm\Services\TemplateService;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Spatie\Valuestore\Valuestore;

function siteSetting() {
    return Valuestore::make(app_path('Adm/Settings/site_settings.json'));
}

function getFullTemplatePath(): string
{
    return 'resources/views/' . siteSetting()->get('template') .'/';
}

function admView(string $bladeName, array $params = [])
{
    return resolve(TemplateService::class)->templateView($bladeName, $params);
}

function admAsset(string $filePath): string
{
    return asset(resolve(TemplateService::class)->templateRecoursePath().'/'. $filePath);
}

function admMenuBySlug($slug) {
    $menu = Menu::query()->where('slug', $slug)->with('menu_items')->lang()->first();
    return !empty($menu->menu_items) ? MenuItem::tree($menu->id) : $menu->menu_items ?? [];
}

function admMenuByPosition($position) {
    $menu = Menu::query()->where('position', $position)->with('menu_items')->lang()->first();
    return !empty($menu->menu_items) ? MenuItem::tree($menu->id) : $menu->menu_items ?? [];
}

function admMenuPositions() {
    return config('adm.menu_positions');
}

function admDefaultLanguage()
{
    return siteSetting()->get('default_language');
}

function admLocales(): array
{
    return config('adm.locales');
}
function admLanguages(): array
{
    $languages = [];

    foreach (admLocales() as $key => $locale) {
        $languages[$key] = $locale['native'];
    }

    return $languages;
}

function admLocale(): string
{
    return app()->getLocale();
}

function admRouteName(): string
{
    return request()->route()->getName() ?? '';
}

function admJsonRouteParameters(): string
{
    return json_encode(request()->route()->parameters()) ?? '';
}

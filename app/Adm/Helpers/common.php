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

function admMenu($slug) {
    $menu = Menu::query()->where('slug', $slug)->with('menu_items')->first();
    return !empty($menu->menu_items) ? MenuItem::tree($menu->id) : $menu->menu_items ?? [];
}

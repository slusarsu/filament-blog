<?php

use App\Adm\Services\PageService;
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
    return view('templates.'.siteSetting()->get('template') .'.'.$bladeName, $params);
}

function admAsset(string $filePath): string
{
    return asset('templates/'.siteSetting()->get('template').'/'. $filePath);
}

function admMenu($slug) {
    $menu = Menu::query()->where('slug', $slug)->with('menu_items')->first();
    return count($menu->menu_items) ? MenuItem::tree($menu->id) : $menu->menu_items ?? [];
}

function admPageBySlug($slug) {
    return resolve(PageService::class)->getPageBySlug($slug);
}

function admPageById($id) {
    return resolve(PageService::class)->getPageById($id);
}


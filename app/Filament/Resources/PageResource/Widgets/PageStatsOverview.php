<?php

namespace App\Filament\Resources\PageResource\Widgets;

use App\Models\Page;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PageStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $pages = Page::query()->get();
        return [
            Card::make(trans('adm/dashboard.pages'), $pages->count()),
            Card::make(trans('adm/dashboard.active'), $pages->where('is_enabled', true)->count()),
            Card::make(trans('adm/dashboard.inactive'), $pages->where('is_enabled', false)->count()),
            Card::make(trans('adm/dashboard.removed'), Page::onlyTrashed()->count()),
        ];
    }
}

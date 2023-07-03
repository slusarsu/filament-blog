<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PostStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $pages = Post::query()->get();
        return [
            Card::make(trans('adm/dashboard.posts'), $pages->count()),
            Card::make(trans('adm/dashboard.active'), $pages->where('is_enabled', true)->count()),
            Card::make(trans('adm/dashboard.inactive'), $pages->where('is_enabled', false)->count()),
            Card::make(trans('adm/dashboard.removed'), Post::onlyTrashed()->count()),
        ];
    }
}

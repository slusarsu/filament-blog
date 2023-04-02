<?php

namespace App\Adm\Services;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageService
{
    private Builder $model;

    public function __construct()
    {
        $this->model = Page::query();
    }

    public function getPageBySlug(string $slug): object
    {
        $page = $this->model
            ->where('slug', $slug)
            ->where('is_enabled', true)
            ->where('created_at', '<=',Carbon::now())
            ->with('media')
            ->first();

        if(!$page) {
            throw new NotFoundHttpException();
        } else {
            $page->thumb = $page->getMedia('thumbs')->first();
            $page->images = $page->getMedia('images')->all();
        }

        return $page;
    }
}

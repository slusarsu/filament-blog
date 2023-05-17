<?php

namespace App\Adm\Services;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
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
        $page = $this->model->where('slug', $slug)->active()->with('media')->first();

        if($page) {
            return $this->getMediaForRecord($page);
        }

        return abort(404);
    }

    public function getPageById(string $id): object
    {
        $page = $this->model->where('id', $id)->active()->with('media')->first();

        if($page) {
            return $this->getMediaForRecord($page);
        }

        return abort(404);
    }

    public function getMediaForRecord(Page $record): Page
    {
        if(!$record) {
            throw new NotFoundHttpException();
        } else {
            $record->thumb = $record->getMedia('thumbs')->first();
            $record->images = $record->getMedia('images')->all();
        }

        return $record;
    }
}

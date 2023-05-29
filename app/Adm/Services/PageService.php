<?php

namespace App\Adm\Services;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageService
{
    private Builder $model;

    public function __construct()
    {
        $this->model = Page::query();
    }

    public function oneBySlug(string $slug): object
    {
        $page = $this->model->where('slug', $slug)->active()->first();

        return $page ?? abort(404);
    }

    public function oneById(string $id): object
    {
        $page = $this->model->where('id', $id)->active()->first();

        return $page ?? abort(404);
    }

    public function getSeoData()
    {

    }
}

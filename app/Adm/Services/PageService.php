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

    public function oneBySlug(string $slug): object|null
    {
        return $this->model->where('slug', $slug)->active()->first();
    }

    public function oneById(string $id): object|null
    {
        return $this->model->where('id', $id)->active()->first();
    }

    public function getSeoData()
    {

    }
}

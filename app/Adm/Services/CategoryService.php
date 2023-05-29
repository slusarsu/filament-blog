<?php

namespace App\Adm\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryService
{
    private Builder $model;

    public function __construct()
    {
        $this->model = Category::query();
    }

    public function oneBySlug(string $slug): object
    {
        $category = $this->model->where('slug', $slug)->withCount('posts')->first();

        return $category ?? abort(404);
    }

    public function oneById(string $id): object
    {
        $category = $this->model->where('id', $id)->withCount('posts')->first();

        return $category ?? abort(404);
    }
}

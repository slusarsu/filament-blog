<?php

namespace App\Adm\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class TagService
{
    private Builder $model;

    public function __construct()
    {
        $this->model = Tag::query();
    }

    public function oneBySlug(string $slug): object
    {
        $tag = $this->model->where('slug', $slug)->withCount('posts')->first();

        return $tag ?? abort(404);
    }

    public function oneById(string $id): object
    {
        $tag = $this->model->where('id', $id)->withCount('posts')->first();

        return $tag ?? abort(404);
    }
}

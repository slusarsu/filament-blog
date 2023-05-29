<?php

namespace App\Adm\Services;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostService
{
    private Post $model;

    /**
     * @var array|string[]
     */
    private array $allPostParams;

    public function __construct()
    {
        $this->model = new Post();
        $this->allPostParams = [
            'orderBy' => 'desc',
            'orderByParam' => 'created_at',
//            'orderByParam' => 'id',
            'limit' => 0,
            'toArray' => false,
        ];
    }

    public function getAll(int $paginateCount = 10, array $params = [])
    {
        $param = [...$this->allPostParams, ...$params];

        $posts =  $this->model::query()->active();

        return $this->postsFilter($posts, $paginateCount, $param);
    }

    public function postsFilter($posts, int $paginateCount, array $param)
    {
        $posts = $posts->with('tags:id,title,slug')->with('categories:id,title,slug');

        $posts = $posts->orderBy($param['orderByParam'],$param['orderBy']);

        if($param['limit']) {
            $posts = $posts->limit($param['limit']);
        }

        if($paginateCount){
            $posts = $posts->paginate($paginateCount);
        } else {
            $posts = $posts->get();
        }

        if($param['toArray']) {
            $posts = $posts->toArray();
        }

        return $posts;
    }

    public function oneBySlug($slug)
    {
        return $this->model->where('slug', $slug)->active()->first();
    }

    public function oneById($id)
    {
        return $this->model->where('id', $id)->active()->first();
    }

    public function allByTagSlug(string $slug, int $paginateCount = 20)
    {
        return $this->model
            ->whereHas('tags', function($query) use ($slug){
                $query->where('slug', $slug);
            })
            ->with('tags:id,title,slug')
            ->with('categories:id,title,slug')
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate($paginateCount);
    }

    public function allByCategorySlug(string $slug, int $paginateCount = 20)
    {
        return $this->model
            ->whereHas('categories', function($query) use ($slug){
                $query->where('slug', $slug);
            })
            ->with('tags:id,title,slug')
            ->with('categories:id,title,slug')
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate($paginateCount);
    }
}

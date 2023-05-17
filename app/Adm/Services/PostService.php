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
    private Builder $model;
    /**
     * @var array|string[]
     */
    private array $allPostParams;

    public function __construct()
    {
        $this->model = Post::query();
        $this->allPostParams = [
            'orderBy' => 'desc',
            'orderByParam' => 'created_at',
            'limit' => 0,
            'toArray' => false,
        ];
    }

    public function getAll(int $paginateCount = 0, array $params = [])
    {
        $param = [...$this->allPostParams, ...$params];

        $posts =  $this->model->active()->with('media');

        return $this->postsFilter($posts, $paginateCount, $param);
    }

    public function postsFilter($posts, int $paginateCount, array $param)
    {
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
        $post =  $this->model->where('slug', $slug)->active()->with('media')->first();

        if($post) {
            return $this->getMediaForRecord($post);
        }

        return $post;
    }

    public function oneById($id)
    {
        $post =  $this->model->where('id', $id)->active()->with('media')->first();

        if($post) {
            return $this->getMediaForRecord($post);
        }

        return $post;
    }

    public function getMediaForRecord(Post $record): Post
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

<?php

namespace App\Adm\Controllers;

use App\Adm\Services\PostService;
use App\Adm\Traits\AdmViewTrait;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use AdmViewTrait;
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function post(Request $request, $slug)
    {
        $post = $this->postService->oneBySlug($slug);
        $thumb = $post->thumb();
        $images = $post->images();

        return admView('posts/post', compact('post', 'images', 'thumb'));
    }
}

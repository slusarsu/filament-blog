<?php

namespace App\Adm\Controllers;

use App\Adm\Services\PageService;
use App\Adm\Services\PostService;
use App\Adm\Services\TagService;
use Illuminate\Http\Request;


class TagController extends Controller
{
    private TagService $tagService;
    private PostService $postService;

    public function __construct(TagService $tagService, PostService $postService)
    {
        $this->tagService = $tagService;
        $this->postService = $postService;
    }

    public function tag(Request $request, $slug)
    {
        $tag = $this->tagService->oneBySlug($slug);
        $posts = $this->postService->allByTagSlug($slug, 6);

        return admView('taxonomy/tag', compact('tag', 'posts'));
    }
}

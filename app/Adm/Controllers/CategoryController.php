<?php

namespace App\Adm\Controllers;

use App\Adm\Services\CategoryService;
use App\Adm\Services\PageService;
use App\Adm\Services\PostService;
use App\Adm\Services\TagService;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    private CategoryService $categoryService;
    private PostService $postService;

    public function __construct(CategoryService $categoryService, PostService $postService)
    {
        $this->categoryService = $categoryService;
        $this->postService = $postService;
    }

    public function category(Request $request, $lang, $slug)
    {
        $category = $this->categoryService->oneBySlug($slug);
        $posts = $this->postService->allByTagSlug($slug, 6);

        return admView('taxonomy/category', compact('category', 'posts'));
    }
}

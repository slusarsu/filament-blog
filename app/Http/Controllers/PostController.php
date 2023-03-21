<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function post(Request $request, $slug)
    {
        $page = Post::query()
            ->where('slug', $slug)
            ->where('is_enabled', true)
            ->with('media')
            ->first();

        if($page) {
            $page->thumb = $page->getMedia('thumbs')->first();
            $page->images = $page->getMedia('images')->all();
        }

        return view('page', compact('page'));
    }
}

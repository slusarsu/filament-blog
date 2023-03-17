<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function page(Request $request, $slug)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_enabled', true)
            ->with('media')
            ->first();

        $thumb = $page->getMedia('thumbs')->first();
        $images = $page->getMedia('images')->all();

        return view('page', compact('page', 'thumb', 'images'));
    }
}

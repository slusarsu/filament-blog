<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function page(Request $request, $slug)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_enabled', true)
            ->where('created_at', '<=',Carbon::now())
            ->with('media')
            ->first();

        if(!$page) {
            throw new NotFoundHttpException();
        }

        if($page) {
            $page->thumb = $page->getMedia('thumbs')->first();
            $page->images = $page->getMedia('images')->all();
        }

        return view('page', compact('page'));
    }
}

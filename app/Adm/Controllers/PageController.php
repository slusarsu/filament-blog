<?php

namespace App\Adm\Controllers;

use App\Adm\Services\PageService;
use Illuminate\Http\Request;


class PageController extends Controller
{
    private PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function page(Request $request, $lang, $slug)
    {
        $page = $this->pageService->oneBySlug($slug);

        if(!$page) {
            return redirect()->route('home', admLocale());
        }

        $cf = $page->customFields();
        $thumb = $page->thumb();
        $images = $page->images();
        $template = !empty($page->template) ? $page->template : 'page';

        return admView('pages/'.$template, compact('page', 'cf', 'images', 'thumb'));
    }
}

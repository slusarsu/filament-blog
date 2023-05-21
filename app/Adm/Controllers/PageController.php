<?php

namespace App\Adm\Controllers;

use App\Adm\Services\PageService;
use App\Adm\Traits\AdmViewTrait;
use Illuminate\Http\Request;


class PageController extends Controller
{
    use AdmViewTrait;

    private PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function page(Request $request, $slug)
    {
        $page = $this->pageService->getPageBySlug($slug);
        $template = !empty($page->template) ? $page->template : 'page';

        return admView('pages/'.$template, compact('page'));
    }
}

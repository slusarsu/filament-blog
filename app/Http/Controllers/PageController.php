<?php

namespace App\Http\Controllers;

use App\Adm\Services\PageService;
use App\Adm\Traits\AdmViewTrait;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        return admView('pages/page', compact('page'));
    }
}

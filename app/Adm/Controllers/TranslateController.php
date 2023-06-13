<?php

namespace App\Adm\Controllers;

use App;
use App\Adm\Services\PageService;
use App\Adm\Services\PostService;
use App\Adm\Services\TagService;
use App\Adm\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class TranslateController extends Controller
{
    private TranslationService $translateService;

    public function __construct(TranslationService $translateService)
    {
        $this->translateService = $translateService;
    }

    public function setLocale(Request $request, $lang)
    {
        $thisUrl = url()->previous();
        $currentLocale = session()->get('locale');
        $homeUrl = route('home', $currentLocale);
        $newUrl = '';

        if ($currentLocale !== $lang) {
            $newUrl = Str::replace("/{$currentLocale}/", "/{$lang}/", $thisUrl);
        }

        if($thisUrl == $newUrl) {
            $newUrl = Str::replace("/{$currentLocale}", "/{$lang}", $thisUrl);
        }

        App::setLocale($lang);
        session()->put('locale', $request->lang);

        return redirect($newUrl);
    }
}

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

    public function localeSwitcher(Request $request): \Illuminate\Http\RedirectResponse
    {
        $locale = $request->input('locale');
        $routeName = $request->input('route_name');
        $routeParameters = json_decode($request->input('route_parameters'));
        $routeModel = config('adm.route_model');
        $model = $routeModel[$routeName] ?? '';

        if(empty($model)) {
            return redirect()->route('home', $locale);
        }

        $record = $model::query()->where('slug', $routeParameters->slug)->first();

        if(empty($record->translations())) {
            return redirect()->route('home', $locale);
        }

        $translation = $record->translations()->where('lang', $locale)->first();

        if(!$translation) {
            return redirect()->route('home', $locale);
        }

        $newRecord = $model::query()->select('slug')->where('id', $translation->model_id)->first();

        if(!$newRecord) {
            return redirect()->route('home', $locale);
        }

        return redirect()->route($routeName, ['lang' => $locale, 'slug'=>$newRecord->slug]);
    }
}

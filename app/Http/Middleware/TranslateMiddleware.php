<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(empty($request->lang)) {
            App::setLocale(admDefaultLanguage());
        }

        if(!empty($request->lang)) {
            App::setLocale($request->lang);
            session()->put('locale', $request->lang);
        }

        return $next($request);
    }
}

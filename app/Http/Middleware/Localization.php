<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = ($request->lang ?? Cookie::get('locale', explode("_", $request->getPreferredLanguage())[0]));

        if ($request->wantsJson() && $request->hasHeader('X-Localization') && array_key_exists($request->header('X-Localization'), LaravelLocalization::getSupportedLocales())) {
            $locale = $request->header('X-Localization');
        }

        if (!array_key_exists($locale, LaravelLocalization::getSupportedLocales())) {
            $locale = config('app.locale');
        }

        Cookie::queue('locale', $locale, 2628000);
        App::setLocale($locale);

        return $next($request);
    }
}

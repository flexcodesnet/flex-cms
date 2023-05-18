<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

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
        $path = explode("/", $request->getPathInfo());
        if (isset($path[1]) && in_array($path[1], config('app.locales')))
            $pathLocale = $path[1];

        $locale = ($request->lang ?? Cookie::get('locale', $pathLocale ?? explode("_", $request->getPreferredLanguage())[0]));

        if ($request->wantsJson() && $request->hasHeader('X-Localization') && in_array($request->header('X-Localization'), config('app.locales'))) {
            $locale = $request->header('X-Localization');
        }

        if (!in_array($locale, config('app.locales'))) {
            $locale = config('app.locale');
        }

        Cookie::queue('locale', $locale, 2628000);
        App::setLocale($locale);

        return $next($request);
    }
}

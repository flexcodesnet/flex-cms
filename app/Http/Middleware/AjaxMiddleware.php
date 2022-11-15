<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AjaxMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax())
            return $next($request);

        $response = (object)[];
        $response->status = 'error';
        $response->message = 'Bad Request';
        return response()->json((array)$response, 400);
    }
}

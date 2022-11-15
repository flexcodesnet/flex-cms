<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolePermission
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
        if (is_null($request->route()->getName()))
            abort(404);

        if (role_permission_check($request))
            return $next($request);

        abort(401);
    }
}

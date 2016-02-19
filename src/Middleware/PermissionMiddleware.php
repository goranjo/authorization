<?php

namespace Stevebauman\Authorization\Middleware;

use Closure;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @param array   $permissions
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissions)
    {
        $permissions = collect($permissions);

        if (! $request->user()->hasPermission($permissions)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}

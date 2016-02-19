<?php

namespace Stevebauman\Authorization\Middleware;

use Closure;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @param array   $roles
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $roles = collect($roles);

        if (! $request->user()->hasRole($roles)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}

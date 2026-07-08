<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Not logged in, or wrong role → block with 403 Forbidden
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
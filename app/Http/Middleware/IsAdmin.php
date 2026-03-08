<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->email !== 'admin@example.com') {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}

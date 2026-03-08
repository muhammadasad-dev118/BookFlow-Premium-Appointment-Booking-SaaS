<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !session('tenant_id')) {
            $tenant = $user->tenants()->first();
            if ($tenant) {
                session(['tenant_id' => $tenant->id]);
            }
        }

        return $next($request);
    }
}

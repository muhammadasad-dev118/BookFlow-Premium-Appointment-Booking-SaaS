<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = null;

        if (class_exists('\Filament\Facades\Filament') && \Filament\Facades\Filament::hasTenancy() && \Filament\Facades\Filament::getTenant()) {
            $tenantId = \Filament\Facades\Filament::getTenant()->id;
        } elseif (session()->has('tenant_id')) {
            $tenantId = session()->get('tenant_id');
        }

        if ($tenantId) {
            setPermissionsTeamId($tenantId);
        }

        return $next($request);
    }
}

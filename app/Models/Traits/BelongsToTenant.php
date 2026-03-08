<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * The "booted" method of the trait.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);
        
        static::creating(function ($model) {
            $tenantId = null;
            if (class_exists('\Filament\Facades\Filament') && \Filament\Facades\Filament::hasTenancy() && \Filament\Facades\Filament::getTenant()) {
                $tenantId = \Filament\Facades\Filament::getTenant()->id;
            } elseif (session()->has('tenant_id')) {
                $tenantId = session()->get('tenant_id');
            }

            if (!$model->tenant_id && $tenantId) {
                $model->tenant_id = $tenantId;
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

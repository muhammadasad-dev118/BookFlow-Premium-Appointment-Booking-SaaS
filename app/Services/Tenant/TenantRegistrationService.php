<?php

namespace App\Services\Tenant;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TenantRegistrationService
{
    /**
     * Registers a new user and creates their SaaS workspace (Tenant).
     */
    public function registerTenant(array $userData, array $tenantData): Tenant
    {
        return DB::transaction(function () use ($userData, $tenantData) {
            
            // 1. Create the User
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            // 2. Create the Tenant (Workspace)
            $tenant = Tenant::create([
                'name' => $tenantData['name'],
                'slug' => Str::slug($tenantData['name']) . '-' . Str::random(4),
            ]);

            // 3. Attach User to Tenant via pivot
            $tenant->users()->attach($user->id, ['role' => 'Owner']);

            // 4. Set the active tenant context for Spatie permissions
            setPermissionsTeamId($tenant->id);

            // 5. Seed default Roles for THIS specific tenant
            $ownerRole = Role::firstOrCreate(['name' => 'Owner', 'tenant_id' => $tenant->id]);
            Role::firstOrCreate(['name' => 'Manager', 'tenant_id' => $tenant->id]);
            Role::firstOrCreate(['name' => 'Staff', 'tenant_id' => $tenant->id]);

            // 6. Assign the Owner role to the user
            $user->assignRole($ownerRole);

            return $tenant;
        });
    }
}

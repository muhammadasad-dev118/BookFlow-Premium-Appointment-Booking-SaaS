<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Service;
use App\Models\Staff;
use App\Models\BusinessHour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create Roles if they don't exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'owner']);

        // 0.1 Create a Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password')
            ]
        );
        // We skip $admin->assignRole('admin') because global roles are complex with teams enabled.
        // The User model already handles admin access via email check.

        // 1. Create a Tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'test-business'],
            ['name' => 'Test Business']
        );

        // 2. Create a User (Tenant Owner)
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Test Owner',
                'password' => Hash::make('password')
            ]
        );
        
        // Use Spatie's method to set the current tenant for role assignment
        setPermissionsTeamId($tenant->id);
        $owner->assignRole('owner');

        // 3. Attach User to Tenant
        $owner->tenants()->syncWithoutDetaching([$tenant->id => ['role' => 'owner']]);

        // 4. Create a Service
        $service = Service::firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Initial Consultation'],
            [
                'description' => 'A 30-minute test consultation.',
                'duration_minutes' => 30,
                'price' => 50.00,
                'is_active' => true,
            ]
        );

        // 5. Create a Staff Member
        $staff = Staff::firstOrCreate(
            ['tenant_id' => $tenant->id, 'email' => $owner->email],
            [
                'user_id' => $owner->id,
                'name' => 'Test Professional',
            ]
        );

        // 6. Link Staff to Service
        $staff->services()->syncWithoutDetaching([$service->id]);

        // 7. Create Business Hours for the Staff (1=Monday to 5=Friday)
        for ($day = 1; $day <= 5; $day++) {
            BusinessHour::firstOrCreate(
                ['tenant_id' => $tenant->id, 'staff_id' => $staff->id, 'day_of_week' => $day],
                [
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ]
            );
        }
    }
}

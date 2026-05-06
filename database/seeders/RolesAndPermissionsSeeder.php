<?php

namespace Database\Seeders;

use App\Support\AdminPermissions;
use App\Support\AdminRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (AdminPermissions::all() as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $superAdminRole = Role::query()->firstOrCreate([
            'name' => AdminRoles::SUPER_ADMIN,
            'guard_name' => 'web',
        ]);
        $superAdminRole->syncPermissions(AdminPermissions::all());

        $adminRole = Role::query()->firstOrCreate([
            'name' => AdminRoles::ADMIN,
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions(AdminPermissions::adminDefault());
    }
}

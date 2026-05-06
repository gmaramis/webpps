<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => config('admin.seed_email')],
            [
                'name' => config('admin.seed_name'),
                'password' => config('admin.seed_password'),
            ]
        );

        $roleName = (string) config('admin.seed_role', 'admin');
        $role = Role::query()->firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->syncRoles([$role->name]);
    }
}

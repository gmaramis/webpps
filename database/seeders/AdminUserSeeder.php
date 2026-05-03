<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => config('admin.seed_email')],
            [
                'name' => config('admin.seed_name'),
                'password' => config('admin.seed_password'),
            ]
        );
    }
}

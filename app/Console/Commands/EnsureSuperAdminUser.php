<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\AdminRoles;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EnsureSuperAdminUser extends Command
{
    protected $signature = 'pps:ensure-super-admin
                            {email : Alamat email pengguna}
                            {--name= : Nama tampilan}
                            {--password= : Kata sandi (disarankan untuk skrip non-interaktif)}';

    protected $description = 'Membuat atau memperbarui pengguna dan menetapkan role super-admin serta membersihkan cache permission Spatie';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $name = (string) ($this->option('name') ?: 'Super Administrator');
        $password = $this->option('password');

        if ($password === null || $password === '') {
            if (! $this->input->isInteractive()) {
                $this->error('Untuk mode non-interaktif, sertakan opsi --password="..."');

                return self::FAILURE;
            }

            $password = (string) $this->secret('Kata sandi baru');
        }

        if (strlen($password) < 8) {
            $this->error('Kata sandi minimal 8 karakter.');

            return self::FAILURE;
        }

        Role::query()->firstOrCreate(
            ['name' => AdminRoles::SUPER_ADMIN, 'guard_name' => 'web'],
        );
        Role::query()->firstOrCreate(
            ['name' => AdminRoles::ADMIN, 'guard_name' => 'web'],
        );

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name],
        );

        $user->password = $password;
        $user->save();
        $user->syncRoles([AdminRoles::SUPER_ADMIN]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->components->info('Selesai.');
        $this->line("  Email: {$email}");
        $this->line('  Role: '.AdminRoles::label(AdminRoles::SUPER_ADMIN).' ('.AdminRoles::SUPER_ADMIN.')');
        $this->comment('  Jika masih bermasalah sesudah ini, jalankan: php artisan optimize:clear');

        return self::SUCCESS;
    }
}

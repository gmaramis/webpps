<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun awal (seeder)
    |--------------------------------------------------------------------------
    | Set di .env untuk produksi. Seeder memakai updateOrCreate berdasarkan email.
    */
    'seed_email' => env('ADMIN_EMAIL', 'admin@pps.unima.ac.id'),

    'seed_password' => env('ADMIN_PASSWORD', 'password'),

    'seed_name' => env('ADMIN_NAME', 'Administrator'),

    'seed_role' => env('ADMIN_ROLE', 'admin'),

];

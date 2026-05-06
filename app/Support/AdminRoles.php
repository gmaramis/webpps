<?php

namespace App\Support;

final class AdminRoles
{
    public const SUPER_ADMIN = 'super-admin';

    public const ADMIN = 'admin';

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator konten',
        ];
    }

    public static function label(string $slug): string
    {
        return self::labels()[strtolower($slug)]
            ?? ucwords(str_replace(['-', '_'], ' ', $slug));
    }
}

<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'magister_hero_path',
    'doktor_hero_path',
])]
class HomepageProgramDisplay extends Model
{
    protected $table = 'homepage_program_displays';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'magister_hero_path' => null,
                'doktor_hero_path' => null,
            ]
        );
    }

    /**
     * URL publik untuk gambar hero beranda / halaman program.
     * Path di `storage/app/public/` disimpan sebagai `programs-heroes/...`.
     */
    public static function publicHeroUrl(?string $path, string $fallbackPublicPath): string
    {
        $path = $path !== null ? trim($path) : '';
        if ($path === '') {
            return asset(ltrim($fallbackPublicPath, '/'));
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'programs-heroes/')) {
            return asset('storage/'.$path);
        }

        return asset(ltrim($path, '/'));
    }

    public static function deleteStoredHero(?string $path): void
    {
        if ($path === null || $path === '' || ! str_starts_with($path, 'programs-heroes/')) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * @return array{magister: ?string, doktor: ?string}|null
     */
    public static function heroPathsFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('homepage_program_displays')) {
                return null;
            }

            $row = static::query()->where('id', 1)->first();
            if ($row === null) {
                return null;
            }

            return [
                'magister' => $row->magister_hero_path !== null && trim($row->magister_hero_path) !== ''
                    ? trim($row->magister_hero_path)
                    : null,
                'doktor' => $row->doktor_hero_path !== null && trim($row->doktor_hero_path) !== ''
                    ? trim($row->doktor_hero_path)
                    : null,
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}

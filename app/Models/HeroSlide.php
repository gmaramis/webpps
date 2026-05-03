<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'sort_order',
    'image',
])]
class HeroSlide extends Model
{
    protected $table = 'hero_slides';

    /** Gambar SVG/PNG statis seperti saat pertama kali (di folder publik slides/). */
    public const BUILTIN_SLIDE_PATHS = ['slides/slide-1.svg', 'slides/slide-2.svg', 'slides/slide-3.svg'];

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public function resolvedImageUrl(): string
    {
        return self::publicImageUrl($this->image);
    }

    public static function publicImageUrl(?string $image): string
    {
        $image = trim((string) $image);
        if ($image === '') {
            return asset('slides/slide-1.svg');
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        if (str_starts_with($image, '/')) {
            return asset(ltrim($image, '/'));
        }
        if (str_starts_with($image, 'hero-slides/')) {
            return asset('storage/'.$image);
        }

        return asset(ltrim($image, '/'));
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'hero-slides/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /** Ganti seluruh isi tiga slide bawaan (path statis publik); hapus berkas hero-slides/ yang ada. */
    public static function restoreBuiltInSlides(): void
    {
        static::withoutEvents(function (): void {
            foreach (static::query()->pluck('image') as $p) {
                static::deleteStoredUpload($p);
            }
            static::query()->delete();
            foreach (array_values(static::BUILTIN_SLIDE_PATHS) as $index => $path) {
                static::query()->create([
                    'sort_order' => $index,
                    'image' => $path,
                ]);
            }
        });
        PpsContent::flush();
    }
}

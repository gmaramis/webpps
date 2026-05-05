<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'sort_order',
    'is_published',
    'image',
    'image_alt_id',
    'image_alt_en',
    'caption_id',
    'caption_en',
])]
class ZiGalleryItem extends Model
{
    protected $table = 'zi_gallery_items';

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public function resolvedImagePublicPath(): string
    {
        $path = trim((string) $this->image);
        if ($path === '') {
            return 'news/cover-1.svg';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'zi-gallery/')) {
            return 'storage/'.$path;
        }

        return $path;
    }

    public function resolvedImageUrl(): string
    {
        $p = $this->resolvedImagePublicPath();
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }

        return asset(ltrim($p, '/'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $altEn = $this->image_alt_en !== null && trim($this->image_alt_en) !== '' ? $this->image_alt_en : $this->image_alt_id;
        $capEn = $this->caption_en !== null && trim($this->caption_en) !== '' ? $this->caption_en : $this->caption_id;

        return [
            'id' => (string) $this->getKey(),
            'image' => $this->resolvedImagePublicPath(),
            'imageAlt' => ['id' => $this->image_alt_id, 'en' => $altEn],
            'caption' => ['id' => $this->caption_id, 'en' => $capEn],
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'zi-gallery/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }
}

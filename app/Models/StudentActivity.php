<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JsonException;

#[Fillable([
    'sort_order',
    'is_published',
    'image',
    'image_alt_id',
    'image_alt_en',
    'title_id',
    'title_en',
    'excerpt_id',
    'excerpt_en',
])]
class StudentActivity extends Model
{
    protected $table = 'student_activities';

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

    /** URL gambar untuk admin & bentuk `image` di JSON publik. */
    public function resolvedImagePublicPath(): string
    {
        $path = trim((string) $this->image);
        if ($path === '') {
            return 'news/cover-1.svg';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'student-activities/')) {
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
     * Bentuk seperti STUDENT_ACTIVITIES di pps-content.json.
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $titleEn = $this->title_en !== null && trim($this->title_en) !== '' ? $this->title_en : $this->title_id;
        $excerptEn = $this->excerpt_en !== null && trim($this->excerpt_en) !== '' ? $this->excerpt_en : $this->excerpt_id;
        $altEn = $this->image_alt_en !== null && trim($this->image_alt_en) !== '' ? $this->image_alt_en : $this->image_alt_id;

        return [
            'id' => (string) $this->getKey(),
            'image' => $this->resolvedImagePublicPath(),
            'imageAlt' => [
                'id' => $this->image_alt_id,
                'en' => $altEn,
            ],
            'title' => [
                'id' => $this->title_id,
                'en' => $titleEn,
            ],
            'excerpt' => [
                'id' => $this->excerpt_id,
                'en' => $excerptEn,
            ],
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'student-activities/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /**
     * Impor dari key STUDENT_ACTIVITIES pada resources/data/pps-content.json (mengganti isi tabel).
     *
     * @throws JsonException
     */
    public static function importFromPpsContentJson(): int
    {
        $path = resource_path('data/pps-content.json');
        if (! File::exists($path)) {
            return 0;
        }

        $decoded = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        $rows = $decoded['STUDENT_ACTIVITIES'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            foreach (static::query()->pluck('image') as $imgPath) {
                static::deleteStoredUpload($imgPath);
            }
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $imageAlt = $row['imageAlt'] ?? [];
                $title = $row['title'] ?? [];
                $excerpt = $row['excerpt'] ?? [];
                static::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'image' => isset($row['image']) ? (string) $row['image'] : 'news/cover-1.svg',
                    'image_alt_id' => (string) ($imageAlt['id'] ?? ''),
                    'image_alt_en' => isset($imageAlt['en']) ? (string) $imageAlt['en'] : null,
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'excerpt_id' => (string) ($excerpt['id'] ?? ''),
                    'excerpt_en' => isset($excerpt['en']) ? (string) $excerpt['en'] : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

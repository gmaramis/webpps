<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JsonException;

#[Fillable([
    'sort_order',
    'slug',
    'name_id',
    'name_en',
    'blurb_id',
    'blurb_en',
    'excerpt_id',
    'excerpt_en',
    'official_url',
])]
class S3Program extends Model
{
    protected $table = 's3_programs';

    protected static function booted(): void
    {
        static::creating(function (S3Program $program): void {
            $slug = trim((string) $program->slug);
            if ($slug === '') {
                $program->slug = static::uniqueSlugFrom((string) $program->name_id);
            }
        });

        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public static function uniqueSlugFrom(string $nameId): string
    {
        $base = Str::slug($nameId) !== '' ? Str::slug($nameId) : 'program';
        $slug = $base;
        $n = 2;
        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }

    /**
     * Bentuk seperti PROGRAMS_DOKTOR di pps-content.json + slug & official_url.
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $nameEn = $this->name_en !== null && trim($this->name_en) !== '' ? $this->name_en : $this->name_id;
        $blurbEn = $this->blurb_en !== null && trim($this->blurb_en) !== '' ? $this->blurb_en : $this->blurb_id;
        $excerptId = $this->excerpt_id !== null ? trim((string) $this->excerpt_id) : '';
        $excerptEn = $this->excerpt_en !== null && trim((string) $this->excerpt_en) !== '' ? trim((string) $this->excerpt_en) : $excerptId;

        return [
            'id' => (string) $this->getKey(),
            'slug' => $this->slug,
            'name' => [
                'id' => $this->name_id,
                'en' => $nameEn,
            ],
            'blurb' => [
                'id' => $this->blurb_id,
                'en' => $blurbEn,
            ],
            'excerpt' => [
                'id' => $excerptId,
                'en' => $excerptEn,
            ],
            'official_url' => $this->official_url !== null && trim($this->official_url) !== ''
                ? trim($this->official_url)
                : null,
        ];
    }

    /**
     * Impor dari key PROGRAMS_DOKTOR pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['PROGRAMS_DOKTOR'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $name = $row['name'] ?? [];
                $blurb = $row['blurb'] ?? [];
                $excerpt = $row['excerpt'] ?? [];
                $nameId = (string) ($name['id'] ?? '');
                $excerptId = is_array($excerpt) ? trim((string) ($excerpt['id'] ?? '')) : '';
                $excerptEnRaw = is_array($excerpt) && isset($excerpt['en']) ? trim((string) $excerpt['en']) : '';

                static::query()->create([
                    'sort_order' => $index,
                    'slug' => static::uniqueSlugFrom($nameId !== '' ? $nameId : 'program-'.$index),
                    'name_id' => $nameId,
                    'name_en' => isset($name['en']) ? (string) $name['en'] : null,
                    'blurb_id' => (string) ($blurb['id'] ?? ''),
                    'blurb_en' => isset($blurb['en']) ? (string) $blurb['en'] : null,
                    'excerpt_id' => $excerptId !== '' ? $excerptId : null,
                    'excerpt_en' => $excerptEnRaw !== '' ? $excerptEnRaw : null,
                    'official_url' => isset($row['official_url']) && is_string($row['official_url']) && trim($row['official_url']) !== ''
                        ? trim($row['official_url'])
                        : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

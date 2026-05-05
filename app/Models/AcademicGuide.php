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
    'name_id',
    'name_en',
    'file_path',
    'show_on_academic_calendar',
])]
class AcademicGuide extends Model
{
    protected $table = 'academic_guides';

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'show_on_academic_calendar' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /** Path relatif ke `public/` untuk `asset()`, sama seperti entri ACADEMIC_GUIDES di JSON. */
    public function resolvedFilePublicPath(): string
    {
        $path = trim((string) $this->file_path);
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'academic-guides/')) {
            return 'storage/'.$path;
        }

        return $path;
    }

    /**
     * Bentuk seperti ACADEMIC_GUIDES di pps-content.json.
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $nameEn = $this->name_en !== null && trim($this->name_en) !== '' ? $this->name_en : $this->name_id;

        return [
            'id' => (string) $this->getKey(),
            'file' => $this->resolvedFilePublicPath(),
            'name' => [
                'id' => $this->name_id,
                'en' => $nameEn,
            ],
            'show_on_academic_calendar' => (bool) $this->show_on_academic_calendar,
        ];
    }

    /**
     * Entri panduan yang dipakai halaman kalender (bentuk seperti ACADEMIC_CALENDARS).
     *
     * @param  list<array<string, mixed>>  $guides  Isi key ACADEMIC_GUIDES dari PpsContent
     * @return list<array<string, mixed>>
     */
    public static function calendarPageEntriesFromGuideList(array $guides): array
    {
        return collect($guides)
            ->filter(fn (array $g): bool => self::isCalendarGuideEntry($g))
            ->values()
            ->map(fn (array $g): array => [
                'id' => (string) ($g['id'] ?? ''),
                'yearLabel' => $g['name'] ?? ['id' => '', 'en' => ''],
                'file' => (string) ($g['file'] ?? ''),
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $g  Satu entri ACADEMIC_GUIDES (JSON atau toFrontArray)
     */
    public static function isCalendarGuideEntry(array $g): bool
    {
        if (array_key_exists('show_on_academic_calendar', $g)) {
            return (bool) $g['show_on_academic_calendar'];
        }

        return self::inferCalendarFromLegacyGuideRow($g);
    }

    /**
     * Untuk data JSON lama tanpa flag: cocokkan judul/berkas yang berisi "kalender".
     *
     * @param  array<string, mixed>  $row
     */
    public static function inferCalendarFromLegacyGuideRow(array $row): bool
    {
        $file = strtolower((string) ($row['file'] ?? ''));
        $name = $row['name'] ?? [];
        $nameId = strtolower((string) (is_array($name) ? ($name['id'] ?? '') : ''));
        $nameEn = strtolower((string) (is_array($name) ? ($name['en'] ?? '') : ''));

        return str_contains($file, 'kalender')
            || str_contains($nameId, 'kalender')
            || str_contains($nameEn, 'kalender');
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'academic-guides/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /**
     * Impor dari key ACADEMIC_GUIDES pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['ACADEMIC_GUIDES'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            foreach (static::query()->pluck('file_path') as $storedPath) {
                static::deleteStoredUpload($storedPath);
            }
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $name = $row['name'] ?? [];
                static::query()->create([
                    'sort_order' => $index,
                    'name_id' => (string) ($name['id'] ?? ''),
                    'name_en' => isset($name['en']) ? (string) $name['en'] : null,
                    'file_path' => isset($row['file']) ? (string) $row['file'] : '',
                    'show_on_academic_calendar' => self::inferCalendarFromLegacyGuideRow($row),
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use JsonException;

#[Fillable([
    'sort_order',
    'is_published',
    'title_id',
    'title_en',
    'date_iso',
    'href',
])]
class AnnouncementItem extends Model
{
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'date_iso' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /**
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $titleEn = $this->title_en !== null && trim($this->title_en) !== '' ? $this->title_en : $this->title_id;

        return [
            'id' => (string) $this->getKey(),
            'title' => [
                'id' => $this->title_id,
                'en' => $titleEn,
            ],
            'dateISO' => optional($this->date_iso)->format('Y-m-d') ?? '',
            'href' => $this->href !== null && trim($this->href) !== '' ? $this->href : '#',
        ];
    }

    /**
     * Impor dari key ANNOUNCEMENTS pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['ANNOUNCEMENTS'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $title = is_array($row['title'] ?? null) ? $row['title'] : [];
                static::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'date_iso' => (string) ($row['dateISO'] ?? now()->format('Y-m-d')),
                    'href' => isset($row['href']) ? (string) $row['href'] : '#',
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

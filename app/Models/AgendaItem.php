<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use JsonException;

#[Fillable([
    'sort_order',
    'day',
    'month_id',
    'month_en',
    'title_id',
    'title_en',
    'href',
])]
class AgendaItem extends Model
{
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
        $monthEn = $this->month_en !== null && trim($this->month_en) !== '' ? $this->month_en : $this->month_id;
        $titleEn = $this->title_en !== null && trim($this->title_en) !== '' ? $this->title_en : $this->title_id;

        return [
            'id' => (string) $this->getKey(),
            'day' => $this->day,
            'month' => [
                'id' => $this->month_id,
                'en' => $monthEn,
            ],
            'title' => [
                'id' => $this->title_id,
                'en' => $titleEn,
            ],
            'href' => $this->href !== null && trim($this->href) !== '' ? $this->href : '#',
        ];
    }

    /**
     * Impor dari key AGENDA pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['AGENDA'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $month = is_array($row['month'] ?? null) ? $row['month'] : [];
                $title = is_array($row['title'] ?? null) ? $row['title'] : [];

                static::query()->create([
                    'sort_order' => $index,
                    'day' => (string) ($row['day'] ?? ''),
                    'month_id' => (string) ($month['id'] ?? ''),
                    'month_en' => isset($month['en']) ? (string) $month['en'] : null,
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'href' => isset($row['href']) ? (string) $row['href'] : '#',
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'sort_order',
    'is_published',
    'date_iso',
    'title_id',
    'title_en',
    'href',
])]
class ZiUpdateItem extends Model
{
    protected $table = 'zi_update_items';

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
            'dateISO' => $this->date_iso?->format('Y-m-d') ?? '',
            'title' => ['id' => $this->title_id, 'en' => $titleEn],
            'href' => $this->href,
        ];
    }
}

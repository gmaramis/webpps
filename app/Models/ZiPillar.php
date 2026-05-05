<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'sort_order',
    'is_published',
    'title_id',
    'title_en',
    'desc_id',
    'desc_en',
])]
class ZiPillar extends Model
{
    protected $table = 'zi_pillars';

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

    /**
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $titleEn = $this->title_en !== null && trim($this->title_en) !== '' ? $this->title_en : $this->title_id;
        $descEn = $this->desc_en !== null && trim($this->desc_en) !== '' ? $this->desc_en : $this->desc_id;

        return [
            'id' => (string) $this->getKey(),
            'title' => ['id' => $this->title_id, 'en' => $titleEn],
            'desc' => ['id' => $this->desc_id, 'en' => $descEn],
        ];
    }
}

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
    'summary_id',
    'summary_en',
    'href',
    'external',
])]
class ZiComplaintChannel extends Model
{
    protected $table = 'zi_complaint_channels';

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'external' => 'boolean',
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
        $sumEn = $this->summary_en !== null && trim($this->summary_en) !== '' ? $this->summary_en : $this->summary_id;
        $out = [
            'id' => (string) $this->getKey(),
            'title' => ['id' => $this->title_id, 'en' => $titleEn],
            'summary' => ['id' => $this->summary_id, 'en' => $sumEn],
            'href' => $this->href,
        ];
        if ($this->external) {
            $out['external'] = true;
        }

        return $out;
    }
}

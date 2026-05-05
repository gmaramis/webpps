<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'intro_heading_id',
    'intro_heading_en',
    'intro_p1_id',
    'intro_p1_en',
    'intro_p2_id',
    'intro_p2_en',
])]
class ZiPageIntro extends Model
{
    protected $table = 'zi_page_intros';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public static function singleton(): self
    {
        $row = static::query()->first();
        if ($row !== null) {
            return $row;
        }

        return static::query()->create([
            'intro_heading_id' => '',
            'intro_heading_en' => null,
            'intro_p1_id' => '',
            'intro_p1_en' => null,
            'intro_p2_id' => '',
            'intro_p2_en' => null,
        ]);
    }

    /**
     * Bentuk untuk blade publik (fallback ke $t jika string kosong ditangani di blade).
     *
     * @return array{heading: array{id: string, en: string}, p1: array{id: string, en: string}, p2: array{id: string, en: string}}
     */
    public function toPpsIntroArray(): array
    {
        $hEn = $this->intro_heading_en !== null && trim($this->intro_heading_en) !== '' ? $this->intro_heading_en : $this->intro_heading_id;
        $p1En = $this->intro_p1_en !== null && trim($this->intro_p1_en) !== '' ? $this->intro_p1_en : $this->intro_p1_id;
        $p2En = $this->intro_p2_en !== null && trim($this->intro_p2_en) !== '' ? $this->intro_p2_en : $this->intro_p2_id;

        return [
            'heading' => ['id' => $this->intro_heading_id, 'en' => $hEn],
            'p1' => ['id' => $this->intro_p1_id, 'en' => $p1En],
            'p2' => ['id' => $this->intro_p2_id, 'en' => $p2En],
        ];
    }
}

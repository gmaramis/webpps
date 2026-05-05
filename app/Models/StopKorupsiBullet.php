<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'sort_order',
    'text_id',
    'text_en',
])]
class StopKorupsiBullet extends Model
{
    protected $table = 'stop_korupsi_bullets';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /**
     * @return array{text: array{id: string, en: string}}
     */
    public function toFrontArray(): array
    {
        $en = $this->text_en !== null && trim((string) $this->text_en) !== '' ? (string) $this->text_en : (string) $this->text_id;

        return [
            'text' => [
                'id' => (string) $this->text_id,
                'en' => $en,
            ],
        ];
    }
}

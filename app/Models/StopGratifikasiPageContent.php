<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'eyebrow_id',
    'eyebrow_en',
    'title_id',
    'title_en',
    'lead_id',
    'lead_en',
    'p1_id',
    'p1_en',
    'p2_id',
    'p2_en',
    'bullets_title_id',
    'bullets_title_en',
    'cta_title_id',
    'cta_title_en',
    'cta_p_id',
    'cta_p_en',
    'link_instrumen_zi_label_id',
    'link_instrumen_zi_label_en',
    'link_instrumen_zi_url',
    'simple_body_id',
    'simple_body_en',
])]
class StopGratifikasiPageContent extends Model
{
    protected $table = 'stop_gratifikasi_page_contents';

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

        return static::query()->create([]);
    }

    public function isiUntukForm(): string
    {
        if ($this->simple_body_id !== null && trim((string) $this->simple_body_id) !== '') {
            return (string) $this->simple_body_id;
        }

        return collect([$this->lead_id, $this->p1_id, $this->p2_id])
            ->map(fn ($s): string => trim((string) ($s ?? '')))
            ->filter()
            ->implode("\n\n");
    }

    public function isiEnUntukForm(): string
    {
        if ($this->simple_body_en !== null && trim((string) $this->simple_body_en) !== '') {
            return (string) $this->simple_body_en;
        }

        return collect([$this->lead_en, $this->p1_en, $this->p2_en])
            ->map(fn ($s): string => trim((string) ($s ?? '')))
            ->filter()
            ->implode("\n\n");
    }
}

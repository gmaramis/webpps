<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'photo_path',
    'name_id',
    'name_en',
    'role_id',
    'role_en',
    'section_eyebrow_id',
    'section_eyebrow_en',
    'section_title_id',
    'section_title_en',
    'section_quote_label_id',
    'section_quote_label_en',
    'paragraphs',
])]
class DirectorGreeting extends Model
{
    protected $table = 'director_greetings';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'paragraphs' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'photo_path' => null,
                'name_id' => null,
                'name_en' => null,
                'role_id' => null,
                'role_en' => null,
                'section_eyebrow_id' => null,
                'section_eyebrow_en' => null,
                'section_title_id' => null,
                'section_title_en' => null,
                'section_quote_label_id' => null,
                'section_quote_label_en' => null,
                'paragraphs' => [],
            ]
        );
    }

    public static function deleteStoredPhoto(?string $path): void
    {
        if ($path === null || $path === '' || ! str_starts_with($path, 'director-greeting/')) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Bentuk seperti key DIRECTOR_GREETING di pps-content.json (untuk beranda).
     *
     * @return array<string, mixed>
     */
    public function toPpsContentDirectorBlock(): array
    {
        $paragraphs = $this->paragraphs;
        if (! is_array($paragraphs)) {
            $paragraphs = [];
        }

        $photo = trim((string) ($this->photo_path ?? ''));

        return [
            'photo' => $photo,
            'name' => [
                'id' => trim((string) ($this->name_id ?? '')),
                'en' => trim((string) ($this->name_en ?? '')),
            ],
            'role' => [
                'id' => trim((string) ($this->role_id ?? '')),
                'en' => trim((string) ($this->role_en ?? '')),
            ],
            'paragraphs' => $paragraphs,
        ];
    }
}

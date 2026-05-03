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
    'cooperation_id',
    'cooperation_en',
    'logo',
])]
class CooperationPartner extends Model
{
    protected $table = 'cooperation_partners';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /** URL logo untuk halaman publik & pratinjau admin. */
    public function resolvedLogoUrl(): string
    {
        return self::publicLogoUrl($this->logo);
    }

    public static function publicLogoUrl(?string $logo): string
    {
        $logo = trim((string) $logo);
        if ($logo === '') {
            return asset('partners/partner-1.svg');
        }
        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }
        if (str_starts_with($logo, '/')) {
            return asset(ltrim($logo, '/'));
        }
        if (str_starts_with($logo, 'cooperation-logos/')) {
            return asset('storage/'.$logo);
        }

        return asset($logo);
    }

    /**
     * Bentuk seperti PARTNERS di pps-content.json.
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $nameEn = $this->name_en !== null && trim($this->name_en) !== '' ? $this->name_en : $this->name_id;
        $coopEn = $this->cooperation_en !== null && trim($this->cooperation_en) !== '' ? $this->cooperation_en : $this->cooperation_id;

        return [
            'id' => (string) $this->getKey(),
            'logo' => $this->logo ?? '',
            'name' => [
                'id' => $this->name_id,
                'en' => $nameEn,
            ],
            'cooperation' => [
                'id' => $this->cooperation_id,
                'en' => $coopEn,
            ],
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'cooperation-logos/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /**
     * Impor dari key PARTNERS pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['PARTNERS'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            foreach (static::query()->pluck('logo') as $logoPath) {
                static::deleteStoredUpload($logoPath);
            }
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $name = $row['name'] ?? [];
                $coop = $row['cooperation'] ?? [];
                static::query()->create([
                    'sort_order' => $index,
                    'name_id' => (string) ($name['id'] ?? ''),
                    'name_en' => isset($name['en']) ? (string) $name['en'] : null,
                    'cooperation_id' => (string) ($coop['id'] ?? ''),
                    'cooperation_en' => isset($coop['en']) ? (string) $coop['en'] : null,
                    'logo' => isset($row['logo']) ? (string) $row['logo'] : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

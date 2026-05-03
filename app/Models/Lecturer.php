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
    'nidn',
    'nip',
    'study_program_id',
    'study_program_en',
    'functional_role_id',
    'functional_role_en',
    'phone',
    'photo',
])]
class Lecturer extends Model
{
    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public function resolvedPhotoUrl(): string
    {
        return self::publicPhotoUrl($this->photo);
    }

    public static function publicPhotoUrl(?string $photo): string
    {
        $photo = trim((string) $photo);
        if ($photo === '') {
            return asset('programs/doktor-photo.png');
        }
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }
        if (str_starts_with($photo, '/')) {
            return asset(ltrim($photo, '/'));
        }
        if (str_starts_with($photo, 'lecturer-photos/')) {
            return asset('storage/'.$photo);
        }

        return asset(ltrim($photo, '/'));
    }

    /**
     * Bentuk seperti LECTURERS di pps-content.json.
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        $nameEn = $this->name_en !== null && trim($this->name_en) !== '' ? $this->name_en : $this->name_id;
        $progEn = $this->study_program_en !== null && trim($this->study_program_en) !== '' ? $this->study_program_en : $this->study_program_id;
        $funcEn = $this->functional_role_en !== null && trim($this->functional_role_en) !== '' ? $this->functional_role_en : $this->functional_role_id;

        return [
            'id' => (string) $this->getKey(),
            'photo' => $this->photo ?? '',
            'name' => [
                'id' => $this->name_id,
                'en' => $nameEn,
            ],
            'nip' => $this->nip ?? '',
            'nidn' => $this->nidn ?? '',
            'functionalRole' => [
                'id' => $this->functional_role_id,
                'en' => $funcEn,
            ],
            'phone' => $this->phone ?? '',
            'studyProgram' => [
                'id' => $this->study_program_id,
                'en' => $progEn,
            ],
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'lecturer-photos/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /**
     * Impor dari key LECTURERS pada resources/data/pps-content.json (mengganti isi tabel).
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
        $rows = $decoded['LECTURERS'] ?? [];
        if (! is_array($rows) || $rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            foreach (static::query()->pluck('photo') as $photoPath) {
                static::deleteStoredUpload($photoPath);
            }
            static::query()->delete();

            foreach (array_values($rows) as $index => $row) {
                $name = $row['name'] ?? [];
                $func = $row['functionalRole'] ?? [];
                $prog = $row['studyProgram'] ?? [];
                static::query()->create([
                    'sort_order' => $index,
                    'name_id' => (string) ($name['id'] ?? ''),
                    'name_en' => isset($name['en']) ? (string) $name['en'] : null,
                    'nidn' => isset($row['nidn']) ? (string) $row['nidn'] : null,
                    'nip' => isset($row['nip']) ? (string) $row['nip'] : null,
                    'study_program_id' => (string) ($prog['id'] ?? ''),
                    'study_program_en' => isset($prog['en']) ? (string) $prog['en'] : null,
                    'functional_role_id' => (string) ($func['id'] ?? ''),
                    'functional_role_en' => isset($func['en']) ? (string) $func['en'] : null,
                    'phone' => isset($row['phone']) ? (string) $row['phone'] : null,
                    'photo' => isset($row['photo']) ? (string) $row['photo'] : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

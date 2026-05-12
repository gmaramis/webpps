<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
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
    'email',
    'google_scholar_url',
    'photo',
])]
class Lecturer extends Model
{
    /**
     * Jabatan fungsional standar (Bahasa Indonesia) — dipakai admin & validasi.
     *
     * @return list<string>
     */
    public static function functionalRankIds(): array
    {
        return [
            'Asisten Ahli',
            'Lektor',
            'Lektor Kepala',
            'Guru Besar',
        ];
    }

    public static function functionalRankEnglish(string $id): string
    {
        return match (trim($id)) {
            'Asisten Ahli' => 'Assistant Expert',
            'Lektor' => 'Lecturer',
            'Lektor Kepala' => 'Principal Lecturer',
            'Guru Besar' => 'Professor',
            default => trim($id),
        };
    }

    /**
     * Nama program studi (Bahasa Indonesia) dari tabel s2_programs & s3_programs.
     *
     * @return list<string>
     */
    public static function studyProgramNameIdsFromDatabase(): array
    {
        if (! Schema::hasTable('s2_programs') || ! Schema::hasTable('s3_programs')) {
            return [];
        }

        $s2 = S2Program::query()->orderBy('sort_order')->orderBy('id')->pluck('name_id');
        $s3 = S3Program::query()->orderBy('sort_order')->orderBy('id')->pluck('name_id');

        return array_values(array_unique(array_merge($s2->all(), $s3->all())));
    }

    /**
     * Judul program bahasa Inggris dari basis data (name_en), atau name_id jika EN kosong.
     */
    public static function resolveStudyProgramEnglishFromId(string $nameId): ?string
    {
        $nameId = trim($nameId);
        if ($nameId === '' || ! Schema::hasTable('s2_programs') || ! Schema::hasTable('s3_programs')) {
            return null;
        }

        $row = S2Program::query()->where('name_id', $nameId)->first()
            ?? S3Program::query()->where('name_id', $nameId)->first();

        if ($row === null) {
            return null;
        }

        $en = $row->name_en;

        return $en !== null && trim((string) $en) !== '' ? trim((string) $en) : $nameId;
    }

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
        $progId = trim((string) ($this->study_program_id ?? ''));
        $fromDb = self::resolveStudyProgramEnglishFromId($progId);
        if ($fromDb !== null) {
            $progEn = $fromDb;
        } else {
            $progEn = $this->study_program_en !== null && trim($this->study_program_en) !== '' ? $this->study_program_en : $progId;
        }
        $funcId = trim((string) ($this->functional_role_id ?? ''));
        if (in_array($funcId, self::functionalRankIds(), true)) {
            $funcEn = self::functionalRankEnglish($funcId);
        } else {
            $funcEn = $this->functional_role_en !== null && trim($this->functional_role_en) !== '' ? $this->functional_role_en : $funcId;
        }

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
            'email' => $this->email ?? '',
            'googleScholarUrl' => $this->google_scholar_url ?? '',
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
                $funcId = trim((string) ($func['id'] ?? ''));
                $funcEn = isset($func['en']) ? (string) $func['en'] : null;
                if (in_array($funcId, self::functionalRankIds(), true)) {
                    $funcEn = self::functionalRankEnglish($funcId);
                }
                $progId = trim((string) ($prog['id'] ?? ''));
                $progEn = isset($prog['en']) ? (string) $prog['en'] : null;
                $progEnFromDb = self::resolveStudyProgramEnglishFromId($progId);
                if ($progEnFromDb !== null) {
                    $progEn = $progEnFromDb;
                }
                static::query()->create([
                    'sort_order' => $index,
                    'name_id' => (string) ($name['id'] ?? ''),
                    'name_en' => isset($name['en']) ? (string) $name['en'] : null,
                    'nidn' => isset($row['nidn']) ? (string) $row['nidn'] : null,
                    'nip' => isset($row['nip']) ? (string) $row['nip'] : null,
                    'study_program_id' => $progId,
                    'study_program_en' => $progEn,
                    'functional_role_id' => $funcId,
                    'functional_role_en' => $funcEn,
                    'phone' => isset($row['phone']) ? (string) $row['phone'] : null,
                    'email' => isset($row['email']) ? (string) $row['email'] : null,
                    'google_scholar_url' => isset($row['googleScholarUrl']) ? (string) $row['googleScholarUrl'] : null,
                    'photo' => isset($row['photo']) ? (string) $row['photo'] : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

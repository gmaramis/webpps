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
    'name',
    'nip',
    'photo',
    'role_id',
    'role_en',
])]
class LeadershipPerson extends Model
{
    protected $table = 'leadership_people';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /**
     * URL tampilan foto untuk halaman publik & admin pratinjau.
     */
    public function resolvedPhotoUrl(): string
    {
        return self::publicPhotoUrl($this->photo);
    }

    public static function publicPhotoUrl(?string $photo): string
    {
        $photo ??= '';
        $photo = trim($photo);
        if ($photo === '') {
            return asset('programs/doktor-photo.png');
        }
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        return str_starts_with($photo, '/')
            ? asset(ltrim($photo, '/'))
            : asset('storage/'.$photo);
    }

    /**
     * Entri seperti di leaders.json untuk ppsData['LEADERS'].
     *
     * @return array<string, mixed>
     */
    public function toFrontArray(): array
    {
        return [
            'role' => [
                'id' => $this->role_id,
                'en' => $this->role_en !== null && trim($this->role_en) !== '' ? $this->role_en : $this->role_id,
            ],
            'name' => $this->name,
            'nip' => $this->nip ?? '',
            'photo' => $this->photo ?? '',
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'leadership/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    /**
     * @throws JsonException
     */
    public static function importFromLegacyJson(): int
    {
        $path = resource_path('data/leaders.json');
        if (! File::exists($path)) {
            return 0;
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        if ($rows === []) {
            return 0;
        }

        $count = static::withoutEvents(function () use ($rows): int {
            foreach (static::query()->pluck('photo') as $path) {
                static::deleteStoredUpload($path);
            }
            static::query()->delete();

            foreach ($rows as $index => $row) {
                $role = $row['role'] ?? [];
                static::query()->create([
                    'sort_order' => $index,
                    'name' => (string) ($row['name'] ?? ''),
                    'nip' => isset($row['nip']) ? (string) $row['nip'] : null,
                    'photo' => isset($row['photo']) ? (string) $row['photo'] : null,
                    'role_id' => (string) ($role['id'] ?? ''),
                    'role_en' => isset($role['en']) ? (string) $role['en'] : null,
                ]);
            }

            return count($rows);
        });

        PpsContent::flush();

        return $count;
    }
}

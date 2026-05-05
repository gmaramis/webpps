<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'sort_order',
    'title_id',
    'title_en',
    'file_path',
    'is_published',
])]
class AccreditationDocument extends Model
{
    protected $table = 'accreditation_documents';

    /**
     * @return array<string, string>
     */
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

    /** Path relatif untuk `asset()`, seperti entri ACCREDITATION_DOCUMENTS di JSON lama. */
    public function resolvedFilePublicPath(): string
    {
        $path = trim((string) $this->file_path);
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'accreditation-documents/')) {
            return 'storage/'.$path;
        }

        return $path;
    }

    /**
     * @return array{id: string, file: string, name: array{id: string, en: string}}
     */
    public function toFrontArray(): array
    {
        $en = $this->title_en !== null && trim((string) $this->title_en) !== ''
            ? (string) $this->title_en
            : (string) $this->title_id;

        return [
            'id' => (string) $this->getKey(),
            'file' => $this->resolvedFilePublicPath(),
            'name' => [
                'id' => (string) $this->title_id,
                'en' => $en,
            ],
        ];
    }

    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'accreditation-documents/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }
}

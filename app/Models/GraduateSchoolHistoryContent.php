<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'eyebrow_id',
    'eyebrow_en',
    'title_id',
    'title_en',
    'paragraph_id',
    'paragraph_en',
    'image_path',
])]
class GraduateSchoolHistoryContent extends Model
{
    protected $table = 'graduate_school_history_contents';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public static function deleteStoredImage(?string $path): void
    {
        if ($path === null || $path === '' || ! str_starts_with($path, 'graduate-school-history/')) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /** URL publik untuk gambar sejarah beranda (path di disk public atau URL absolut). */
    public static function publicImageUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    /**
     * @return array<string, string|null>
     */
    public static function defaultPayload(): array
    {
        return [
            'eyebrow_id' => 'Profil Institusi',
            'eyebrow_en' => 'Institution Profile',
            'title_id' => 'Sejarah Pascasarjana UNIMA',
            'title_en' => 'History of UNIMA Graduate School',
            'paragraph_id' => 'Sekolah Pascasarjana Universitas Negeri Manado hadir untuk menjawab kebutuhan pendidikan lanjut dan pengembangan riset di kawasan timur Indonesia. Sejak berdiri, Pascasarjana UNIMA berkomitmen memperkuat kualitas sumber daya manusia melalui program magister dan doktor yang adaptif serta memperluas jejaring kemitraan di tingkat lokal, nasional, dan internasional.',
            'paragraph_en' => 'The Graduate School of Manado State University was established to meet the need for advanced education and research development in Eastern Indonesia. Since its establishment, the UNIMA Graduate School has strengthened human resource quality through adaptive master’s and doctoral programs and expanded partnerships at local, national, and international levels.',
            'image_path' => null,
        ];
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            self::defaultPayload()
        );
    }
}

<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('title', 'excerpt', 'body', 'category', 'location', 'slug', 'meta_title', 'meta_description')]
#[Fillable([
    'is_published',
    'author',
    'title',
    'excerpt',
    'body',
    'category',
    'location',
    'meta_title',
    'meta_description',
    'href',
    'image_path',
    'translation_status',
    'translation_error',
])]
class NewsItem extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(static function (NewsItem $item): void {
            if (! $item->is_published) {
                $item->published_at = null;

                return;
            }
            if ($item->published_at === null) {
                $item->published_at = now();
            }
        });

        static::created(static function (NewsItem $item): void {
            $item->syncSlugsFromTitles();
            $item->saveQuietly();
            PpsContent::flush();
        });

        static::saved(static fn() => PpsContent::flush());
        static::deleted(static fn() => PpsContent::flush());
    }

    /** Slug URL per locale dari judul saat ini. */
    public function syncSlugsFromTitles(): void
    {
        foreach (['id', 'en', 'zh'] as $loc) {
            $title = (string) $this->getTranslationWithoutFallback('title', $loc);
            if ($loc === 'zh' && trim($title) === '') {
                continue;
            }
            $base = Str::slug(Str::limit($title !== '' ? $title : ($loc === 'id' ? 'berita' : 'news'), 80, ''));
            if ($base === '') {
                $base = $loc === 'id' ? 'berita' : 'news';
            }
            $this->setTranslation('slug', $loc, $base . '-' . $this->getKey());
        }
    }

    /**
     * Ambil translasi atribut dengan fallback: zh -> en -> id, en -> id, id.
     */
    public function getTranslationWithFallback(string $attribute, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $val = trim((string) ($this->getTranslationWithoutFallback($attribute, $locale) ?? ''));
        if ($val !== '') {
            return $val;
        }

        if ($locale === 'zh') {
            $valEn = trim((string) ($this->getTranslationWithoutFallback($attribute, 'en') ?? ''));
            if ($valEn !== '') {
                return $valEn;
            }

            return trim((string) ($this->getTranslationWithoutFallback($attribute, 'id') ?? ''));
        }

        if ($locale === 'en') {
            return trim((string) ($this->getTranslationWithoutFallback($attribute, 'id') ?? ''));
        }

        return '';
    }

    /** Draf dengan isi Indonesia tapi isi Inggris masih kosong → layak diterjemahkan otomatis. */
    public function needsEnglishAutofill(): bool
    {
        if ($this->is_published) {
            return false;
        }
        $idBody = trim((string) $this->getTranslationWithoutFallback('body', 'id'));

        return $idBody !== '' && trim((string) $this->getTranslationWithoutFallback('body', 'en')) === '';
    }

    /** @return array{id: string, en: string, zh: string} */
    public function translationsForFrontend(string $attribute): array
    {
        return [
            'id' => (string) ($this->getTranslationWithoutFallback($attribute, 'id') ?? ''),
            'en' => (string) ($this->getTranslationWithoutFallback($attribute, 'en') ?? ''),
            'zh' => (string) ($this->getTranslationWithoutFallback($attribute, 'zh') ?? ''),
        ];
    }

    /**
     * Path publik untuk atribut gambar hero/kartu (sama struktur seperti field `image` dari JSON NEWS).
     * Unggahan baru disimpan di disk `public` sebagai `news/...`; lama bisa berupa `/news/*.svg` di folder public.
     */
    public function resolvedNewsImagePath(): string
    {
        $raw = $this->image_path;
        if ($raw === null || $raw === '') {
            return '/news/cover-1.svg';
        }

        $trim = trim($raw);
        if (str_starts_with($trim, 'http://') || str_starts_with($trim, 'https://')) {
            return $trim;
        }

        $rel = ltrim(str_replace('\\', '/', $trim), '/');
        if (Storage::disk('public')->exists($rel)) {
            return '/storage/' . $rel;
        }

        return str_starts_with($trim, '/') ? $trim : '/' . $rel;
    }

    /** URL lengkap untuk atribut HTML `src`. */
    public function newsImageUrl(): string
    {
        $p = $this->resolvedNewsImagePath();
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }

        return asset(ltrim($p, '/'));
    }

    /** Hapus file unggahan di storage jika path mengarah ke disk public. */
    public static function deleteStoredUpload(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        $rel = ltrim(str_replace('\\', '/', $path), '/');
        if (Storage::disk('public')->exists($rel)) {
            Storage::disk('public')->delete($rel);
        }
    }
}

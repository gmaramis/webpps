<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class StudyProgramCurriculum extends Model
{
    protected $table = 'study_program_curricula';

    protected $fillable = [
        's2_program_id',
        's3_program_id',
        'pdf_path',
    ];

    protected static function booted(): void
    {
        static::deleting(function (StudyProgramCurriculum $row): void {
            self::deleteStoredPdf($row->pdf_path);
        });
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    public function s2Program(): BelongsTo
    {
        return $this->belongsTo(S2Program::class, 's2_program_id');
    }

    public function s3Program(): BelongsTo
    {
        return $this->belongsTo(S3Program::class, 's3_program_id');
    }

    public function resolvedPdfUrl(): string
    {
        return self::publicPdfUrl($this->pdf_path);
    }

    public static function publicPdfUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return asset(ltrim($path, '/'));
        }
        if (str_starts_with($path, 'curriculum-pdfs/')) {
            return asset('storage/'.$path);
        }

        return asset(ltrim($path, '/'));
    }

    public static function deleteStoredPdf(?string $path): void
    {
        if ($path === null || $path === '' || str_contains($path, '..')) {
            return;
        }
        if (! str_starts_with($path, 'curriculum-pdfs/')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }
}

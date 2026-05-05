<?php

namespace App\Models;

use App\Support\PpsContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

#[Fillable([
    'portal_url',
    'lms_url',
    'spada_url',
])]
class AcademicPortalSetting extends Model
{
    protected $table = 'academic_portal_settings';

    protected static function booted(): void
    {
        static::saved(static fn () => PpsContent::flush());
        static::deleted(static fn () => PpsContent::flush());
    }

    /**
     * @return array{portal: string, lms: string, spada: string}
     */
    public static function defaultUrls(): array
    {
        return [
            'portal' => 'https://si.unima.ac.id/gtakademik_portal/',
            'lms' => 'https://lms.unima.ac.id/',
            'spada' => 'https://spada.kemdiktisaintek.go.id',
        ];
    }

    /**
     * URL untuk menu Akademik (portal, LMS, SPADA) dan footer.
     *
     * @return array{portal: string, lms: string, spada: string}
     */
    public static function resolvedUrls(): array
    {
        $defaults = self::defaultUrls();

        try {
            if (! Schema::hasTable((new self)->getTable())) {
                return $defaults;
            }

            $row = static::query()->first();
            if ($row === null) {
                return $defaults;
            }

            return [
                'portal' => self::nonEmptyOr($row->portal_url, $defaults['portal']),
                'lms' => self::nonEmptyOr($row->lms_url, $defaults['lms']),
                'spada' => self::nonEmptyOr($row->spada_url, $defaults['spada']),
            ];
        } catch (\Throwable) {
            return $defaults;
        }
    }

    public static function singleton(): self
    {
        $d = self::defaultUrls();

        if (! Schema::hasTable((new self)->getTable())) {
            return (new self)->forceFill($d);
        }

        $row = static::query()->first();
        if ($row !== null) {
            return $row;
        }

        return static::query()->create([
            'portal_url' => $d['portal'],
            'lms_url' => $d['lms'],
            'spada_url' => $d['spada'],
        ]);
    }

    protected static function nonEmptyOr(string $value, string $fallback): string
    {
        $t = trim($value);

        return $t !== '' ? $t : $fallback;
    }
}

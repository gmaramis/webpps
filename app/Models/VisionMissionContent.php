<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'vision_id',
    'vision_en',
    'mission_id',
    'mission_en',
    'values_id',
    'values_en',
])]
class VisionMissionContent extends Model
{
    /**
     * Satu baris konten global untuk halaman visi–misi.
     *
     * @return array<string, mixed>
     */
    public static function defaultPayload(): array
    {
        return [
            'vision_id' => 'Menjadi lembaga pascasarjana yang unggul dalam pengembangan ilmu pengetahuan, teknologi, dan seni—berdaya saing di kawasan dan berkontribusi pada pembangunan nasional melalui pendidikan tinggi, penelitian, dan pengabdian kepada masyarakat.',
            'vision_en' => 'To become an outstanding graduate school in the advancement of science, technology, and the arts—regionally competitive and contributing to national development through higher education, research, and community engagement.',
            'mission_id' => [
                'Menyelenggarakan pendidikan magister dan doktor yang bermutu, relevan, dan berkeadilan sosial.',
                'Mengembangkan budaya riset, publikasi ilmiah, dan inovasi yang menjawab tantangan lokal dan global.',
                'Memperkuat kemitraan strategis dengan pemerintah, dunia usaha, dan masyarakat sipil.',
                'Menyelenggarakan tata kelola institusi yang transparan, akuntabel, dan berorientasi pada layanan prima.',
            ],
            'mission_en' => [
                'Deliver equitable, relevant, and high-quality master’s and doctoral education.',
                'Foster a culture of research, scientific publication, and innovation that responds to local and global challenges.',
                'Strengthen strategic partnerships with government, industry, and civil society.',
                'Uphold transparent, accountable governance oriented toward excellent service.',
            ],
            'values_id' => [
                'Integritas dan etika akademik sebagai landasan setiap kegiatan.',
                'Kolaborasi lintas disiplin dan lintas institusi untuk memperkaya pembelajaran dan riset.',
                'Penguatan kapasitas dosen dan mahasiswa melalui pembinaan berkelanjutan.',
            ],
            'values_en' => [
                'Integrity and academic ethics as the foundation of all activities.',
                'Interdisciplinary and cross-institutional collaboration to enrich learning and research.',
                'Continuous capacity building for faculty and graduate students.',
            ],
        ];
    }

    public static function singleton(): self
    {
        $row = static::query()->first();
        if ($row !== null) {
            return $row;
        }

        return static::query()->create(self::defaultPayload());
    }

    /**
     * @return array{vision: string, mission: list<string>, values: list<string>}
     */
    public static function resolvedBlocks(string $locale): array
    {
        try {
            $row = static::query()->first();
            if ($row !== null) {
                return $row->blocksForLocale($locale);
            }
        } catch (\Throwable) {
            // Tabel belum ada atau DB tidak siap — pakai bawaan.
        }

        $defaults = self::defaultPayload();

        return self::blocksFromPayload($defaults, $locale);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{vision: string, mission: list<string>, values: list<string>}
     */
    public static function blocksFromPayload(array $payload, string $locale): array
    {
        $missionId = self::normalizeLines($payload['mission_id'] ?? []);
        $missionEn = self::normalizeLines($payload['mission_en'] ?? []);
        $valuesId = self::normalizeLines($payload['values_id'] ?? []);
        $valuesEn = self::normalizeLines($payload['values_en'] ?? []);

        if ($locale === 'en') {
            return [
                'vision' => trim((string) ($payload['vision_en'] ?? '')) !== ''
                    ? trim((string) $payload['vision_en'])
                    : trim((string) ($payload['vision_id'] ?? '')),
                'mission' => $missionEn !== [] ? $missionEn : $missionId,
                'values' => $valuesEn !== [] ? $valuesEn : $valuesId,
            ];
        }

        return [
            'vision' => trim((string) ($payload['vision_id'] ?? '')),
            'mission' => $missionId,
            'values' => $valuesId,
        ];
    }

    /**
     * @return array{vision: string, mission: list<string>, values: list<string>}
     */
    public function blocksForLocale(string $locale): array
    {
        return self::blocksFromPayload($this->attributesToArray(), $locale);
    }

    /**
     * @return list<string>
     */
    public static function normalizeLines(mixed $lines): array
    {
        if (! is_array($lines)) {
            return [];
        }

        $out = [];
        foreach ($lines as $line) {
            $s = trim((string) $line);
            if ($s !== '') {
                $out[] = $s;
            }
        }

        return $out;
    }

    /**
     * Satu item per baris dari textarea admin.
     *
     * @return list<string>
     */
    public static function linesFromTextarea(string $raw): array
    {
        $parts = preg_split("/\r\n|\r|\n/", $raw) ?: [];

        return self::normalizeLines($parts);
    }

    protected function casts(): array
    {
        return [
            'mission_id' => 'array',
            'mission_en' => 'array',
            'values_id' => 'array',
            'values_en' => 'array',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\StopGratifikasiBullet;
use App\Models\StopGratifikasiPageContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use JsonException;

final class StopGratifikasiContentImporter
{
    /**
     * Mengisi ulang konten halaman Stop Gratifikasi dari resources/data/pps-content.json
     * (STRINGS + STOP_GRATIFIKASI_BULLETS). Menghapus semua poin di basis data lalu menyisipkan ulang.
     *
     * @throws JsonException
     */
    public static function importFromPpsContentJson(): void
    {
        $path = resource_path('data/pps-content.json');
        if (! is_readable($path)) {
            throw new JsonException('pps-content.json tidak dapat dibaca.');
        }

        $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        $idStr = is_array($data['STRINGS']['id'] ?? null) ? $data['STRINGS']['id'] : [];
        $enStr = is_array($data['STRINGS']['en'] ?? null) ? $data['STRINGS']['en'] : [];

        DB::transaction(function () use ($idStr, $enStr, $data): void {
            $joinBodies = static function (array $parts): ?string {
                $s = collect($parts)
                    ->map(fn ($p): string => trim((string) ($p ?? '')))
                    ->filter()
                    ->implode("\n\n");

                return $s !== '' ? $s : null;
            };

            $simpleId = $joinBodies([
                $idStr['stopGratifikasiLead'] ?? '',
                $idStr['stopGratifikasiP1'] ?? '',
                $idStr['stopGratifikasiP2'] ?? '',
            ]);
            $simpleEn = $joinBodies([
                $enStr['stopGratifikasiLead'] ?? '',
                $enStr['stopGratifikasiP1'] ?? '',
                $enStr['stopGratifikasiP2'] ?? '',
            ]);

            $page = StopGratifikasiPageContent::singleton();
            $page->update([
                'eyebrow_id' => (string) ($idStr['stopGratifikasiEyebrow'] ?? ''),
                'eyebrow_en' => isset($enStr['stopGratifikasiEyebrow']) ? (string) $enStr['stopGratifikasiEyebrow'] : null,
                'title_id' => (string) ($idStr['stopGratifikasiTitle'] ?? ''),
                'title_en' => isset($enStr['stopGratifikasiTitle']) ? (string) $enStr['stopGratifikasiTitle'] : null,
                'lead_id' => (string) ($idStr['stopGratifikasiLead'] ?? ''),
                'lead_en' => isset($enStr['stopGratifikasiLead']) ? (string) $enStr['stopGratifikasiLead'] : null,
                'p1_id' => (string) ($idStr['stopGratifikasiP1'] ?? ''),
                'p1_en' => isset($enStr['stopGratifikasiP1']) ? (string) $enStr['stopGratifikasiP1'] : null,
                'p2_id' => (string) ($idStr['stopGratifikasiP2'] ?? ''),
                'p2_en' => isset($enStr['stopGratifikasiP2']) ? (string) $enStr['stopGratifikasiP2'] : null,
                'simple_body_id' => $simpleId,
                'simple_body_en' => $simpleEn,
                'bullets_title_id' => (string) ($idStr['stopGratifikasiBulletsTitle'] ?? ''),
                'bullets_title_en' => isset($enStr['stopGratifikasiBulletsTitle']) ? (string) $enStr['stopGratifikasiBulletsTitle'] : null,
                'cta_title_id' => (string) ($idStr['stopGratifikasiCtaTitle'] ?? ''),
                'cta_title_en' => isset($enStr['stopGratifikasiCtaTitle']) ? (string) $enStr['stopGratifikasiCtaTitle'] : null,
                'cta_p_id' => (string) ($idStr['stopGratifikasiCtaP'] ?? ''),
                'cta_p_en' => isset($enStr['stopGratifikasiCtaP']) ? (string) $enStr['stopGratifikasiCtaP'] : null,
                'link_instrumen_zi_label_id' => (string) ($idStr['stopLinkInstrumenZi'] ?? ''),
                'link_instrumen_zi_label_en' => isset($enStr['stopLinkInstrumenZi']) ? (string) $enStr['stopLinkInstrumenZi'] : null,
                'link_instrumen_zi_url' => null,
            ]);

            StopGratifikasiBullet::query()->delete();

            $bullets = $data['STOP_GRATIFIKASI_BULLETS'] ?? [];
            if (! is_array($bullets)) {
                return;
            }

            foreach (array_values($bullets) as $i => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $text = $row['text'] ?? [];
                $tid = is_array($text) ? (string) ($text['id'] ?? '') : '';
                if (trim($tid) === '') {
                    continue;
                }
                $ten = is_array($text) && isset($text['en']) ? (string) $text['en'] : null;

                StopGratifikasiBullet::query()->create([
                    'sort_order' => (int) $i,
                    'text_id' => $tid,
                    'text_en' => $ten !== null && trim($ten) !== '' ? $ten : null,
                ]);
            }
        });

        PpsContent::flush();
    }
}

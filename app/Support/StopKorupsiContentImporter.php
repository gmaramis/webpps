<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\StopKorupsiBullet;
use App\Models\StopKorupsiPageContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use JsonException;

final class StopKorupsiContentImporter
{
    /**
     * Mengisi ulang konten halaman Stop Korupsi dari resources/data/pps-content.json
     * (STRINGS + STOP_KORUPSI_BULLETS). Menghapus semua poin di basis data lalu menyisipkan ulang.
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
                $idStr['stopKorupsiLead'] ?? '',
                $idStr['stopKorupsiP1'] ?? '',
                $idStr['stopKorupsiP2'] ?? '',
            ]);
            $simpleEn = $joinBodies([
                $enStr['stopKorupsiLead'] ?? '',
                $enStr['stopKorupsiP1'] ?? '',
                $enStr['stopKorupsiP2'] ?? '',
            ]);

            $page = StopKorupsiPageContent::singleton();
            $page->update([
                'eyebrow_id' => (string) ($idStr['stopKorupsiEyebrow'] ?? ''),
                'eyebrow_en' => isset($enStr['stopKorupsiEyebrow']) ? (string) $enStr['stopKorupsiEyebrow'] : null,
                'title_id' => (string) ($idStr['stopKorupsiTitle'] ?? ''),
                'title_en' => isset($enStr['stopKorupsiTitle']) ? (string) $enStr['stopKorupsiTitle'] : null,
                'lead_id' => (string) ($idStr['stopKorupsiLead'] ?? ''),
                'lead_en' => isset($enStr['stopKorupsiLead']) ? (string) $enStr['stopKorupsiLead'] : null,
                'p1_id' => (string) ($idStr['stopKorupsiP1'] ?? ''),
                'p1_en' => isset($enStr['stopKorupsiP1']) ? (string) $enStr['stopKorupsiP1'] : null,
                'p2_id' => (string) ($idStr['stopKorupsiP2'] ?? ''),
                'p2_en' => isset($enStr['stopKorupsiP2']) ? (string) $enStr['stopKorupsiP2'] : null,
                'simple_body_id' => $simpleId,
                'simple_body_en' => $simpleEn,
                'bullets_title_id' => (string) ($idStr['stopKorupsiBulletsTitle'] ?? ''),
                'bullets_title_en' => isset($enStr['stopKorupsiBulletsTitle']) ? (string) $enStr['stopKorupsiBulletsTitle'] : null,
                'cta_title_id' => (string) ($idStr['stopKorupsiCtaTitle'] ?? ''),
                'cta_title_en' => isset($enStr['stopKorupsiCtaTitle']) ? (string) $enStr['stopKorupsiCtaTitle'] : null,
                'cta_p_id' => (string) ($idStr['stopKorupsiCtaP'] ?? ''),
                'cta_p_en' => isset($enStr['stopKorupsiCtaP']) ? (string) $enStr['stopKorupsiCtaP'] : null,
                'link_instrumen_zi_label_id' => (string) ($idStr['stopLinkInstrumenZi'] ?? ''),
                'link_instrumen_zi_label_en' => isset($enStr['stopLinkInstrumenZi']) ? (string) $enStr['stopLinkInstrumenZi'] : null,
                'link_span_lapor_label_id' => (string) ($idStr['stopLinkSpanLapor'] ?? ''),
                'link_span_lapor_label_en' => isset($enStr['stopLinkSpanLapor']) ? (string) $enStr['stopLinkSpanLapor'] : null,
                'link_span_lapor_url' => 'https://spanlapor.kemenpan.go.id/',
            ]);

            StopKorupsiBullet::query()->delete();

            $bullets = $data['STOP_KORUPSI_BULLETS'] ?? [];
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

                StopKorupsiBullet::query()->create([
                    'sort_order' => (int) $i,
                    'text_id' => $tid,
                    'text_en' => $ten !== null && trim($ten) !== '' ? $ten : null,
                ]);
            }
        });

        PpsContent::flush();
    }
}

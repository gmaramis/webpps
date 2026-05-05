<?php

namespace App\Support;

use App\Models\ZiComplaintChannel;
use App\Models\ZiGalleryItem;
use App\Models\ZiPageIntro;
use App\Models\ZiPillar;
use App\Models\ZiUpdateItem;
use Illuminate\Support\Facades\File;
use JsonException;

class ZiContentImporter
{
    /**
     * Impor ZI_* dari pps-content.json + teks pengantar dari STRINGS. Mengosongkan tabel ZI dan mengisi ulang.
     *
     * @throws JsonException
     */
    public static function importFromPpsContentJson(): void
    {
        $path = resource_path('data/pps-content.json');
        if (! File::exists($path)) {
            return;
        }

        $decoded = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        ZiPageIntro::withoutEvents(function () use ($decoded): void {
            ZiPageIntro::query()->delete();
            $idStr = $decoded['STRINGS']['id'] ?? [];
            $enStr = $decoded['STRINGS']['en'] ?? [];
            if (! is_array($idStr)) {
                return;
            }
            ZiPageIntro::query()->create([
                'intro_heading_id' => (string) ($idStr['ziIntroHeading'] ?? ''),
                'intro_heading_en' => isset($enStr['ziIntroHeading']) ? (string) $enStr['ziIntroHeading'] : null,
                'intro_p1_id' => (string) ($idStr['ziIntroP1'] ?? ''),
                'intro_p1_en' => isset($enStr['ziIntroP1']) ? (string) $enStr['ziIntroP1'] : null,
                'intro_p2_id' => (string) ($idStr['ziIntroP2'] ?? ''),
                'intro_p2_en' => isset($enStr['ziIntroP2']) ? (string) $enStr['ziIntroP2'] : null,
            ]);
        });

        ZiPillar::withoutEvents(function () use ($decoded): void {
            ZiPillar::query()->delete();
            $rows = $decoded['ZI_PILLARS'] ?? [];
            if (! is_array($rows)) {
                return;
            }
            foreach (array_values($rows) as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $title = $row['title'] ?? [];
                $desc = $row['desc'] ?? [];
                ZiPillar::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'desc_id' => (string) ($desc['id'] ?? ''),
                    'desc_en' => isset($desc['en']) ? (string) $desc['en'] : null,
                ]);
            }
        });

        ZiGalleryItem::withoutEvents(function () use ($decoded): void {
            foreach (ZiGalleryItem::query()->pluck('image') as $imgPath) {
                ZiGalleryItem::deleteStoredUpload($imgPath);
            }
            ZiGalleryItem::query()->delete();
            $rows = $decoded['ZI_GALLERY'] ?? [];
            if (! is_array($rows)) {
                return;
            }
            foreach (array_values($rows) as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $imageAlt = $row['imageAlt'] ?? [];
                $caption = $row['caption'] ?? [];
                ZiGalleryItem::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'image' => isset($row['image']) ? (string) $row['image'] : 'news/cover-1.svg',
                    'image_alt_id' => (string) ($imageAlt['id'] ?? ''),
                    'image_alt_en' => isset($imageAlt['en']) ? (string) $imageAlt['en'] : null,
                    'caption_id' => (string) ($caption['id'] ?? ''),
                    'caption_en' => isset($caption['en']) ? (string) $caption['en'] : null,
                ]);
            }
        });

        ZiComplaintChannel::withoutEvents(function () use ($decoded): void {
            ZiComplaintChannel::query()->delete();
            $rows = $decoded['ZI_COMPLAINT_CHANNELS'] ?? [];
            if (! is_array($rows)) {
                return;
            }
            foreach (array_values($rows) as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $title = $row['title'] ?? [];
                $summary = $row['summary'] ?? [];
                ZiComplaintChannel::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'summary_id' => (string) ($summary['id'] ?? ''),
                    'summary_en' => isset($summary['en']) ? (string) $summary['en'] : null,
                    'href' => isset($row['href']) ? (string) $row['href'] : '#',
                    'external' => ! empty($row['external']),
                ]);
            }
        });

        ZiUpdateItem::withoutEvents(function () use ($decoded): void {
            ZiUpdateItem::query()->delete();
            $rows = $decoded['ZI_UPDATES'] ?? [];
            if (! is_array($rows)) {
                return;
            }
            foreach (array_values($rows) as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $title = $row['title'] ?? [];
                $date = $row['dateISO'] ?? $row['date_iso'] ?? now()->format('Y-m-d');
                ZiUpdateItem::query()->create([
                    'sort_order' => $index,
                    'is_published' => true,
                    'date_iso' => is_string($date) ? $date : now()->format('Y-m-d'),
                    'title_id' => (string) ($title['id'] ?? ''),
                    'title_en' => isset($title['en']) ? (string) $title['en'] : null,
                    'href' => isset($row['href']) ? (string) $row['href'] : '#',
                ]);
            }
        });

        PpsContent::flush();
    }
}

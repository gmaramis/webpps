<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class NewsHeroImageProcessor
{
    /** Lebih panjang sisi maksimum setelah resize (proporsional). */
    public const MAX_SIDE_PX = 1200;

    /** Target ukuran berkas keluaran WebP (byte). */
    public const OUTPUT_MAX_BYTES = 307200;

    /**
     * Resize gambar ke WebP (sisi terpanjang max {@see MAX_SIDE_PX}), simpan di disk `public`.
     *
     * @throws RuntimeException Jika GD/WebP tidak tersedia atau gambar tidak valid
     */
    public static function storeProcessed(UploadedFile $file): string
    {
        if (! function_exists('imagewebp')) {
            throw new RuntimeException('PHP tidak mendukung WebP (aktifkan ekstensi gd dengan dukungan WebP).');
        }

        $binary = $file->getContent();
        $src = @imagecreatefromstring($binary);
        if ($src === false) {
            throw new RuntimeException('Berkas gambar tidak dapat dibaca.');
        }

        try {
            $w = imagesx($src);
            $h = imagesy($src);
            if ($w < 1 || $h < 1) {
                throw new RuntimeException('Dimensi gambar tidak valid.');
            }

            $nw = $w;
            $nh = $h;
            $maxSide = self::MAX_SIDE_PX;
            if ($w > $maxSide || $h > $maxSide) {
                if ($w >= $h) {
                    $nw = $maxSide;
                    $nh = max(1, (int) round($h * ($maxSide / $w)));
                } else {
                    $nh = $maxSide;
                    $nw = max(1, (int) round($w * ($maxSide / $h)));
                }
            }

            $dst = imagecreatetruecolor($nw, $nh);
            if ($dst === false) {
                throw new RuntimeException('Gagal menyiapkan gambar keluaran.');
            }

            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
            imagealphablending($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagesavealpha($dst, true);

            $relativePath = 'news/'.Str::uuid()->toString().'.webp';
            $tmpPath = tempnam(sys_get_temp_dir(), 'pps_news');
            if ($tmpPath === false) {
                imagedestroy($dst);

                throw new RuntimeException('Tidak dapat membuat berkas sementara.');
            }

            try {
                $quality = 85;
                do {
                    if (! imagewebp($dst, $tmpPath, $quality)) {
                        throw new RuntimeException('Gagal mengenkode WebP.');
                    }
                    $size = filesize($tmpPath);
                    if ($size !== false && $size <= self::OUTPUT_MAX_BYTES) {
                        break;
                    }
                    $quality -= 6;
                } while ($quality >= 42);

                Storage::disk('public')->put($relativePath, file_get_contents($tmpPath));
            } finally {
                @unlink($tmpPath);
                imagedestroy($dst);
            }

            return $relativePath;
        } finally {
            imagedestroy($src);
        }
    }
}

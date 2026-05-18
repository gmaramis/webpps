<?php

namespace App\Support;

use Illuminate\Support\Str;

final class StudyProgramBlurb
{
    /**
     * Ubah deskripsi prodi menjadi HTML aman untuk ditampilkan di halaman publik.
     */
    public static function toHtml(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        if (self::looksLikeMarkdown($text)) {
            return Str::markdown($text, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
        }

        $paragraphs = preg_split('/\n\s*\n+/', $text) ?: [$text];
        $html = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if ($paragraph === '') {
                continue;
            }
            $lines = e($paragraph);
            $lines = nl2br($lines, false);
            $html .= '<p>'.$lines.'</p>';
        }

        return $html !== '' ? $html : '<p>'.e($text).'</p>';
    }

    private static function looksLikeMarkdown(string $text): bool
    {
        return (bool) preg_match('/^#{1,6}\s|^\s*[\*\-]\s|\*\*|__|\[.+\]\(.+\)/m', $text);
    }
}

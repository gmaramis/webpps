<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Models\VisionMissionContent;
use App\Support\PpsContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpsPageController extends Controller
{
    public function home(): View
    {
        return view('home', ['isHome' => true]);
    }

    public function newsShow(string $locale, string $slug): View
    {
        abort_unless(in_array($locale, ['id', 'en'], true), 404);
        app()->setLocale($locale);

        $post = NewsItem::query()
            ->where('is_published', true)
            ->whereJsonContainsLocale('slug', $locale, $slug)
            ->firstOrFail();

        $sidebarNews = NewsItem::query()
            ->where('is_published', true)
            ->whereKeyNot($post->getKey())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('news.show', [
            'post' => $post,
            'locale' => $locale,
            'sidebarNews' => $sidebarNews,
        ]);
    }

    public function visiMisi(): View
    {
        $blocks = VisionMissionContent::resolvedBlocks(app()->getLocale());

        return view('pages.visi-misi', compact('blocks'));
    }

    public function strukturPimpinan(): View
    {
        return view('pages.struktur-pimpinan');
    }

    public function kerjasama(): View
    {
        return view('pages.kerjasama');
    }

    public function dosen(): View
    {
        return view('pages.dosen');
    }

    public function panduanAkademik(): View
    {
        return view('pages.panduan-akademik');
    }

    public function kalenderAkademik(Request $request): View
    {
        $calendars = PpsContent::all()['ACADEMIC_CALENDARS'] ?? [];
        $requested = $request->query('tahun');
        $active = $requested
            ? collect($calendars)->firstWhere('id', $requested)
            : null;
        if ($active === null && $calendars !== []) {
            $active = $calendars[0];
        }

        return view('pages.kalender-akademik', compact('calendars', 'active'));
    }

    public function kegiatanMahasiswa(): View
    {
        return view('pages.kegiatan-mahasiswa');
    }

    public function kegiatanAlumni(): View
    {
        return view('pages.kegiatan-alumni');
    }

    public function instrumenZonaIntegritas(): View
    {
        return view('pages.instrumen-zona-integritas');
    }

    public function stopKorupsi(): View
    {
        return view('pages.stop-korupsi');
    }

    public function stopGratifikasi(): View
    {
        return view('pages.stop-gratifikasi');
    }

    public function dokumenAkreditasi(): View
    {
        return view('pages.dokumen-akreditasi');
    }

    public function programS2(): View
    {
        return view('pages.program-s2');
    }

    public function programS3(): View
    {
        return view('pages.program-s3');
    }
}

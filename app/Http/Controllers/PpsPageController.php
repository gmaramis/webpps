<?php

namespace App\Http\Controllers;

use App\Models\AcademicGuide;
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
        $guides = PpsContent::all()['ACADEMIC_GUIDES'] ?? [];
        $calendars = AcademicGuide::calendarPageEntriesFromGuideList($guides);
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

    public function programS2(Request $request): View
    {
        $programs = PpsContent::all()['PROGRAMS_MAGISTER'] ?? [];

        return view('pages.program-s2', array_merge(
            ['programs' => $programs],
            $this->resolveStudyProgramSelection($programs, $request)
        ));
    }

    public function programS3(Request $request): View
    {
        $programs = PpsContent::all()['PROGRAMS_DOKTOR'] ?? [];

        return view('pages.program-s3', array_merge(
            ['programs' => $programs],
            $this->resolveStudyProgramSelection($programs, $request)
        ));
    }

    /**
     * @param  list<array<string, mixed>>  $programs
     * @return array{active: array<string, mixed>|null, selectedSlug: string, invalidProgramSelection: bool}
     */
    private function resolveStudyProgramSelection(array $programs, Request $request): array
    {
        $querySlug = trim((string) $request->query('program', ''));
        $active = null;
        $selectedSlug = '';
        $invalidProgramSelection = false;

        if ($programs !== []) {
            if ($querySlug === '') {
                $active = $programs[0];
                $selectedSlug = (string) ($active['slug'] ?? '');
            } else {
                $active = collect($programs)->first(
                    fn (array $p): bool => (string) ($p['slug'] ?? '') === $querySlug
                );
                if ($active !== null) {
                    $selectedSlug = $querySlug;
                } else {
                    $invalidProgramSelection = true;
                    $selectedSlug = $querySlug;
                }
            }
        }

        return compact('active', 'selectedSlug', 'invalidProgramSelection');
    }
}

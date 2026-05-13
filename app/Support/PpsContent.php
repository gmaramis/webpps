<?php

namespace App\Support;

use App\Models\AcademicGuide;
use App\Models\AcademicPortalSetting;
use App\Models\AccreditationDocument;
use App\Models\AgendaItem;
use App\Models\AlumniActivity;
use App\Models\AnnouncementItem;
use App\Models\CooperationPartner;
use App\Models\DirectorGreeting;
use App\Models\GraduateSchoolHistoryContent;
use App\Models\HeroSlide;
use App\Models\HomepageProgramDisplay;
use App\Models\LeadershipPerson;
use App\Models\Lecturer;
use App\Models\NewsItem;
use App\Models\S2Program;
use App\Models\S3Program;
use App\Models\StopGratifikasiBullet;
use App\Models\StopGratifikasiPageContent;
use App\Models\StopKorupsiBullet;
use App\Models\StopKorupsiPageContent;
use App\Models\StudentActivity;
use App\Models\ZiComplaintChannel;
use App\Models\ZiGalleryItem;
use App\Models\ZiPageIntro;
use App\Models\ZiPillar;
use App\Models\ZiUpdateItem;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use JsonException;

class PpsContent
{
    /** @var array<string, mixed>|null */
    protected static ?array $cache = null;

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $path = resource_path('data/pps-content.json');
        if (! File::exists($path)) {
            self::$cache = [
                'ACADEMIC_EXTERNAL_URLS' => AcademicPortalSetting::resolvedUrls(),
            ];

            return self::$cache;
        }

        $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        if (! isset($data['ACCREDITATION_DOCUMENTS']) || ! is_array($data['ACCREDITATION_DOCUMENTS'])) {
            $data['ACCREDITATION_DOCUMENTS'] = [];
        }

        $academicUrls = AcademicPortalSetting::resolvedUrls();
        $data['ACADEMIC_EXTERNAL_URLS'] = $academicUrls;

        if (! empty($data['NAV']) && is_array($data['NAV'])) {
            $data['NAV'] = self::normalizeNav($data['NAV']);
            $data['NAV'] = self::applyAcademicExternalNavHrefs($data['NAV'], $academicUrls);
            $data['NAV'] = self::injectCurriculumNavItemUnderAkademic($data['NAV'], $data['STRINGS'] ?? []);
        }
        $leadersPath = resource_path('data/leaders.json');
        if (File::exists($leadersPath)) {
            $data['LEADERS'] = json_decode(File::get($leadersPath), true, 512, JSON_THROW_ON_ERROR);
        } else {
            $data['LEADERS'] = [];
        }

        $leadersFromDb = self::leadershipFromDatabase();
        if ($leadersFromDb !== null) {
            $data['LEADERS'] = $leadersFromDb;
        }
        $data['SLIDE_IMAGES'] = self::slidesFromDatabase() ?? HeroSlide::BUILTIN_SLIDE_PATHS;

        $magisterHeroJson = $data['MAGISTER_HERO'] ?? null;
        $data['MAGISTER_HERO'] = is_string($magisterHeroJson) && trim($magisterHeroJson) !== ''
            ? ltrim(trim($magisterHeroJson), '/')
            : 'programs/magister-photo.png';

        $doktorHeroJson = $data['DOKTOR_HERO'] ?? null;
        $data['DOKTOR_HERO'] = is_string($doktorHeroJson) && trim($doktorHeroJson) !== ''
            ? ltrim(trim($doktorHeroJson), '/')
            : 'programs/doktor-photo.png';

        $dbHeroPaths = HomepageProgramDisplay::heroPathsFromDatabase();
        if ($dbHeroPaths !== null) {
            if ($dbHeroPaths['magister'] !== null) {
                $data['MAGISTER_HERO'] = $dbHeroPaths['magister'];
            }
            if ($dbHeroPaths['doktor'] !== null) {
                $data['DOKTOR_HERO'] = $dbHeroPaths['doktor'];
            }
        }

        $newsFromDb = self::newsFromDatabase();
        if ($newsFromDb !== null) {
            $data['NEWS'] = $newsFromDb;
        }

        if (empty($data['ANNOUNCEMENTS']) || ! is_array($data['ANNOUNCEMENTS'])) {
            $data['ANNOUNCEMENTS'] = [];
        }
        $announcementsFromDb = self::announcementsFromDatabase();
        if ($announcementsFromDb !== null) {
            $data['ANNOUNCEMENTS'] = $announcementsFromDb;
        }

        if (empty($data['AGENDA']) || ! is_array($data['AGENDA'])) {
            $data['AGENDA'] = [];
        }
        $agendaFromDb = self::agendaFromDatabase();
        if ($agendaFromDb !== null) {
            $data['AGENDA'] = $agendaFromDb;
        }

        if (empty($data['PARTNERS']) || ! is_array($data['PARTNERS'])) {
            $data['PARTNERS'] = [];
        }
        $partnersFromDb = self::partnersFromDatabase();
        if ($partnersFromDb !== null) {
            $data['PARTNERS'] = $partnersFromDb;
        }

        if (empty($data['LECTURERS']) || ! is_array($data['LECTURERS'])) {
            $data['LECTURERS'] = [];
        }
        $lecturersFromDb = self::lecturersFromDatabase();
        if ($lecturersFromDb !== null) {
            $data['LECTURERS'] = $lecturersFromDb;
        }

        if (empty($data['ACADEMIC_GUIDES']) || ! is_array($data['ACADEMIC_GUIDES'])) {
            $data['ACADEMIC_GUIDES'] = [];
        }
        $guidesFromDb = self::academicGuidesFromDatabase();
        if ($guidesFromDb !== null) {
            $data['ACADEMIC_GUIDES'] = $guidesFromDb;
        }

        if (empty($data['PROGRAMS_DOKTOR']) || ! is_array($data['PROGRAMS_DOKTOR'])) {
            $data['PROGRAMS_DOKTOR'] = [];
        }
        $data['PROGRAMS_DOKTOR'] = self::normalizeProgramListFromJson($data['PROGRAMS_DOKTOR']);
        $s3FromDb = self::programsDoktorFromDatabase();
        if ($s3FromDb !== null) {
            $data['PROGRAMS_DOKTOR'] = $s3FromDb;
        }

        if (empty($data['PROGRAMS_MAGISTER']) || ! is_array($data['PROGRAMS_MAGISTER'])) {
            $data['PROGRAMS_MAGISTER'] = [];
        }
        $data['PROGRAMS_MAGISTER'] = self::normalizeProgramListFromJson($data['PROGRAMS_MAGISTER']);
        $s2FromDb = self::programsMagisterFromDatabase();
        if ($s2FromDb !== null) {
            $data['PROGRAMS_MAGISTER'] = $s2FromDb;
        }

        if (empty($data['STUDENT_ACTIVITIES']) || ! is_array($data['STUDENT_ACTIVITIES'])) {
            $data['STUDENT_ACTIVITIES'] = [];
        }
        $studentActivitiesFromDb = self::studentActivitiesFromDatabase();
        if ($studentActivitiesFromDb !== null) {
            $data['STUDENT_ACTIVITIES'] = $studentActivitiesFromDb;
        }

        if (empty($data['ALUMNI_ACTIVITIES']) || ! is_array($data['ALUMNI_ACTIVITIES'])) {
            $data['ALUMNI_ACTIVITIES'] = [];
        }
        $alumniActivitiesFromDb = self::alumniActivitiesFromDatabase();
        if ($alumniActivitiesFromDb !== null) {
            $data['ALUMNI_ACTIVITIES'] = $alumniActivitiesFromDb;
        }

        $ziIntro = self::ziPageIntroFromDatabase();
        if ($ziIntro !== null) {
            $data['ZI_PAGE_INTRO'] = $ziIntro;
        }

        if (empty($data['ZI_PILLARS']) || ! is_array($data['ZI_PILLARS'])) {
            $data['ZI_PILLARS'] = [];
        }
        $ziPillarsDb = self::ziPillarsFromDatabase();
        if ($ziPillarsDb !== null) {
            $data['ZI_PILLARS'] = $ziPillarsDb;
        }

        if (empty($data['ZI_GALLERY']) || ! is_array($data['ZI_GALLERY'])) {
            $data['ZI_GALLERY'] = [];
        }
        $ziGalleryDb = self::ziGalleryFromDatabase();
        if ($ziGalleryDb !== null) {
            $data['ZI_GALLERY'] = $ziGalleryDb;
        }

        if (empty($data['ZI_COMPLAINT_CHANNELS']) || ! is_array($data['ZI_COMPLAINT_CHANNELS'])) {
            $data['ZI_COMPLAINT_CHANNELS'] = [];
        }
        $ziChannelsDb = self::ziComplaintChannelsFromDatabase();
        if ($ziChannelsDb !== null) {
            $data['ZI_COMPLAINT_CHANNELS'] = $ziChannelsDb;
        }

        if (empty($data['ZI_UPDATES']) || ! is_array($data['ZI_UPDATES'])) {
            $data['ZI_UPDATES'] = [];
        }
        $ziUpdatesDb = self::ziUpdatesFromDatabase();
        if ($ziUpdatesDb !== null) {
            $data['ZI_UPDATES'] = $ziUpdatesDb;
        }

        self::mergeStopKorupsiFromDatabase($data);
        self::mergeStopGratifikasiFromDatabase($data);
        self::mergeDirectorGreetingFromDatabase($data);
        self::mergeGraduateSchoolHistoryFromDatabase($data);
        self::mergeAccreditationDocumentsFromDatabase($data);

        self::$cache = $data;

        return self::$cache;
    }

    /**
     * @return array<string, string>
     */
    public static function strings(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $all = self::all();
        $strings = $all['STRINGS'] ?? [];

        return $strings[$locale] ?? $strings['id'] ?? [];
    }

    public static function flush(): void
    {
        self::$cache = null;
    }

    /**
     * URL publik untuk foto sambutan direktur (path publik atau file di disk public).
     */
    public static function directorGreetingPublicPhotoUrl(?string $photo): string
    {
        $photo = trim((string) $photo);
        if ($photo === '') {
            return asset('faculty/faculty-1.svg');
        }
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }
        if (str_starts_with($photo, 'director-greeting/')) {
            return asset('storage/'.$photo);
        }

        return asset(ltrim($photo, '/'));
    }

    /**
     * @param  list<array<string, mixed>>  $nav
     * @return list<array<string, mixed>>
     */
    protected static function normalizeNav(array $nav): array
    {
        foreach ($nav as &$item) {
            if (isset($item['href']) && is_string($item['href'])) {
                $item['href'] = self::normalizeHref($item['href']);
            }
            if (isset($item['children']) && is_array($item['children'])) {
                foreach ($item['children'] as &$child) {
                    if (isset($child['href']) && is_string($child['href'])) {
                        $child['href'] = self::normalizeHref($child['href']);
                    }
                }
                unset($child);
            }
        }
        unset($item);

        return $nav;
    }

    /**
     * Menu Akademik: href Portal / LMS / SPADA diambil dari basis data (bukan hard code di JSON).
     *
     * @param  list<array<string, mixed>>  $nav
     * @param  array{portal: string, lms: string, spada: string}  $urls
     * @return list<array<string, mixed>>
     */
    protected static function applyAcademicExternalNavHrefs(array $nav, array $urls): array
    {
        $map = static function (array $items) use ($urls, &$map): array {
            $out = [];
            foreach ($items as $item) {
                if (! is_array($item)) {
                    $out[] = $item;

                    continue;
                }
                $slot = $item['linkSlot'] ?? null;
                if (is_string($slot) && isset($urls[$slot])) {
                    $item['href'] = $urls[$slot];
                } else {
                    $labelId = is_array($item['label'] ?? null) ? (string) ($item['label']['id'] ?? '') : '';
                    if ($labelId === 'Portal Akademik') {
                        $item['href'] = $urls['portal'];
                    } elseif ($labelId === 'LMS') {
                        $item['href'] = $urls['lms'];
                    } elseif ($labelId === 'SPADA Indonesia') {
                        $item['href'] = $urls['spada'];
                    }
                }
                if (isset($item['children']) && is_array($item['children'])) {
                    $item['children'] = $map($item['children']);
                }
                $out[] = $item;
            }

            return $out;
        };

        return $map($nav);
    }

    /**
     * Sisipkan item menu Kurikulum di bawah Kalender Akademik dalam grup Akademik.
     *
     * @param  list<array<string, mixed>>  $nav
     * @param  array<string, mixed>  $stringsBlock  Isi kunci STRINGS dari JSON (berisi 'id' / 'en').
     * @return list<array<string, mixed>>
     */
    protected static function injectCurriculumNavItemUnderAkademic(array $nav, array $stringsBlock): array
    {
        $locId = is_array($stringsBlock['id'] ?? null) ? $stringsBlock['id'] : [];
        $locEn = is_array($stringsBlock['en'] ?? null) ? $stringsBlock['en'] : [];
        $labelId = trim((string) ($locId['kurikulumNavLabel'] ?? 'Kurikulum'));
        $labelEn = trim((string) ($locEn['kurikulumNavLabel'] ?? 'Curriculum'));
        if ($labelEn === '') {
            $labelEn = $labelId !== '' ? $labelId : 'Curriculum';
        }
        if ($labelId === '') {
            $labelId = 'Kurikulum';
        }

        $child = [
            'label' => ['id' => $labelId, 'en' => $labelEn],
            'href' => url('/kurikulum'),
        ];

        foreach ($nav as &$item) {
            if (! is_array($item)) {
                continue;
            }
            $topId = (string) (($item['label'] ?? [])['id'] ?? '');
            if ($topId !== 'Akademik') {
                continue;
            }
            $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
            foreach ($children as $c) {
                if (! is_array($c)) {
                    continue;
                }
                $h = (string) ($c['href'] ?? '');
                if (str_contains($h, 'kurikulum')) {
                    return $nav;
                }
            }
            $newChildren = [];
            $inserted = false;
            foreach ($children as $c) {
                $newChildren[] = $c;
                if ($inserted || ! is_array($c)) {
                    continue;
                }
                $href = (string) ($c['href'] ?? '');
                $cLabelId = (string) (($c['label'] ?? [])['id'] ?? '');
                if (str_contains($href, 'kalender-akademik') || $cLabelId === 'Kalender Akademik') {
                    $newChildren[] = $child;
                    $inserted = true;
                }
            }
            if (! $inserted) {
                $newChildren[] = $child;
            }
            $item['children'] = $newChildren;
            break;
        }
        unset($item);

        return $nav;
    }

    protected static function normalizeHref(string $href): string
    {
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }
        if ($href === '#/' || $href === '#') {
            return url('/');
        }
        if (str_starts_with($href, '#/')) {
            $path = '/'.ltrim(substr($href, 2), '/');

            return url($path);
        }

        return $href;
    }

    /**
     * Pimpinan dari basis data admin — bentuk sama seperti leaders.json untuk blade publik.
     *
     * @return list<array<string, mixed>>|null null jika tabel kosong / tidak ada
     */
    protected static function leadershipFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('leadership_people')) {
                return null;
            }

            $people = LeadershipPerson::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($people->isEmpty()) {
                return null;
            }

            return $people->map(fn (LeadershipPerson $p): array => $p->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Mitra kerjasama dari DB — bentuk sama seperti PARTNERS di pps-content.json.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function partnersFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('cooperation_partners')) {
                return null;
            }

            $rows = CooperationPartner::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (CooperationPartner $p): array => $p->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Dosen dari DB — bentuk sama seperti LECTURERS di pps-content.json.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function lecturersFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('lecturers')) {
                return null;
            }

            $rows = Lecturer::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (Lecturer $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Panduan akademik dari DB — bentuk sama seperti ACADEMIC_GUIDES di pps-content.json.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function academicGuidesFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('academic_guides')) {
                return null;
            }

            $rows = AcademicGuide::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (AcademicGuide $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Slug & official_url untuk daftar program dari JSON (S2 / S3, sebelum override basis data).
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    protected static function normalizeProgramListFromJson(array $rows): array
    {
        $used = [];

        return collect($rows)->filter(fn ($row): bool => is_array($row))->values()->map(function (array $row) use (&$used): array {
            $name = $row['name'] ?? [];
            $nameId = (string) ($name['id'] ?? '');
            $base = Str::slug($nameId);
            if ($base === '') {
                $base = 'program';
            }

            $slug = trim((string) ($row['slug'] ?? ''));
            if ($slug === '') {
                $slug = $base;
                $n = 2;
                while (isset($used[$slug])) {
                    $slug = $base.'-'.$n;
                    $n++;
                }
            } else {
                $orig = $slug;
                $n = 2;
                while (isset($used[$slug])) {
                    $slug = $orig.'-'.$n;
                    $n++;
                }
            }
            $used[$slug] = true;
            $row['slug'] = $slug;

            $url = $row['official_url'] ?? null;
            $row['official_url'] = is_string($url) && trim($url) !== '' ? trim($url) : null;

            $excerpt = $row['excerpt'] ?? [];
            if (is_array($excerpt)) {
                $exId = trim((string) ($excerpt['id'] ?? ''));
                $exEn = isset($excerpt['en']) ? trim((string) $excerpt['en']) : '';
                $row['excerpt'] = [
                    'id' => $exId,
                    'en' => $exEn !== '' ? $exEn : $exId,
                ];
            } else {
                $row['excerpt'] = ['id' => '', 'en' => ''];
            }

            return $row;
        })->all();
    }

    /**
     * Program doktor (S3) dari DB — bentuk sama seperti PROGRAMS_DOKTOR di pps-content.json + slug & official_url.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function programsDoktorFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('s3_programs')) {
                return null;
            }

            $rows = S3Program::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (S3Program $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Program magister (S2) dari DB — bentuk sama seperti PROGRAMS_MAGISTER di pps-content.json + slug & official_url.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function programsMagisterFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('s2_programs')) {
                return null;
            }

            $rows = S2Program::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (S2Program $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Kegiatan mahasiswa dari DB — bentuk sama seperti STUDENT_ACTIVITIES di pps-content.json.
     * Hanya baris is_published = true yang dikirim ke halaman publik.
     *
     * @return list<array<string, mixed>>|null null jika tabel kosong / tidak ada; array (boleh kosong) jika ada data di tabel
     */
    protected static function studentActivitiesFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('student_activities')) {
                return null;
            }

            if (! StudentActivity::query()->exists()) {
                return null;
            }

            $rows = StudentActivity::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            return $rows->map(fn (StudentActivity $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Kegiatan alumni dari DB — bentuk sama seperti ALUMNI_ACTIVITIES di pps-content.json.
     * Hanya baris is_published = true yang dikirim ke halaman publik.
     *
     * @return list<array<string, mixed>>|null null jika tabel kosong / tidak ada; array (boleh kosong) jika ada data di tabel
     */
    protected static function alumniActivitiesFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('alumni_activities')) {
                return null;
            }

            if (! AlumniActivity::query()->exists()) {
                return null;
            }

            $rows = AlumniActivity::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            return $rows->map(fn (AlumniActivity $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Stop Korupsi: dari stop_korupsi_page_contents. Jika simple_body_id terisi, halaman publik memakai satu blok isi
     * (STOP_KORUPSI_SIMPLE); jika tidak, menimpa STRINGS per kunci seperti sebelumnya. Daftar poin dari basis data jika ada.
     *
     * @param  array<string, mixed>  $data
     */
    protected static function mergeStopKorupsiFromDatabase(array &$data): void
    {
        try {
            if (! Schema::hasTable('stop_korupsi_page_contents')) {
                return;
            }

            $page = StopKorupsiPageContent::query()->first();
            if ($page !== null) {
                $simpleBodyId = trim((string) ($page->simple_body_id ?? ''));
                $useSimple = $simpleBodyId !== '';

                if ($useSimple) {
                    $enBody = trim((string) ($page->simple_body_en ?? ''));
                    $data['STOP_KORUPSI_SIMPLE'] = [
                        'body' => [
                            'id' => $simpleBodyId,
                            'en' => $enBody !== '' ? $enBody : $simpleBodyId,
                        ],
                    ];
                } else {
                    unset($data['STOP_KORUPSI_SIMPLE']);
                }

                $map = [
                    'stopKorupsiEyebrow' => ['id' => $page->eyebrow_id, 'en' => $page->eyebrow_en],
                    'stopKorupsiTitle' => ['id' => $page->title_id, 'en' => $page->title_en],
                    'stopKorupsiLead' => ['id' => $page->lead_id, 'en' => $page->lead_en],
                    'stopKorupsiP1' => ['id' => $page->p1_id, 'en' => $page->p1_en],
                    'stopKorupsiP2' => ['id' => $page->p2_id, 'en' => $page->p2_en],
                    'stopKorupsiBulletsTitle' => ['id' => $page->bullets_title_id, 'en' => $page->bullets_title_en],
                    'stopKorupsiCtaTitle' => ['id' => $page->cta_title_id, 'en' => $page->cta_title_en],
                    'stopKorupsiCtaP' => ['id' => $page->cta_p_id, 'en' => $page->cta_p_en],
                    'stopLinkInstrumenZi' => ['id' => $page->link_instrumen_zi_label_id, 'en' => $page->link_instrumen_zi_label_en],
                    'stopLinkSpanLapor' => ['id' => $page->link_span_lapor_label_id, 'en' => $page->link_span_lapor_label_en],
                ];

                $skipWhenSimple = [
                    'stopKorupsiLead',
                    'stopKorupsiP1',
                    'stopKorupsiP2',
                    'stopKorupsiCtaTitle',
                    'stopKorupsiCtaP',
                ];

                foreach (['id', 'en'] as $loc) {
                    if (! isset($data['STRINGS'][$loc]) || ! is_array($data['STRINGS'][$loc])) {
                        $data['STRINGS'][$loc] = [];
                    }
                }

                foreach ($map as $strKey => $pair) {
                    if ($useSimple && in_array($strKey, $skipWhenSimple, true)) {
                        continue;
                    }
                    $idVal = trim((string) ($pair['id'] ?? ''));
                    if ($idVal !== '') {
                        $data['STRINGS']['id'][$strKey] = $idVal;
                    }
                    $enVal = $pair['en'] ?? null;
                    $enTrim = is_string($enVal) ? trim($enVal) : '';
                    if ($enTrim !== '') {
                        $data['STRINGS']['en'][$strKey] = $enTrim;
                    }
                }

                $url = $page->link_span_lapor_url;
                if (is_string($url) && trim($url) !== '') {
                    $data['STOP_KORUPSI_SPAN_LAPOR_URL'] = trim($url);
                }
            }

            if (! Schema::hasTable('stop_korupsi_bullets')) {
                return;
            }

            if (! StopKorupsiBullet::query()->exists()) {
                return;
            }

            $rows = StopKorupsiBullet::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $data['STOP_KORUPSI_BULLETS'] = $rows
                ->map(fn (StopKorupsiBullet $b): array => $b->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            //
        }
    }

    /**
     * Stop Gratifikasi: dari stop_gratifikasi_page_contents. Jika simple_body_id terisi, halaman publik memakai satu blok isi
     * (STOP_GRATIFIKASI_SIMPLE); jika tidak, menimpa STRINGS per kunci seperti JSON. Tombol utama boleh pakai STOP_GRATIFIKASI_INSTRUMEN_URL.
     *
     * @param  array<string, mixed>  $data
     */
    protected static function mergeStopGratifikasiFromDatabase(array &$data): void
    {
        try {
            if (! Schema::hasTable('stop_gratifikasi_page_contents')) {
                return;
            }

            $page = StopGratifikasiPageContent::query()->first();
            if ($page !== null) {
                $simpleBodyId = trim((string) ($page->simple_body_id ?? ''));
                $useSimple = $simpleBodyId !== '';

                if ($useSimple) {
                    $enBody = trim((string) ($page->simple_body_en ?? ''));
                    $data['STOP_GRATIFIKASI_SIMPLE'] = [
                        'body' => [
                            'id' => $simpleBodyId,
                            'en' => $enBody !== '' ? $enBody : $simpleBodyId,
                        ],
                    ];
                } else {
                    unset($data['STOP_GRATIFIKASI_SIMPLE']);
                }

                $map = [
                    'stopGratifikasiEyebrow' => ['id' => $page->eyebrow_id, 'en' => $page->eyebrow_en],
                    'stopGratifikasiTitle' => ['id' => $page->title_id, 'en' => $page->title_en],
                    'stopGratifikasiLead' => ['id' => $page->lead_id, 'en' => $page->lead_en],
                    'stopGratifikasiP1' => ['id' => $page->p1_id, 'en' => $page->p1_en],
                    'stopGratifikasiP2' => ['id' => $page->p2_id, 'en' => $page->p2_en],
                    'stopGratifikasiBulletsTitle' => ['id' => $page->bullets_title_id, 'en' => $page->bullets_title_en],
                    'stopGratifikasiCtaTitle' => ['id' => $page->cta_title_id, 'en' => $page->cta_title_en],
                    'stopGratifikasiCtaP' => ['id' => $page->cta_p_id, 'en' => $page->cta_p_en],
                    'stopGratifikasiLinkZi' => ['id' => $page->link_instrumen_zi_label_id, 'en' => $page->link_instrumen_zi_label_en],
                ];

                $skipWhenSimple = [
                    'stopGratifikasiLead',
                    'stopGratifikasiP1',
                    'stopGratifikasiP2',
                    'stopGratifikasiCtaTitle',
                    'stopGratifikasiCtaP',
                ];

                foreach (['id', 'en'] as $loc) {
                    if (! isset($data['STRINGS'][$loc]) || ! is_array($data['STRINGS'][$loc])) {
                        $data['STRINGS'][$loc] = [];
                    }
                }

                foreach ($map as $strKey => $pair) {
                    if ($useSimple && in_array($strKey, $skipWhenSimple, true)) {
                        continue;
                    }
                    $idVal = trim((string) ($pair['id'] ?? ''));
                    if ($idVal !== '') {
                        $data['STRINGS']['id'][$strKey] = $idVal;
                    }
                    $enVal = $pair['en'] ?? null;
                    $enTrim = is_string($enVal) ? trim($enVal) : '';
                    if ($enTrim !== '') {
                        $data['STRINGS']['en'][$strKey] = $enTrim;
                    }
                }

                $ziUrl = $page->link_instrumen_zi_url;
                if (is_string($ziUrl) && trim($ziUrl) !== '') {
                    $data['STOP_GRATIFIKASI_INSTRUMEN_URL'] = trim($ziUrl);
                }
            }

            if (! Schema::hasTable('stop_gratifikasi_bullets')) {
                return;
            }

            if (! StopGratifikasiBullet::query()->exists()) {
                return;
            }

            $rows = StopGratifikasiBullet::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $data['STOP_GRATIFIKASI_BULLETS'] = $rows
                ->map(fn (StopGratifikasiBullet $b): array => $b->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            //
        }
    }

    /**
     * Sambutan direktur dan label bagian terkait di beranda.
     *
     * @param  array<string, mixed>  $data
     */
    protected static function mergeDirectorGreetingFromDatabase(array &$data): void
    {
        try {
            if (! Schema::hasTable('director_greetings')) {
                return;
            }

            $row = DirectorGreeting::query()->find(1);
            if ($row === null) {
                return;
            }

            $data['DIRECTOR_GREETING'] = $row->toPpsContentDirectorBlock();

            $data['STRINGS'] ??= [];
            $data['STRINGS']['id'] ??= [];
            $data['STRINGS']['en'] ??= [];
            $data['STRINGS']['id']['directorGreetingEyebrow'] = (string) ($row->section_eyebrow_id ?? '');
            $data['STRINGS']['en']['directorGreetingEyebrow'] = (string) ($row->section_eyebrow_en ?? '');
            $data['STRINGS']['id']['directorGreetingTitle'] = (string) ($row->section_title_id ?? '');
            $data['STRINGS']['en']['directorGreetingTitle'] = (string) ($row->section_title_en ?? '');
            $data['STRINGS']['id']['directorGreetingQuoteLabel'] = (string) ($row->section_quote_label_id ?? '');
            $data['STRINGS']['en']['directorGreetingQuoteLabel'] = (string) ($row->section_quote_label_en ?? '');
        } catch (\Throwable) {
            //
        }
    }

    /**
     * Blok sejarah di beranda — menimpa kunci STRINGS history* dari basis data jika baris id=1 ada.
     *
     * @param  array<string, mixed>  $data
     */
    protected static function mergeGraduateSchoolHistoryFromDatabase(array &$data): void
    {
        try {
            if (! Schema::hasTable('graduate_school_history_contents')) {
                return;
            }
            if (! Schema::hasColumn('graduate_school_history_contents', 'paragraph_id')) {
                return;
            }

            $row = GraduateSchoolHistoryContent::query()->find(1);
            if ($row === null) {
                return;
            }

            $data['STRINGS'] ??= [];
            $data['STRINGS']['id'] ??= [];
            $data['STRINGS']['en'] ??= [];

            $pairs = [
                'historyEyebrow' => ['eyebrow_id', 'eyebrow_en'],
                'historyTitle' => ['title_id', 'title_en'],
                'historyParagraph' => ['paragraph_id', 'paragraph_en'],
            ];

            foreach ($pairs as $strKey => [$idCol, $enCol]) {
                $idVal = trim((string) ($row->{$idCol} ?? ''));
                $enRaw = trim((string) ($row->{$enCol} ?? ''));
                $data['STRINGS']['id'][$strKey] = $idVal;
                $data['STRINGS']['en'][$strKey] = $enRaw !== '' ? $enRaw : $idVal;
            }

            if (Schema::hasColumn('graduate_school_history_contents', 'image_path')) {
                $img = trim((string) ($row->image_path ?? ''));
                $data['GRADUATE_SCHOOL_HISTORY_IMAGE'] = $img !== '' ? GraduateSchoolHistoryContent::publicImageUrl($img) : null;
            }
        } catch (\Throwable) {
            //
        }
    }

    /**
     * Dokumen akreditasi dari basis data menggantikan daftar statis — hanya PDF yang is_published = true.
     *
     * @param  array<string, mixed>  $data
     */
    protected static function mergeAccreditationDocumentsFromDatabase(array &$data): void
    {
        try {
            if (! Schema::hasTable('accreditation_documents')) {
                return;
            }

            $rows = AccreditationDocument::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $data['ACCREDITATION_DOCUMENTS'] = $rows
                ->map(fn (AccreditationDocument $doc): array => $doc->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            //
        }
    }

    protected static function ziPageIntroFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('zi_page_intros')) {
                return null;
            }

            $row = ZiPageIntro::query()->first();
            if ($row === null) {
                return null;
            }
            if (trim($row->intro_heading_id) === '' && trim($row->intro_p1_id) === '' && trim($row->intro_p2_id) === '') {
                return null;
            }

            return $row->toPpsIntroArray();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>|null
     */
    protected static function ziPillarsFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('zi_pillars')) {
                return null;
            }
            if (! ZiPillar::query()->exists()) {
                return null;
            }

            return ZiPillar::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (ZiPillar $row): array => $row->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>|null
     */
    protected static function ziGalleryFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('zi_gallery_items')) {
                return null;
            }
            if (! ZiGalleryItem::query()->exists()) {
                return null;
            }

            return ZiGalleryItem::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (ZiGalleryItem $row): array => $row->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>|null
     */
    protected static function ziComplaintChannelsFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('zi_complaint_channels')) {
                return null;
            }
            if (! ZiComplaintChannel::query()->exists()) {
                return null;
            }

            return ZiComplaintChannel::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (ZiComplaintChannel $row): array => $row->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>|null
     */
    protected static function ziUpdatesFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('zi_update_items')) {
                return null;
            }
            if (! ZiUpdateItem::query()->exists()) {
                return null;
            }

            return ZiUpdateItem::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (ZiUpdateItem $row): array => $row->toFrontArray())
                ->values()
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Slideshow beranda dari DB — susunan string path gambar seperti SLIDE_IMAGES.
     *
     * @return list<string>|null null jika kosong / tabel belum ada → pakai HeroSlide::BUILTIN_SLIDE_PATHS
     */
    protected static function slidesFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('hero_slides')) {
                return null;
            }

            $slides = HeroSlide::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($slides->isEmpty()) {
                return null;
            }

            return $slides->map(fn (HeroSlide $s): string => (string) $s->image)->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Pengumuman dari DB — bentuk sama seperti ANNOUNCEMENTS di pps-content.json.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function announcementsFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('announcement_items')) {
                return null;
            }

            $rows = AnnouncementItem::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderByDesc('date_iso')
                ->orderByDesc('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (AnnouncementItem $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Agenda dari DB — bentuk sama seperti AGENDA di pps-content.json.
     *
     * @return list<array<string, mixed>>|null null jika kosong atau tabel tidak ada
     */
    protected static function agendaFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('agenda_items')) {
                return null;
            }

            $rows = AgendaItem::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                return null;
            }

            return $rows->map(fn (AgendaItem $row): array => $row->toFrontArray())->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * NEWS dari DB (admin) bentuknya sama seperti entri NEWS di JSON agar konsisten dengan home.blade.php.
     *
     * @return list<array<string, mixed>>|null null jika tabel kosong/error — pakai NEWS dari JSON
     */
    protected static function newsFromDatabase(): ?array
    {
        try {
            if (! Schema::hasTable('news_items')) {
                return null;
            }

            $items = NewsItem::query()
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get();

            if ($items->isEmpty()) {
                return null;
            }

            return $items->map(fn (NewsItem $item): array => self::newsItemToFrontArray($item))->values()->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected static function newsItemToFrontArray(NewsItem $item): array
    {
        $image = $item->resolvedNewsImagePath();
        $slugId = (string) ($item->getTranslationWithoutFallback('slug', 'id') ?? '');
        $slugEn = (string) ($item->getTranslationWithoutFallback('slug', 'en') ?? '');
        $href = [
            'id' => $slugId !== '' ? route('news.show', ['locale' => 'id', 'slug' => $slugId], false) : '#',
            'en' => $slugEn !== '' ? route('news.show', ['locale' => 'en', 'slug' => $slugEn], false) : '#',
        ];

        return [
            'id' => (string) $item->getKey(),
            'date' => $item->published_at?->format('Y-m-d')
                ?? $item->created_at?->format('Y-m-d')
                ?? now()->format('Y-m-d'),
            'title' => $item->translationsForFrontend('title'),
            'excerpt' => $item->translationsForFrontend('excerpt'),
            'href' => ($item->href !== null && $item->href !== '' && $item->href !== '#')
                ? $item->href
                : $href,
            'location' => $item->translationsForFrontend('location'),
            'image' => $image,
            'imageAlt' => $item->translationsForFrontend('title'),
            'category' => $item->translationsForFrontend('category'),
        ];
    }

    public static function formatAnnouncementDate(string $iso, string $locale): string
    {
        try {
            $c = Carbon::parse($iso)->locale($locale === 'en' ? 'en_GB' : 'id_ID');

            return strtoupper($c->translatedFormat('d F Y'));
        } catch (\Throwable) {
            return $iso;
        }
    }

    /** Rentang waktu sejak tayang sampai sekarang (untuk halaman detail berita). */
    public static function newsPublishedAgeHuman(?CarbonInterface $publishedAt, string $locale): string
    {
        if ($publishedAt === null) {
            return '';
        }

        $tz = config('app.timezone') ?: 'UTC';
        $pub = Carbon::parse($publishedAt)->timezone($tz);

        $isoLocale = $locale === 'en' ? 'en' : 'id';

        return $pub->copy()->locale($isoLocale)->diffForHumans();
    }
}

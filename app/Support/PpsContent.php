<?php

namespace App\Support;

use App\Models\AgendaItem;
use App\Models\AnnouncementItem;
use App\Models\CooperationPartner;
use App\Models\HeroSlide;
use App\Models\LeadershipPerson;
use App\Models\Lecturer;
use App\Models\NewsItem;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
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
            self::$cache = [];

            return self::$cache;
        }

        $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        if (! empty($data['NAV']) && is_array($data['NAV'])) {
            $data['NAV'] = self::normalizeNav($data['NAV']);
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
        $data['MAGISTER_HERO'] = 'programs/magister-photo.png';
        $data['DOKTOR_HERO'] = 'programs/doktor-photo.png';

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

@php
    $navLocale = 'id';
    $navRoot = $ppsData['NAV'] ?? [];
    $newsCounts = $adminSidebarNewsCounts ?? ['total' => 0, 'published' => 0, 'draft' => 0];
    $can = static fn (string $permission): bool => auth()->user()?->can($permission) ?? false;
    $canManageUsers = auth()->user()?->can('users.manage') ?? false;

    /** Di panel admin, tautan menu situs yang sama dengan halaman CMS mengarah ke backend, bukan URL publik. */
    $adminSidebarHref = static function (string $href): string {
        $path = parse_url($href, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return $href;
        }
        if (str_contains($path, '/admin')) {
            return $href;
        }
        $trim = rtrim($path, '/');
        if (str_ends_with($trim, '/visi-misi')) {
            return route('admin.visi-misi.edit');
        }
        if (str_ends_with($trim, '/struktur-pimpinan')) {
            return route('admin.struktur-pimpinan.index');
        }
        if (str_ends_with($trim, '/kerjasama')) {
            return route('admin.kerjasama.index');
        }
        if (str_ends_with($trim, '/dosen')) {
            return route('admin.dosen.index');
        }
        if (str_ends_with($trim, '/panduan-akademik')) {
            return route('admin.panduan-akademik.index');
        }
        if (str_ends_with($trim, '/s2')) {
            return route('admin.prodi-s2.index');
        }
        if (str_ends_with($trim, '/s3')) {
            return route('admin.prodi-s3.index');
        }
        if (str_ends_with($trim, '/kurikulum')) {
            return route('admin.kurikulum.index');
        }
        if (str_ends_with($trim, '/pengumuman')) {
            return route('admin.pengumuman.index');
        }
        if (str_ends_with($trim, '/agenda')) {
            return route('admin.agenda.index');
        }
        if (str_ends_with($trim, '/kegiatan-mahasiswa')) {
            return route('admin.kegiatan-mahasiswa.index');
        }
        if (str_ends_with($trim, '/kegiatan-alumni')) {
            return route('admin.kegiatan-alumni.index');
        }
        if (str_ends_with($trim, '/instrumen-zona-integritas')) {
            return route('admin.zi.hub');
        }
        if (str_ends_with($trim, '/stop-korupsi')) {
            return route('admin.stop-korupsi.hub');
        }
        if (str_ends_with($trim, '/stop-gratifikasi')) {
            return route('admin.stop-gratifikasi.hub');
        }
        if (str_ends_with($trim, '/dokumen-akreditasi')) {
            return route('admin.dokumen-akreditasi.index');
        }

        return $href;
    };

    $permissionFromPath = static function (string $path): ?string {
        $trim = rtrim($path, '/');

        return match (true) {
            str_ends_with($trim, '/visi-misi') => 'visi-misi.manage',
            str_ends_with($trim, '/struktur-pimpinan') => 'struktur-pimpinan.manage',
            str_ends_with($trim, '/kerjasama') => 'kerjasama.manage',
            str_ends_with($trim, '/dosen') => 'dosen.manage',
            str_ends_with($trim, '/panduan-akademik') => 'panduan-akademik.manage',
            str_ends_with($trim, '/s2') => 'prodi-s2.manage',
            str_ends_with($trim, '/s3') => 'prodi-s3.manage',
            str_ends_with($trim, '/kurikulum') => 'kurikulum.manage',
            str_ends_with($trim, '/pengumuman') => 'pengumuman.manage',
            str_ends_with($trim, '/agenda') => 'agenda.manage',
            str_ends_with($trim, '/kegiatan-mahasiswa') => 'kegiatan-mahasiswa.manage',
            str_ends_with($trim, '/kegiatan-alumni') => 'kegiatan-alumni.manage',
            str_ends_with($trim, '/instrumen-zona-integritas') => 'zi.manage',
            str_ends_with($trim, '/stop-korupsi') => 'stop-korupsi.manage',
            str_ends_with($trim, '/stop-gratifikasi') => 'stop-gratifikasi.manage',
            str_ends_with($trim, '/dokumen-akreditasi') => 'dokumen-akreditasi.manage',
            default => null,
        };
    };

    /** URL absolut ke luar backend admin → tab baru (path mengandung segmen /admin/ diabaikan). */
    $sidebarOpenExternal = static function (string $href): bool {
        if (! \Illuminate\Support\Str::startsWith($href, ['http://', 'https://'])) {
            return false;
        }
        $path = parse_url($href, PHP_URL_PATH) ?: '';

        return ! str_contains($path, '/admin');
    };
@endphp

<style>
    .admin-sidebar-details > summary {
        list-style: none;
    }
    .admin-sidebar-details > summary::-webkit-details-marker {
        display: none;
    }
    .admin-sidebar-chevron {
        transition: transform 0.2s ease;
    }
    .admin-sidebar-details[open] > summary .admin-sidebar-chevron {
        transform: rotate(180deg);
    }
    /* Override hover agar selalu terlihat abu-abu gelap di sidebar admin. */
    .admin-sidebar-details > summary:hover {
        background-color: #94a3b8 !important; /* slate-400 */
        color: #020617 !important; /* slate-950 */
    }
    .admin-sidebar-details a:hover {
        background-color: #94a3b8 !important; /* slate-400 */
        color: #020617 !important; /* slate-950 */
    }
    .admin-sidebar-top-link:hover {
        background-color: #94a3b8 !important; /* slate-400 */
        border-color: #475569 !important; /* slate-600 */
        color: #020617 !important; /* slate-950 */
    }
</style>

@php
    $navActive = fn (bool $cond): string => $cond
        ? 'bg-gradient-to-r from-primary/[0.1] via-sky-500/[0.07] to-transparent font-semibold text-primary shadow-inner shadow-white/30 ring-1 ring-primary/[0.12]'
        : 'text-slate-600 ring-1 ring-transparent hover:bg-slate-400 hover:text-slate-950 hover:shadow-sm';
@endphp

<nav class="flex min-h-0 flex-1 flex-col gap-1 overflow-y-auto px-2.5 py-2 text-[13px] font-medium" aria-label="Menu admin dan tautan situs">
    <p class="mb-1.5 px-3 pt-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Admin</p>

    <details open class="admin-sidebar-details overflow-hidden rounded-2xl border border-slate-200/70 bg-white/70 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm">
        <summary class="flex cursor-pointer items-center gap-2 rounded-t-2xl px-3.5 py-3 text-[13px] font-bold tracking-tight text-slate-800 transition-colors duration-200 ease-out hover:bg-slate-400">
            @include('admin.layouts.sidebar-icon', ['name' => 'panel', 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
            <span class="min-w-0 flex-1 text-left">Panel</span>
            <svg class="admin-sidebar-chevron h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
        </summary>
        <div class="space-y-1 border-t border-slate-100/90 bg-white/95 p-2">
            @if ($can('dashboard.view'))
                <a href="{{ route('admin.dashboard') }}" class="{{ $navActive(request()->routeIs('admin.dashboard')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'dashboard', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Dasbor</span>
                </a>
            @endif
            @if ($can('news.manage'))
                <a href="{{ route('admin.news.index') }}" class="{{ $navActive(request()->routeIs('admin.news.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'newspaper', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Berita</span>
                    @if(($newsCounts['total'] ?? 0) > 0)
                        <span class="flex h-5 min-w-[1.35rem] shrink-0 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-bold text-white shadow-sm shadow-primary/30">{{ $newsCounts['total'] > 99 ? '99+' : $newsCounts['total'] }}</span>
                    @endif
                </a>
            @endif
            @if ($can('slideshow.manage'))
                <a href="{{ route('admin.slideshow.index') }}" class="{{ $navActive(request()->routeIs('admin.slideshow.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'photo', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Slideshow beranda</span>
                </a>
            @endif
            @if ($can('program-heroes.manage'))
                <a href="{{ route('admin.program-heroes.edit') }}" class="{{ $navActive(request()->routeIs('admin.program-heroes.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'photo', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Hero program beranda</span>
                </a>
            @endif
            @if ($can('director-greeting.manage') || $can('slideshow.manage') || $can('program-heroes.manage'))
                <a href="{{ route('admin.director-greeting.edit') }}" class="{{ $navActive(request()->routeIs('admin.director-greeting.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'teacher', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Sambutan direktur</span>
                </a>
            @endif
            @if ($can('beranda-sejarah.manage'))
                <a href="{{ route('admin.beranda-sejarah.edit') }}" class="{{ $navActive(request()->routeIs('admin.beranda-sejarah.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'organization', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Sejarah beranda</span>
                </a>
            @endif
            @if ($can('pengumuman.manage'))
                <a href="{{ route('admin.pengumuman.index') }}" class="{{ $navActive(request()->routeIs('admin.pengumuman.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'megaphone', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Pengumuman</span>
                </a>
            @endif
            @if ($can('agenda.manage'))
                <a href="{{ route('admin.agenda.index') }}" class="{{ $navActive(request()->routeIs('admin.agenda.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'calendar', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Agenda</span>
                </a>
            @endif
            @if ($can('profile.manage'))
                <a href="{{ route('admin.profile.edit') }}" class="{{ $navActive(request()->routeIs('admin.profile.edit')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'account', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Profil akun</span>
                </a>
                <a href="{{ route('admin.profile.password.edit') }}" class="{{ $navActive(request()->routeIs('admin.profile.password.edit')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'lock', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Ubah kata sandi</span>
                </a>
            @endif
            @if ($canManageUsers)
                <a href="{{ route('admin.users.index') }}" class="{{ $navActive(request()->routeIs('admin.users.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-200 ease-out">
                    @include('admin.layouts.sidebar-icon', ['name' => 'users', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                    <span class="min-w-0 flex-1">Kelola user</span>
                </a>
            @endif
        </div>
    </details>

    <div class="my-3 mx-3 border-t border-slate-200/80"></div>

    <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Menu utama situs</p>

    @foreach ($navRoot as $navItem)
        @php
            $topLabel = $navItem['label'][$navLocale] ?? $navItem['label']['id'] ?? $navItem['label']['en'] ?? '';
            $topKey = $navItem['label']['id'] ?? '';
            $groupIcon = match ($topKey) {
                'Profil' => 'profil',
                'Akademik' => 'akademik',
                'Program Studi' => 'prodi',
                'Kemahasiswaan' => 'mahasiswa',
                'Unit Penjamin Mutu' => 'mutu',
                'Akreditasi' => 'akreditasi',
                default => 'folder',
            };
        @endphp
        @if (! empty($navItem['children']) && is_array($navItem['children']))
            <details class="admin-sidebar-details overflow-hidden rounded-2xl border border-slate-200/70 bg-white/65 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm" @if ($topKey === 'Akademik' && (request()->routeIs('admin.tautan-portal-akademik.*') || request()->routeIs('admin.kurikulum.*'))) open @endif @if ($topKey === 'Program Studi' && (request()->routeIs('admin.prodi-s2.*') || request()->routeIs('admin.prodi-s3.*') || request()->routeIs('admin.program-heroes.*'))) open @endif @if ($topKey === 'Unit Penjamin Mutu' && (request()->routeIs('admin.stop-korupsi.*') || request()->routeIs('admin.stop-gratifikasi.*'))) open @endif>
                <summary class="flex cursor-pointer items-center gap-2 px-3.5 py-3 text-[13px] font-bold tracking-tight text-slate-800 transition-colors duration-200 ease-out hover:bg-slate-400">
                    @include('admin.layouts.sidebar-icon', ['name' => $groupIcon, 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
                    <span class="min-w-0 flex-1 text-left leading-snug">{{ $topLabel }}</span>
                    <svg class="admin-sidebar-chevron h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <div class="border-t border-slate-100/90 bg-white/95 py-1.5">
                    @foreach ($navItem['children'] as $child)
                        @php
                            $slot = $child['linkSlot'] ?? null;
                            $labelId = (string) ($child['label']['id'] ?? '');
                            $skipExternalAcademicLinks = is_string($slot) && in_array($slot, ['portal', 'lms', 'spada'], true)
                                || ($topKey === 'Akademik' && in_array($labelId, ['Portal Akademik', 'LMS', 'SPADA Indonesia'], true));
                        @endphp
                        @if ($skipExternalAcademicLinks)
                            @continue
                        @endif
                        @php
                            $childLabel = $child['label'][$navLocale] ?? $child['label']['id'] ?? $child['label']['en'] ?? '';
                            $href = $adminSidebarHref((string) ($child['href'] ?? '#'));
                            $childExternal = $sidebarOpenExternal($href);
                            $path = parse_url($href, PHP_URL_PATH) ?: '';
                            $childPermission = $permissionFromPath($path);
                        @endphp
                        @if ($childPermission && ! $can($childPermission))
                            @continue
                        @endif
                        <a href="{{ $href }}" @if ($childExternal) target="_blank" rel="noopener noreferrer" @endif class="mx-1 flex items-center gap-2 rounded-xl px-3 py-2 pl-9 text-[13px] font-medium text-slate-600 transition-all duration-200 ease-out hover:bg-slate-400 hover:text-slate-950 hover:shadow-sm">
                            @if ($childExternal)
                                @include('admin.layouts.sidebar-icon', ['name' => 'link', 'class' => 'h-3.5 w-3.5 shrink-0 opacity-70'])
                            @else
                                <span class="h-1 w-1 shrink-0 rounded-full bg-slate-300" aria-hidden="true"></span>
                            @endif
                            <span class="min-w-0 flex-1">{{ $childLabel }}</span>
                        </a>
                    @endforeach
                    @if ($topKey === 'Akademik' && $can('tautan-portal-akademik.manage'))
                        <a href="{{ route('admin.tautan-portal-akademik.edit') }}" class="{{ $navActive(request()->routeIs('admin.tautan-portal-akademik.*')) }} mx-1 flex items-center gap-2 rounded-xl px-3 py-2 pl-9 text-[13px] font-medium text-slate-600 transition-all duration-200 ease-out hover:bg-slate-400 hover:text-slate-950 hover:shadow-sm">
                            @include('admin.layouts.sidebar-icon', ['name' => 'link', 'class' => 'h-3.5 w-3.5 shrink-0 opacity-70'])
                            <span class="min-w-0 flex-1">Tautan portal akademik</span>
                        </a>
                    @endif
                </div>
            </details>
        @elseif (! empty($navItem['href']))
            @php
                $href = $adminSidebarHref((string) $navItem['href']);
                $topExternal = $sidebarOpenExternal($href);
            @endphp
            <a href="{{ $href }}" @if ($topExternal) target="_blank" rel="noopener noreferrer" @endif class="admin-sidebar-top-link flex items-center gap-2 rounded-2xl border border-slate-200/70 bg-white/90 px-3.5 py-2.5 text-[13px] font-semibold text-slate-800 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm transition-all duration-200 ease-out hover:border-slate-600 hover:bg-slate-400 hover:text-slate-950">
                @include('admin.layouts.sidebar-icon', ['name' => 'home', 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
                <span class="min-w-0 flex-1 text-left">{{ $topLabel }}</span>
                @if ($topExternal)
                    @include('admin.layouts.sidebar-icon', ['name' => 'link', 'class' => 'h-3.5 w-3.5 shrink-0 opacity-70'])
                @endif
            </a>
        @endif
    @endforeach
</nav>

@php
    $navLocale = 'id';
    $navRoot = $ppsData['NAV'] ?? [];
    $newsCounts = $adminSidebarNewsCounts ?? ['total' => 0, 'published' => 0, 'draft' => 0];

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
        if (str_ends_with($trim, '/pengumuman')) {
            return route('admin.pengumuman.index');
        }
        if (str_ends_with($trim, '/agenda')) {
            return route('admin.agenda.index');
        }

        return $href;
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
</style>

@php
    $navActive = fn (bool $cond): string => $cond
        ? 'bg-gradient-to-r from-primary/[0.1] via-sky-500/[0.07] to-transparent font-semibold text-primary shadow-inner shadow-white/30 ring-1 ring-primary/[0.12]'
        : 'text-slate-600 ring-1 ring-transparent hover:bg-white/90 hover:text-slate-900 hover:shadow-sm';
@endphp

<nav class="flex min-h-0 flex-1 flex-col gap-1 overflow-y-auto px-2.5 py-2 text-[13px] font-medium" aria-label="Menu admin dan tautan situs">
    <p class="mb-1.5 px-3 pt-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Admin</p>

    <details open class="admin-sidebar-details overflow-hidden rounded-2xl border border-slate-200/70 bg-white/70 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm">
        <summary class="flex cursor-pointer items-center gap-2 rounded-t-2xl px-3.5 py-3 text-[13px] font-bold tracking-tight text-slate-800 hover:bg-white/80">
            @include('admin.layouts.sidebar-icon', ['name' => 'panel', 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
            <span class="min-w-0 flex-1 text-left">Panel</span>
            <svg class="admin-sidebar-chevron h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
        </summary>
        <div class="space-y-1 border-t border-slate-100/90 bg-white/95 p-2">
            <a href="{{ route('admin.dashboard') }}" class="{{ $navActive(request()->routeIs('admin.dashboard')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'dashboard', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Dasbor</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="{{ $navActive(request()->routeIs('admin.news.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'newspaper', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Berita</span>
                @if(($newsCounts['total'] ?? 0) > 0)
                    <span class="flex h-5 min-w-[1.35rem] shrink-0 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-bold text-white shadow-sm shadow-primary/30">{{ $newsCounts['total'] > 99 ? '99+' : $newsCounts['total'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.slideshow.index') }}" class="{{ $navActive(request()->routeIs('admin.slideshow.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'photo', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Slideshow beranda</span>
            </a>
            <a href="{{ route('admin.pengumuman.index') }}" class="{{ $navActive(request()->routeIs('admin.pengumuman.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'megaphone', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Pengumuman</span>
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="{{ $navActive(request()->routeIs('admin.agenda.*')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'calendar', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Agenda</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ $navActive(request()->routeIs('admin.profile.edit')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'account', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Profil akun</span>
            </a>
            <a href="{{ route('admin.profile.password.edit') }}" class="{{ $navActive(request()->routeIs('admin.profile.password.edit')) }} mx-0.5 flex items-center gap-2.5 rounded-xl px-2.5 py-2 transition">
                @include('admin.layouts.sidebar-icon', ['name' => 'lock', 'class' => 'h-4 w-4 shrink-0 opacity-90'])
                <span class="min-w-0 flex-1">Ubah kata sandi</span>
            </a>
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
            <details class="admin-sidebar-details overflow-hidden rounded-2xl border border-slate-200/70 bg-white/65 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm">
                <summary class="flex cursor-pointer items-center gap-2 px-3.5 py-3 text-[13px] font-bold tracking-tight text-slate-800 hover:bg-white/90">
                    @include('admin.layouts.sidebar-icon', ['name' => $groupIcon, 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
                    <span class="min-w-0 flex-1 text-left leading-snug">{{ $topLabel }}</span>
                    <svg class="admin-sidebar-chevron h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <div class="border-t border-slate-100/90 bg-white/95 py-1.5">
                    @foreach ($navItem['children'] as $child)
                        @php
                            $childLabel = $child['label'][$navLocale] ?? $child['label']['id'] ?? $child['label']['en'] ?? '';
                            $href = $adminSidebarHref((string) ($child['href'] ?? '#'));
                            $childExternal = $sidebarOpenExternal($href);
                        @endphp
                        <a href="{{ $href }}" @if ($childExternal) target="_blank" rel="noopener noreferrer" @endif class="mx-1 flex items-center gap-2 rounded-xl px-3 py-2 pl-9 text-[13px] font-medium text-slate-600 transition hover:bg-white hover:text-primary hover:shadow-sm">
                            @if ($childExternal)
                                @include('admin.layouts.sidebar-icon', ['name' => 'link', 'class' => 'h-3.5 w-3.5 shrink-0 opacity-70'])
                            @else
                                <span class="h-1 w-1 shrink-0 rounded-full bg-slate-300" aria-hidden="true"></span>
                            @endif
                            <span class="min-w-0 flex-1">{{ $childLabel }}</span>
                        </a>
                    @endforeach
                </div>
            </details>
        @elseif (! empty($navItem['href']))
            @php
                $href = $adminSidebarHref((string) $navItem['href']);
                $topExternal = $sidebarOpenExternal($href);
            @endphp
            <a href="{{ $href }}" @if ($topExternal) target="_blank" rel="noopener noreferrer" @endif class="flex items-center gap-2 rounded-2xl border border-slate-200/70 bg-white/90 px-3.5 py-2.5 text-[13px] font-semibold text-slate-800 shadow-md shadow-slate-900/[0.03] backdrop-blur-sm transition hover:border-sky-200/80 hover:bg-white hover:text-primary">
                @include('admin.layouts.sidebar-icon', ['name' => 'home', 'class' => 'h-4 w-4 shrink-0 text-slate-500'])
                <span class="min-w-0 flex-1 text-left">{{ $topLabel }}</span>
                @if ($topExternal)
                    @include('admin.layouts.sidebar-icon', ['name' => 'link', 'class' => 'h-3.5 w-3.5 shrink-0 opacity-70'])
                @endif
            </a>
        @endif
    @endforeach
</nav>

@extends('admin.layouts.app')

@section('title', 'Dasbor')
@section('heading', 'Dasbor')

@section('content')
<div class="mx-auto max-w-7xl space-y-7">
    <div class="grid gap-6 lg:grid-cols-3 lg:gap-7">
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-primary to-sky-800 p-6 text-white shadow-2xl shadow-primary/25 ring-1 ring-white/10 lg:col-span-2 lg:p-9">
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-sky-400/25 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-16 left-1/4 h-56 w-56 rounded-full bg-teal-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20width=%2240%22%20height=%2240%22%20viewBox=%220%200%2040%2040%22%3E%3Cg%20fill=%22%23fff%22%20fill-opacity=%220.03%22%3E%3Cpath%20d=%22M0%200h40v40H0z%22/%3E%3C/g%3E%3C/svg%3E')] opacity-90" aria-hidden="true"></div>
            <div class="relative">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-200/90">Selamat datang kembali</p>
                <h1 class="mt-2 font-display-admin text-2xl font-bold tracking-tight md:text-3xl lg:text-[1.85rem]">{{ Auth::user()->name }}</h1>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-sky-100/95 md:text-[15px]">
                    Kelola berita dan konten situs dari satu panel. Ringkasan aktivitas ada di bawah.
                </p>
                <div class="mt-7 flex flex-wrap gap-2.5">
                    <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-sm font-bold text-primary shadow-lg shadow-slate-900/20 transition hover:bg-sky-50 hover:shadow-xl">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Berita baru
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                        Semua berita
                    </a>
                    <a href="{{ route('admin.visi-misi.edit') }}" class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                        <svg class="h-5 w-5 opacity-95" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Visi &amp; Misi
                    </a>
                </div>
            </div>
        </section>

        <aside class="flex flex-col rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/60 backdrop-blur-md md:p-6">
            <div class="flex items-start justify-between gap-2 border-b border-slate-100/90 pb-3">
                <h2 class="font-display-admin text-base font-bold tracking-tight text-slate-900">Berita terbaru</h2>
                <a href="{{ route('admin.news.index') }}" class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-primary transition hover:bg-sky-100">Semua</a>
            </div>
            @if($recentNews->isEmpty())
                <p class="mt-5 flex-1 text-sm leading-relaxed text-slate-500">Belum ada entri.</p>
            @else
                <ul class="mt-4 flex min-h-0 flex-1 flex-col gap-1">
                    @foreach($recentNews as $article)
                        @php
                            $titleId = $article->getTranslation('title', 'id') ?: $article->getTranslation('title', 'en') ?: 'Tanpa judul';
                            $excerpt = $article->getTranslation('excerpt', 'id') ?: '';
                            $statusLabel = $article->is_published ? 'Terbit' : 'Draf';
                            $dateRef = $article->published_at ?? $article->updated_at;
                            $dateStr = $dateRef ? $dateRef->translatedFormat('d M Y') : '—';
                        @endphp
                        <li>
                            <a href="{{ route('admin.news.edit', $article) }}" class="group block rounded-2xl border border-transparent p-2 -m-1 transition hover:border-slate-200/90 hover:bg-slate-50/90">
                                <span class="line-clamp-2 text-[13px] font-semibold text-primary transition group-hover:underline">{{ $titleId }}</span>
                                @if($excerpt !== '')
                                    <span class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-slate-500">{{ Str::limit(strip_tags($excerpt), 95) }}</span>
                                @endif
                                <div class="mt-2 flex items-center justify-between gap-2 text-[11px] font-medium text-slate-400">
                                    <span class="rounded-full {{ $article->is_published ? 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200/70' : 'bg-amber-100 text-amber-900 ring-1 ring-amber-200/70' }} px-2 py-0.5">{{ $statusLabel }}</span>
                                    <time datetime="{{ $dateRef?->toIso8601String() }}" class="font-medium">{{ $dateStr }}</time>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 sm:gap-5 xl:grid-cols-4">
        @php
            $statCards = [
                ['label' => 'Total berita', 'value' => $newsStats['total'], 'sub' => 'Semua entri', 'tone' => 'sky', 'icon' => 'newspaper'],
                ['label' => 'Terbit', 'value' => $newsStats['published'], 'sub' => 'Di situs publik', 'tone' => 'teal', 'icon' => 'check'],
                ['label' => 'Draf', 'value' => $newsStats['draft'], 'sub' => 'Belum tayang', 'tone' => 'amber', 'icon' => 'draft'],
                ['label' => 'Pengumuman aktif bulan ini', 'value' => $announcementMonthlyPublished, 'sub' => 'Published', 'tone' => 'sky', 'icon' => 'megaphone'],
                ['label' => 'Agenda aktif bulan ini', 'value' => $agendaMonthlyPublished, 'sub' => 'Published', 'tone' => 'teal', 'icon' => 'calendar'],
                ['label' => 'Notifikasi', 'value' => $translationNotifyUnread, 'sub' => 'Belum dibaca', 'tone' => 'rose', 'icon' => 'bell'],
            ];
        @endphp
        @foreach($statCards as $sc)
            @php
                $tone = $sc['tone'];
                $wrap = match ($tone) {
                    'sky' => 'from-sky-50/90 to-white ring-sky-200/60',
                    'teal' => 'from-teal-50/90 to-white ring-teal-200/60',
                    'amber' => 'from-amber-50/90 to-white ring-amber-200/60',
                    'rose' => 'from-rose-50/90 to-white ring-rose-200/60',
                    default => 'from-slate-50 to-white ring-slate-200/60',
                };
                $iconBg = match ($tone) {
                    'sky' => 'bg-sky-100 text-primary',
                    'teal' => 'bg-teal-100 text-teal-800',
                    'amber' => 'bg-amber-100 text-amber-900',
                    'rose' => 'bg-rose-100 text-rose-800',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp
            <article class="group rounded-3xl border-0 bg-gradient-to-br {{ $wrap }} p-5 shadow-lg shadow-slate-900/[0.04] ring-1 transition duration-200 hover:-translate-y-0.5 hover:shadow-xl md:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">{{ $sc['label'] }}</p>
                        <p class="mt-1 font-display-admin text-3xl font-bold tracking-tight text-slate-900 md:text-4xl">{{ $sc['value'] }}</p>
                    </div>
                    @if($sc['icon'] === 'newspaper')
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-11.25h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5"/></svg>
                        </span>
                    @elseif($sc['icon'] === 'check')
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    @elseif($sc['icon'] === 'draft')
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </span>
                    @elseif($sc['icon'] === 'megaphone')
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 3.94c3.33-.82 6.66 1.68 7.48 5.58l.31 1.5c.82 3.9-1.2 7.74-4.52 8.56l-5.74 1.41a1.5 1.5 0 01-1.82-1.09l-1.9-7.72a1.5 1.5 0 011.1-1.82l5.1-1.26zM14.5 6.5l3.5-.86M15.5 10.5l3.5-.86M6.5 14.75l-1.05 3.97a1 1 0 001.9.5l1.01-3.82"/></svg>
                        </span>
                    @elseif($sc['icon'] === 'calendar')
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 5.25h15A1.5 1.5 0 0121 6.75v12A1.5 1.5 0 0119.5 20.25h-15A1.5 1.5 0 013 18.75v-12A1.5 1.5 0 014.5 5.25z"/></svg>
                        </span>
                    @else
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} shadow-inner transition group-hover:scale-105">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0"/></svg>
                        </span>
                    @endif
                </div>
                <p class="mt-2 text-xs font-medium text-slate-600/90">{{ $sc['sub'] }}</p>
            </article>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3 lg:gap-7">
        <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-md lg:col-span-2" aria-labelledby="dash-notify-heading">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100/90 bg-gradient-to-r from-slate-50/80 to-white px-5 py-4 md:px-6">
                <div>
                    <h2 id="dash-notify-heading" class="font-display-admin text-base font-bold tracking-tight text-slate-900">Aktivitas terjemahan</h2>
                </div>
                @if($translationNotifyUnread > 0)
                    <span class="rounded-full bg-gradient-to-r from-primary to-primary-light px-3 py-1 text-xs font-bold text-white shadow-md shadow-primary/25">{{ $translationNotifyUnread }} baru</span>
                @endif
            </div>
            @if($translationNotifyRecent->isEmpty())
                <p class="px-5 py-12 text-center text-sm text-slate-500 md:px-6">Belum ada notifikasi terjemahan.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[320px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                <th class="px-5 py-3 md:px-6">Status</th>
                                <th class="px-5 py-3 md:px-6">Judul</th>
                                <th class="hidden px-5 py-3 sm:table-cell md:px-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/90">
                            @foreach($translationNotifyRecent as $n)
                                @php
                                    $d = $n->data;
                                    $kind = $d['kind'] ?? '';
                                    $title = $d['title_preview'] ?? '';
                                    $isFail = $kind === 'failed';
                                    $isUnread = $n->read_at === null;
                                @endphp
                                <tr class="{{ $isUnread ? 'bg-sky-50/50' : '' }} transition hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-5 py-3 md:px-6">
                                        @if($isUnread)
                                            <span class="mr-1.5 inline-block rounded-md bg-sky-600 px-1.5 py-0.5 text-[10px] font-bold uppercase text-white">Baru</span>
                                        @endif
                                        <span class="text-xs font-semibold {{ $isFail ? 'text-rose-700' : 'text-emerald-700' }}">{{ $isFail ? 'Gagal' : 'Siap' }}</span>
                                    </td>
                                    <td class="max-w-[12rem] truncate px-5 py-3 text-slate-700 md:max-w-none md:px-6" title="{{ $title !== '' ? $title : 'Berita #'.($d['news_id'] ?? '') }}">{{ $title !== '' ? $title : 'Berita #'.($d['news_id'] ?? '') }}</td>
                                    <td class="hidden px-5 py-3 sm:table-cell md:px-6">
                                        <form method="post" action="{{ route('admin.notifications.read', $n->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Buka</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100/90 px-5 py-3 md:px-6">
                    <p class="text-center text-[11px] text-slate-500">Gunakan <strong>Buka</strong> untuk menandai dibaca.</p>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', ($t['ziPageTitle'] ?? 'Zona Integritas').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $pillars = $ppsData['ZI_PILLARS'] ?? [];
    $gallery = $ppsData['ZI_GALLERY'] ?? [];
    $channels = $ppsData['ZI_COMPLAINT_CHANNELS'] ?? [];
    $updates = $ppsData['ZI_UPDATES'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['ziPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['ziPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['ziPageLead'] }}</p>
        </header>

        <section class="mb-12 rounded-2xl border border-sky-100 bg-white p-6 shadow-sm md:p-8">
            <h2 class="font-display text-xl font-bold text-primary">{{ $t['ziIntroHeading'] }}</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-700 md:text-base">{{ $t['ziIntroP1'] }}</p>
            <p class="mt-4 text-sm leading-relaxed text-slate-700 md:text-base">{{ $t['ziIntroP2'] }}</p>
        </section>

        <section class="mb-12">
            <h2 class="font-display text-xl font-bold text-primary">{{ $t['ziPillarsTitle'] }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $t['ziPillarsLead'] }}</p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($pillars as $p)
                    @php
                        $title = $p['title'][$loc] ?? $p['title']['id'] ?? '';
                        $desc = $p['desc'][$loc] ?? $p['desc']['id'] ?? '';
                    @endphp
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="font-display text-base font-bold text-primary">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $desc }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mb-12">
            <h2 class="font-display text-xl font-bold text-primary">{{ $t['ziGalleryTitle'] }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $t['ziGalleryLead'] }}</p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($gallery as $g)
                    @php
                        $src = asset(ltrim($g['image'] ?? '', '/'));
                        $alt = $g['imageAlt'][$loc] ?? $g['imageAlt']['id'] ?? '';
                        $cap = $g['caption'][$loc] ?? $g['caption']['id'] ?? '';
                    @endphp
                    <figure class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="aspect-[4/3] bg-slate-100">
                            <img src="{{ $src }}" alt="{{ $alt }}" class="h-full w-full object-cover" loading="lazy">
                        </div>
                        <figcaption class="p-3 text-xs text-slate-700">{{ $cap }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </section>

        <section class="mb-12">
            <h2 class="font-display text-xl font-bold text-primary">{{ $t['ziComplaintTitle'] }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $t['ziComplaintLead'] }}</p>
            <ul class="mt-6 space-y-3">
                @foreach($channels as $ch)
                    @php
                        $title = $ch['title'][$loc] ?? $ch['title']['id'] ?? '';
                        $sum = $ch['summary'][$loc] ?? $ch['summary']['id'] ?? '';
                        $href = $ch['href'] ?? '#';
                        $ext = ! empty($ch['external']);
                    @endphp
                    <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <a href="{{ $href }}" class="font-display text-base font-bold text-primary hover:underline" @if($ext) target="_blank" rel="noopener noreferrer" @endif>{{ $title }}</a>
                        <p class="mt-2 text-sm text-slate-600">{{ $sum }}</p>
                    </li>
                @endforeach
            </ul>
            <p class="mt-6">
                <a href="https://spanlapor.kemenpan.go.id/" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">{{ $t['ziCtaReport'] }}</a>
            </p>
        </section>

        <section>
            <h2 class="font-display text-xl font-bold text-primary">{{ $t['ziUpdatesTitle'] }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $t['ziUpdatesLead'] }}</p>
            <ul class="mt-4 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">
                @foreach($updates as $u)
                    @php
                        $title = $u['title'][$loc] ?? $u['title']['id'] ?? '';
                        $date = \App\Support\PpsContent::formatAnnouncementDate($u['dateISO'] ?? '', $loc);
                    @endphp
                    <li class="flex flex-col gap-1 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ $u['href'] ?? '#' }}" class="font-medium text-slate-900 hover:text-primary">{{ $title }}</a>
                        <time class="shrink-0 text-xs font-semibold uppercase text-slate-500">{{ $date }}</time>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</main>
@endsection

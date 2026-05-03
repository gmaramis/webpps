@extends('layouts.app')

@section('title', ($t['studentActivitiesTitle'] ?? 'Kegiatan Mahasiswa').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $items = $ppsData['STUDENT_ACTIVITIES'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="activities-page-head-reveal mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['studentActivitiesEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['studentActivitiesTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['studentActivitiesLead'] }}</p>
        </header>

        <p class="activities-grid-label-reveal mb-4 text-sm font-medium text-slate-700">{{ $t['studentActivitiesGridLabel'] }}</p>
        <div class="pps-activity-list grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-pps-activities-grid data-pps-activities-delay="300">
            @foreach($items as $it)
                @php
                    $img = asset(ltrim($it['image'] ?? '', '/'));
                    $alt = $it['imageAlt'][$loc] ?? $it['imageAlt']['id'] ?? '';
                    $title = $it['title'][$loc] ?? $it['title']['id'] ?? '';
                    $ex = $it['excerpt'][$loc] ?? $it['excerpt']['id'] ?? '';
                @endphp
                <article class="pps-activity-card activities-card-surface overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="{{ $img }}" alt="{{ $alt }}" class="activities-card-img h-full w-full object-cover" width="640" height="400" loading="lazy" decoding="async">
                    </div>
                    <div class="p-4">
                        <h2 class="font-display text-lg font-bold text-primary">{{ $title }}</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $ex }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</main>
@endsection

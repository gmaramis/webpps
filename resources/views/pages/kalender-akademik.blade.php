@extends('layouts.app')

@section('title', ($t['calendarPageTitle'] ?? 'Kalender').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $calendars = $calendars ?? [];
    $active = $active ?? null;
    $pdfUrl = $active && ! empty($active['file']) ? asset(ltrim($active['file'], '/')) : null;
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-8 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['calendarPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['calendarPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['calendarPageLead'] }}</p>
        </header>

        @if(count($calendars))
            <form method="get" action="{{ route('kalender-akademik') }}" class="mb-6 flex flex-wrap items-end gap-4">
                <label class="text-sm font-medium text-slate-700">
                    <span class="mb-1 block">{{ $t['calendarAcademicYearLabel'] }}</span>
                    <select name="tahun" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" onchange="this.form.submit()">
                        @foreach($calendars as $c)
                            @php $yl = $c['yearLabel'][$loc] ?? $c['yearLabel']['id'] ?? $c['id']; @endphp
                            <option value="{{ $c['id'] }}" @selected(($active['id'] ?? '') === ($c['id'] ?? ''))>{{ $yl }}</option>
                        @endforeach
                    </select>
                </label>
            </form>

            @if($pdfUrl)
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                    <p class="border-b border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-800">{{ $t['calendarPreviewLabel'] }}</p>
                    <div class="aspect-[4/3] w-full min-h-[480px] md:aspect-auto md:min-h-[720px]">
                        <iframe title="PDF" src="{{ $pdfUrl }}" class="h-full w-full min-h-[480px] border-0 md:min-h-[720px]"></iframe>
                    </div>
                    <div class="flex flex-wrap gap-3 border-t border-slate-200 bg-white px-4 py-3">
                        <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50">{{ $t['calendarOpenNewTab'] }}</a>
                        <a href="{{ $pdfUrl }}" download class="inline-flex rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">{{ $t['calendarDownloadBtn'] }}</a>
                    </div>
                </section>
            @endif
        @else
            <p class="text-slate-600">{{ $loc === 'id' ? 'Belum ada data kalender.' : 'No calendar data yet.' }}</p>
        @endif
    </div>
</main>
@endsection

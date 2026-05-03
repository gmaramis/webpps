@extends('layouts.app')

@section('title', ($t['guidePageTitle'] ?? 'Panduan').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $guides = $ppsData['ACADEMIC_GUIDES'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['guidePageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['guidePageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['guidePageLead'] }}</p>
        </header>

        <ul class="space-y-3">
            @foreach($guides as $g)
                @php
                    $name = $g['name'][$loc] ?? $g['name']['id'] ?? '';
                    $href = asset(ltrim($g['file'] ?? '', '/'));
                @endphp
                <li class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between md:p-5">
                    <div>
                        <p class="font-medium text-slate-900">{{ $name }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $g['file'] ?? '' }}</p>
                    </div>
                    <a href="{{ $href }}" download class="inline-flex shrink-0 items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-dark">{{ $t['guideDownloadBtn'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</main>
@endsection

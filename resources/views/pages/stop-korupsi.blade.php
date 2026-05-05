@extends('layouts.app')

@section('title', ($t['stopKorupsiTitle'] ?? 'Stop Korupsi').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $bullets = $ppsData['STOP_KORUPSI_BULLETS'] ?? [];
    $spanLaporUrl = $ppsData['STOP_KORUPSI_SPAN_LAPOR_URL'] ?? 'https://spanlapor.kemenpan.go.id/';
    $simple = $ppsData['STOP_KORUPSI_SIMPLE'] ?? null;
    $simpleParas = [];
    if (is_array($simple) && isset($simple['body']) && is_array($simple['body'])) {
        $raw = trim((string) ($simple['body'][$loc] ?? $simple['body']['id'] ?? ''));
        if ($raw !== '') {
            $chunks = preg_split('/\R{2,}/u', $raw);
            $simpleParas = array_values(array_filter(array_map(
                static fn (string $s): string => trim($s),
                is_array($chunks) ? $chunks : []
            )));
        }
    }
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['stopKorupsiEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['stopKorupsiTitle'] }}</h1>
            @if(count($simpleParas) > 0)
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{!! nl2br(e($simpleParas[0])) !!}</p>
            @else
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['stopKorupsiLead'] }}</p>
            @endif
        </header>

        @if(count($simpleParas) > 1)
            <div class="max-w-3xl">
                @foreach(array_slice($simpleParas, 1) as $i => $para)
                    <p class="{{ $i > 0 ? 'mt-4 ' : '' }}text-base leading-relaxed text-slate-700">{!! nl2br(e($para)) !!}</p>
                @endforeach
            </div>
        @elseif(count($simpleParas) === 0)
            <div class="max-w-3xl">
                <p class="text-base leading-relaxed text-slate-700">{{ $t['stopKorupsiP1'] }}</p>
                <p class="mt-4 text-base leading-relaxed text-slate-700">{{ $t['stopKorupsiP2'] }}</p>
            </div>
        @endif

        <section class="mt-10 rounded-2xl border border-rose-100 bg-rose-50/40 p-6 md:p-8">
            <h2 class="font-display text-lg font-bold text-primary">{{ $t['stopKorupsiBulletsTitle'] }}</h2>
            <ul class="mt-4 list-disc space-y-2 pl-5 text-sm text-slate-800 md:text-base">
                @foreach($bullets as $b)
                    <li>{{ $b['text'][$loc] ?? $b['text']['id'] ?? '' }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <h2 class="font-display text-lg font-bold text-primary">{{ $t['stopKorupsiCtaTitle'] }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 md:text-base">{{ $t['stopKorupsiCtaP'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('instrumen-zona-integritas') }}" class="inline-flex rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50">{{ $t['stopLinkInstrumenZi'] }}</a>
                <a href="{{ $spanLaporUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">{{ $t['stopLinkSpanLapor'] }}</a>
            </div>
        </section>
    </div>
</main>
@endsection

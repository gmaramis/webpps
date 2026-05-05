@extends('layouts.app')

@section('title', ($t['stopGratifikasiTitle'] ?? 'Stop Gratifikasi').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $bullets = $ppsData['STOP_GRATIFIKASI_BULLETS'] ?? [];
    $instrumenHref = isset($ppsData['STOP_GRATIFIKASI_INSTRUMEN_URL']) && is_string($ppsData['STOP_GRATIFIKASI_INSTRUMEN_URL']) && trim($ppsData['STOP_GRATIFIKASI_INSTRUMEN_URL']) !== ''
        ? trim($ppsData['STOP_GRATIFIKASI_INSTRUMEN_URL'])
        : route('instrumen-zona-integritas');
    $simple = $ppsData['STOP_GRATIFIKASI_SIMPLE'] ?? null;
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
    $ziLabel = $t['stopGratifikasiLinkZi'] ?? $t['stopLinkInstrumenZi'] ?? '';
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['stopGratifikasiEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['stopGratifikasiTitle'] }}</h1>
            @if(count($simpleParas) > 0)
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{!! nl2br(e($simpleParas[0])) !!}</p>
            @else
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['stopGratifikasiLead'] }}</p>
            @endif
        </header>

        @if(count($simpleParas) > 1)
            <div class="max-w-none">
                @foreach(array_slice($simpleParas, 1) as $i => $para)
                    <p class="{{ $i > 0 ? 'mt-4 ' : '' }}text-base leading-relaxed text-slate-700">{!! nl2br(e($para)) !!}</p>
                @endforeach
            </div>
        @elseif(count($simpleParas) === 0)
            <div class="max-w-none">
                <p class="text-base leading-relaxed text-slate-700">{{ $t['stopGratifikasiP1'] }}</p>
                <p class="mt-4 text-base leading-relaxed text-slate-700">{{ $t['stopGratifikasiP2'] }}</p>
            </div>
        @endif

        <section class="mt-10 rounded-2xl border border-amber-100 bg-amber-50/40 p-6 md:p-8">
            <h2 class="font-display text-lg font-bold text-primary">{{ $t['stopGratifikasiBulletsTitle'] }}</h2>
            <ul class="mt-4 list-disc space-y-2 pl-5 text-sm text-slate-800 md:text-base">
                @foreach($bullets as $b)
                    <li>{{ $b['text'][$loc] ?? $b['text']['id'] ?? '' }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <h2 class="font-display text-lg font-bold text-primary">{{ $t['stopGratifikasiCtaTitle'] }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 md:text-base">{{ $t['stopGratifikasiCtaP'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ $instrumenHref }}" class="inline-flex rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">{{ $ziLabel }}</a>
            </div>
        </section>
    </div>
</main>
@endsection

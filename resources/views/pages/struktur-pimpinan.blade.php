@extends('layouts.app')

@section('title', ($t['leadersPageTitle'] ?? 'Pimpinan').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $leaders = is_array($ppsData['LEADERS'] ?? null) ? $ppsData['LEADERS'] : [];
    $director = $leaders[0] ?? null;
    $viceLeft = $leaders[1] ?? null;
    $viceRight = $leaders[2] ?? null;
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['leadersPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['leadersPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['leadersPageLead'] }}</p>
        </header>

        <section class="mb-12 text-center">
            <h2 class="mx-auto mb-6 max-w-2xl font-display text-lg font-bold leading-snug text-primary md:text-xl">
                {{ $t['leadersDirectorTitle'] }} &amp; {{ $t['leadersViceTitle'] }}
            </h2>

            @if(count($leaders) === 0)
                <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 px-4 py-6 text-center text-sm text-slate-600">{{ $t['leadersPageLead'] }}</p>
            @else
                <div class="leadership-chart leadership-pyramid flex w-full flex-col items-center">
                    @if($director !== null)
                        @php
                            $dRole = $director['role'][$loc] ?? $director['role']['id'] ?? '';
                            $dSrc = \App\Models\LeadershipPerson::publicPhotoUrl($director['photo'] ?? null);
                            $dName = (string) ($director['name'] ?? '');
                        @endphp
                        <div class="flex justify-center px-2">
                            <article class="leader-card director-card leader-card--portrait flex w-full flex-col items-center overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm">
                                <div class="leader-card-photo">
                                    <div class="leader-card-photo__ring">
                                        <div class="leader-card-photo__clip">
                                            <img src="{{ $dSrc }}" alt="{{ $dName }}" class="leader-card-photo__img" width="320" height="320" loading="lazy" decoding="async">
                                        </div>
                                    </div>
                                </div>
                                <div class="leader-card-body flex w-full flex-1 flex-col items-center break-words p-5 pt-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-primary">{{ $dRole }}</p>
                                    <p class="mt-1 font-display text-base font-bold text-slate-900">{{ $dName }}</p>
                                    <p class="mt-2 text-xs text-slate-500"><span class="font-medium text-slate-600">{{ $t['leadersNipLabel'] }}</span> {{ $director['nip'] ?? '' }}</p>
                                </div>
                            </article>
                        </div>
                    @endif

                    @if($viceLeft !== null || $viceRight !== null)
                        <div class="leadership-pyramid__connector w-full" aria-hidden="true">
                            <svg class="leadership-pyramid__svg leadership-pyramid__svg--tree hidden w-full md:block" viewBox="0 0 400 72" preserveAspectRatio="xMidYMin meet" xmlns="http://www.w3.org/2000/svg">
                                @if($viceLeft !== null && $viceRight !== null)
                                    <path fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M200 4 L200 26 M64 26 L336 26 M64 26 L64 66 M336 26 L336 66" />
                                @elseif($viceLeft !== null)
                                    <path fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M200 4 L200 26 L88 26 L88 66" />
                                @else
                                    <path fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M200 4 L200 26 L312 26 L312 66" />
                                @endif
                            </svg>
                            <div class="mx-auto flex justify-center md:hidden">
                                <span class="block w-px rounded-full bg-primary/65" style="height: 1.25rem"></span>
                            </div>
                        </div>
                        <div class="leadership-pyramid__vice-row mx-auto grid w-full max-w-6xl grid-cols-1 gap-10 px-2 sm:px-4 md:grid-cols-2 md:gap-x-32 md:gap-y-10 lg:max-w-7xl lg:gap-x-40">
                            <div class="flex min-w-0 justify-center md:justify-end">
                                @if($viceLeft !== null)
                                    @php
                                        $vlRole = $viceLeft['role'][$loc] ?? $viceLeft['role']['id'] ?? '';
                                        $vlSrc = \App\Models\LeadershipPerson::publicPhotoUrl($viceLeft['photo'] ?? null);
                                        $vlName = (string) ($viceLeft['name'] ?? '');
                                    @endphp
                                    <article class="leader-card vice-card leader-card--portrait flex w-full flex-col items-center overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm">
                                        <div class="leader-card-photo">
                                            <div class="leader-card-photo__ring">
                                                <div class="leader-card-photo__clip">
                                                    <img src="{{ $vlSrc }}" alt="{{ $vlName }}" class="leader-card-photo__img" width="320" height="320" loading="lazy" decoding="async">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="leader-card-body flex w-full flex-1 flex-col items-center break-words p-5 pt-4">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-primary">{{ $vlRole }}</p>
                                            <p class="mt-1 font-display text-base font-bold text-slate-900">{{ $vlName }}</p>
                                            <p class="mt-2 text-xs text-slate-500"><span class="font-medium text-slate-600">{{ $t['leadersNipLabel'] }}</span> {{ $viceLeft['nip'] ?? '' }}</p>
                                        </div>
                                    </article>
                                @endif
                            </div>
                            @if($viceLeft !== null && $viceRight !== null)
                                <div class="flex justify-center md:hidden" aria-hidden="true">
                                    <span class="block w-px rounded-full bg-primary/65" style="height: 1rem"></span>
                                </div>
                            @endif
                            <div class="flex min-w-0 justify-center md:justify-start">
                                @if($viceRight !== null)
                                    @php
                                        $vrRole = $viceRight['role'][$loc] ?? $viceRight['role']['id'] ?? '';
                                        $vrSrc = \App\Models\LeadershipPerson::publicPhotoUrl($viceRight['photo'] ?? null);
                                        $vrName = (string) ($viceRight['name'] ?? '');
                                    @endphp
                                    <article class="leader-card vice-card leader-card--portrait flex w-full flex-col items-center overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm">
                                        <div class="leader-card-photo">
                                            <div class="leader-card-photo__ring">
                                                <div class="leader-card-photo__clip">
                                                    <img src="{{ $vrSrc }}" alt="{{ $vrName }}" class="leader-card-photo__img" width="320" height="320" loading="lazy" decoding="async">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="leader-card-body flex w-full flex-1 flex-col items-center break-words p-5 pt-4">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-primary">{{ $vrRole }}</p>
                                            <p class="mt-1 font-display text-base font-bold text-slate-900">{{ $vrName }}</p>
                                            <p class="mt-2 text-xs text-slate-500"><span class="font-medium text-slate-600">{{ $t['leadersNipLabel'] }}</span> {{ $viceRight['nip'] ?? '' }}</p>
                                        </div>
                                    </article>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </section>
    </div>
</main>
@endsection

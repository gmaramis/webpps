@extends('layouts.app')

@section('title', ($t['doktorTitle'] ?? 'Program S3').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $programs = $ppsData['PROGRAMS_DOKTOR'] ?? [];
    $hero = $ppsData['DOKTOR_HERO'] ?? 'programs/doktor-photo.png';
    $heroSrc = asset(ltrim($hero, '/'));
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid gap-10 lg:grid-cols-[1fr_1.1fr] lg:items-center">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-100 shadow-lg">
                <img src="{{ $heroSrc }}" alt="" class="h-full w-full object-cover" width="800" height="600" loading="lazy" decoding="async">
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $loc === 'id' ? 'Program Studi' : 'Study programs' }}</p>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['doktorTitle'] }}</h1>
                <p class="mt-4 text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['doktorLead'] }}</p>
            </div>
        </div>

        <section class="mt-14">
            <h2 class="font-display text-xl font-bold text-primary">{{ $loc === 'id' ? 'Program Doktor (S3)' : 'Doctoral programs (S3)' }}</h2>
            <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($programs as $p)
                    @php
                        $name = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                        $blurb = $p['blurb'][$loc] ?? $p['blurb']['id'] ?? '';
                    @endphp
                    <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="font-display text-lg font-bold text-primary">{{ $name }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $blurb }}</p>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</main>
@endsection

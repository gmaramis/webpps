@extends('layouts.app')

@section('title', ($t['magisterTitle'] ?? 'Program S2').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $programs = $programs ?? [];
    $active = $active ?? null;
    $selectedSlug = $selectedSlug ?? '';
    $invalidProgramSelection = $invalidProgramSelection ?? false;
    $hero = $ppsData['MAGISTER_HERO'] ?? 'programs/magister-photo.png';
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
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['magisterTitle'] }}</h1>
                <p class="mt-4 text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['magisterLead'] }}</p>
            </div>
        </div>

        <section class="mt-14" aria-labelledby="s2-programs-heading">
            <h2 id="s2-programs-heading" class="font-display text-xl font-bold text-primary">{{ $loc === 'id' ? 'Program Magister (S2)' : "Master's programs (S2)" }}</h2>
            <p class="mt-2 max-w-3xl text-sm text-slate-600">{{ $loc === 'id' ? 'Deskripsi program tampil di bawah; gunakan pil untuk memilih program lain.' : 'The programme description appears below; use the chips to switch programme.' }}</p>

            @if(count($programs) === 0)
                <p class="mt-8 text-slate-600">{{ $loc === 'id' ? 'Belum ada program S2 yang dipublikasikan.' : 'No master’s (S2) programmes are published yet.' }}</p>
            @else
                <ul class="mt-6 flex flex-wrap gap-2">
                    @foreach($programs as $p)
                        @php
                            $slug = (string) ($p['slug'] ?? '');
                            $name = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                            $isActive = $slug !== '' && $slug === $selectedSlug;
                            $href = $slug !== '' ? route('program.s2', ['program' => $slug]) : route('program.s2');
                        @endphp
                        <li>
                            <a href="{{ $href }}" class="inline-flex rounded-full border px-4 py-2 text-sm font-semibold transition {{ $isActive ? 'border-primary bg-primary text-white shadow-md shadow-primary/20' : 'border-slate-200 bg-white text-slate-800 hover:border-primary/40 hover:text-primary' }}">{{ $name }}</a>
                        </li>
                    @endforeach
                </ul>

                @if($active && is_array($active))
                    @php
                        $dName = $active['name'][$loc] ?? $active['name']['id'] ?? '';
                        $dBlurb = $active['blurb'][$loc] ?? $active['blurb']['id'] ?? '';
                        $official = isset($active['official_url']) && is_string($active['official_url']) ? trim($active['official_url']) : '';
                    @endphp
                    <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                        <h3 class="font-display text-2xl font-bold text-primary">{{ $dName }}</h3>
                        <p class="mt-4 text-base leading-relaxed text-slate-700">{{ $dBlurb }}</p>
                        @if($official !== '')
                            <p class="mt-6">
                                <a href="{{ $official }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-dark">{{ $loc === 'id' ? 'Situs web resmi prodi' : 'Official programme website' }} ↗</a>
                            </p>
                        @else
                            <p class="mt-6 text-sm text-slate-500">{{ $loc === 'id' ? 'Tautan situs resmi prodi belum diatur.' : 'The official programme website link has not been set yet.' }}</p>
                        @endif
                    </div>
                @elseif($invalidProgramSelection)
                    <p class="mt-8 text-rose-700" role="status">{{ $loc === 'id' ? 'Program tidak ditemukan. Periksa parameter URL program.' : 'Programme not found. Check the programme URL parameter.' }}</p>
                @endif
            @endif
        </section>
    </div>
</main>
@endsection

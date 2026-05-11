@extends('layouts.app')

@section('title', ($t['magisterTitle'] ?? 'Program S2').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $programs = $programs ?? [];
    $selectedSlug = $selectedSlug ?? '';
    $invalidProgramSelection = $invalidProgramSelection ?? false;
    $hero = $ppsData['MAGISTER_HERO'] ?? 'programs/magister-photo.png';
    $heroSrc = \App\Models\HomepageProgramDisplay::publicHeroUrl($hero, 'programs/magister-photo.png');

    $activeTabIndex = 0;
    if (count($programs) > 0) {
        foreach ($programs as $idx => $p) {
            if ((string) ($p['slug'] ?? '') === $selectedSlug) {
                $activeTabIndex = $idx;
                break;
            }
        }
        if ($invalidProgramSelection) {
            $activeTabIndex = 0;
        }
    }
    $programPagePath = parse_url(route('program.s2'), PHP_URL_PATH) ?: '/s2';
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid gap-10 lg:grid-cols-[1fr_1.1fr] lg:items-start lg:gap-12">
            <div class="order-2 min-w-0 lg:order-1">
                <div class="aspect-[4/3] min-h-[200px] overflow-hidden rounded-3xl border border-slate-200 bg-slate-100 shadow-lg sm:min-h-[240px] lg:min-h-[22rem]">
                    <img src="{{ $heroSrc }}" alt="" class="h-full w-full object-cover" width="800" height="600" loading="lazy" decoding="async">
                </div>
            </div>

            <div class="order-1 min-w-0 space-y-8 lg:order-2 lg:space-y-10">
                <header>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $loc === 'id' ? 'Program Studi' : 'Study programs' }}</p>
                    <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['magisterTitle'] }}</h1>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['magisterLead'] }}</p>
                </header>

                <section class="border-t border-slate-200 pt-8 lg:pt-10" aria-labelledby="s2-programs-heading">
                    <h2 id="s2-programs-heading" class="font-display text-xl font-bold text-primary">{{ $loc === 'id' ? 'Program Magister (S2)' : "Master's programs (S2)" }}</h2>
                    <p class="mt-2 max-w-3xl text-sm text-slate-600">{{ $loc === 'id' ? 'Pilih tab program untuk membaca penjelasan dan tautan situs resmi prodi.' : 'Select a programme tab to read the description and official website link.' }}</p>

                    @if(count($programs) === 0)
                        <p class="mt-8 text-slate-600">{{ $loc === 'id' ? 'Belum ada program S2 yang dipublikasikan.' : 'No master’s (S2) programmes are published yet.' }}</p>
                    @else
                        @include('partials.study-program-tabs', [
                            'programs' => $programs,
                            'activeTabIndex' => $activeTabIndex,
                            'tabPrefix' => 's2',
                            'programPagePath' => $programPagePath,
                            'selectedSlug' => $selectedSlug,
                            'invalidProgramSelection' => $invalidProgramSelection,
                            'tablistAriaLabel' => $loc === 'id' ? 'Program Magister (S2)' : "Master's programmes (S2)",
                            'invalidUrlMessage' => $loc === 'id' ? 'Program pada URL tidak ditemukan; menampilkan program pertama.' : 'The programme in the URL was not found; showing the first programme.',
                            'loc' => $loc,
                        ])
                    @endif
                </section>
            </div>
        </div>
    </div>
</main>
@endsection

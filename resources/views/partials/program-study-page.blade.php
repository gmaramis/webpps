{{--
    Halaman daftar program studi (/s2, /s3): hero lebar penuh + navigasi sidebar/select + panel konten.
--}}
@php
    $loc = app()->getLocale();
    $level = $level ?? 's2';
    $programs = $programs ?? [];
    $selectedSlug = $selectedSlug ?? '';
    $invalidProgramSelection = $invalidProgramSelection ?? false;
    $pageTitle = $pageTitle ?? ($level === 's3' ? 'doktorTitle' : 'magisterTitle');
    $pageLead = $pageLead ?? ($level === 's3' ? 'doktorLead' : 'magisterLead');
    $tabPrefix = $tabPrefix ?? $level;

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

    $selectId = $tabPrefix.'-program-select';
@endphp

<main id="main" class="program-study-page pb-16 pt-6 md:pb-20 md:pt-8">
    
        <div class="mx-auto max-w-6xl px-4">
            <header class="program-study-page__hero overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50/90 shadow-[0_20px_50px_-24px_rgba(15,23,42,0.12)]">
                <div class="grid gap-8 p-6 md:grid-cols-2 md:items-center md:gap-10 md:p-8 lg:p-10">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $loc === 'id' ? 'Program Studi' : 'Study programmes' }}</p>
                        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t[$pageTitle] ?? '' }}</h1>
                        <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t[$pageLead] ?? '' }}</p>
                    </div>
                    <div class="min-w-0">
                        <figure class="program-hero-frame program-hero-frame--showcase relative mx-auto max-w-md overflow-hidden rounded-2xl ring-1 ring-slate-900/[0.06] shadow-lg md:mx-0 md:ml-auto md:max-w-none">
                            <img src="{{ $heroSrc }}" alt="" width="800" height="500" class="program-hero-img h-full w-full max-h-[min(16rem,40vh)] object-cover md:max-h-[min(18rem,42vh)]" loading="eager" decoding="async">
                        </figure>
                    </div>
                </div>
            </header>

            <section class="mt-10 md:mt-12" aria-labelledby="{{ $tabPrefix }}-programs-heading">
                <h2 id="{{ $tabPrefix }}-programs-heading" class="font-display text-xl font-bold text-primary md:text-2xl">{{ $programsHeading }}</h2>
                <p class="mt-2 max-w-3xl text-sm text-slate-600 md:text-base">{{ $programsHint }}</p>

                @if(count($programs) === 0)
                    <p class="mt-8 text-slate-600">{{ $emptyMessage }}</p>
                @else
                    @include('partials.study-program-tabs', [
                        'programs' => $programs,
                        'activeTabIndex' => $activeTabIndex,
                        'tabPrefix' => $tabPrefix,
                        'programPagePath' => $programPagePath,
                        'selectedSlug' => $selectedSlug,
                        'invalidProgramSelection' => $invalidProgramSelection,
                        'tablistAriaLabel' => $tablistAriaLabel,
                        'invalidUrlMessage' => $invalidUrlMessage,
                        'selectId' => $selectId,
                        'loc' => $loc,
                    ])
                @endif
            </section>
        </div>
    </main>

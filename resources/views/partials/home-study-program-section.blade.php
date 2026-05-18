{{--
    Blok program studi di beranda: hero + pratinjau terbatas (6) + CTA ke halaman lengkap.
--}}
@php
    $loc = app()->getLocale();
    $previewLimit = 6;
    $allPrograms = $programs ?? [];
    $previewPrograms = array_slice($allPrograms, 0, $previewLimit);
    $moreCount = max(0, count($allPrograms) - count($previewPrograms));
    $imageOnLeft = ($imageFirst ?? 'false') === 'true';
    $isS3 = ($programRouteName ?? '') === 'program.s3';
    $viewAllLabel = $loc === 'id'
        ? ($isS3 ? 'Lihat semua program Doktor (S3)' : 'Lihat semua program Magister (S2)')
        : ($isS3 ? 'View all doctoral (S3) programmes' : "View all master's (S2) programmes");
    $moreLabel = $loc === 'id'
        ? 'Dan :count program studi lainnya.'
        : 'And :count other study programme(s).';
    $moreLabel = str_replace(':count', (string) $moreCount, $moreLabel);
    $learnMore = $loc === 'id' ? 'Selengkapnya' : 'Learn more';
    $theme = $theme ?? 'sky';
@endphp

<section id="{{ $sectionId }}" class="{{ $sectionClass }} relative isolate overflow-hidden py-14 md:py-20">
    @if($theme === 'violet')
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(195deg,#f5f3ff_0%,#ffffff_48%,#f8fafc_100%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-12 top-20 h-80 w-80 rounded-full bg-violet-400/20 blur-3xl md:h-[26rem] md:w-[26rem]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-10 right-0 h-56 w-56 rounded-full bg-indigo-400/15 blur-2xl" aria-hidden="true"></div>
    @else
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(165deg,#f0f9ff_0%,#ffffff_42%,#f8fafc_100%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-16 top-24 h-80 w-80 rounded-full bg-sky-400/25 blur-3xl md:-right-8 md:h-[28rem] md:w-[28rem]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-48 w-48 rounded-full bg-cyan-300/15 blur-2xl" aria-hidden="true"></div>
    @endif

    <div class="relative mx-auto max-w-6xl px-4">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
            <div class="min-w-0 {{ $imageOnLeft ? 'lg:order-2' : 'lg:order-1' }}">
                <span class="inline-flex items-center rounded-full border px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] {{ $badgeClass }}">{{ $badgeLabel }}</span>
                <h2 class="font-display mt-5 text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $title }}</h2>
                <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $lead }}</p>
            </div>
            <div class="min-w-0 {{ $imageOnLeft ? 'lg:order-1' : 'lg:order-2' }}">
                <div class="relative mx-auto max-w-lg lg:mx-0 lg:max-w-none">
                    <div class="absolute -inset-3 rounded-[1.75rem] opacity-70 blur-2xl md:-inset-4 {{ $heroGlowClass }}" aria-hidden="true"></div>
                    <figure class="program-hero-frame program-hero-frame--showcase relative overflow-hidden rounded-3xl ring-1 ring-slate-900/[0.06] {{ $heroFigureClass ?? 'shadow-[0_24px_60px_-18px_rgba(15,23,42,0.22)]' }}">
                        <img src="{{ $heroSrc }}" alt="" width="960" height="600" class="program-hero-img h-full w-full max-h-[min(22rem,52vh)] object-cover" loading="lazy" decoding="async">
                    </figure>
                </div>
            </div>
        </div>

        @if(count($previewPrograms) > 0)
            <div class="{{ $panelClass }} mt-10 rounded-3xl border border-slate-200/70 bg-white/95 p-5 shadow-[0_20px_50px_-24px_rgba(15,23,42,0.12)] backdrop-blur-md md:p-7 lg:mt-12">
                <ul class="m-0 grid list-none gap-2.5 p-0 sm:grid-cols-2 lg:grid-cols-3" role="list">
                    @foreach($previewPrograms as $p)
                        @php
                            $programName = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                            $programSlug = (string) ($p['slug'] ?? '');
                        @endphp
                        <li class="{{ $cardClass }} home-program-preview-card overflow-hidden rounded-xl border border-slate-200/80 bg-gradient-to-br shadow-sm transition hover:shadow-md {{ $theme === 'violet' ? 'from-white to-violet-50/40' : 'from-white to-sky-50/50' }}">
                            <a href="{{ route($programRouteName, ['program' => $programSlug]) }}" class="group flex items-center gap-3 px-3.5 py-3.5 no-underline text-inherit outline-none ring-primary/25 transition hover:bg-white focus-visible:ring-2">
                                <span class="program-study-accent {{ $accentClass }} home-program-preview-card__accent" aria-hidden="true"></span>
                                <span class="home-program-preview-card__title min-w-0 flex-1 text-[0.9375rem] font-semibold leading-snug text-primary group-hover:underline">{{ $programName }}</span>
                                <span class="shrink-0 text-slate-300 transition group-hover:text-primary" aria-hidden="true">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </span>
                                <span class="sr-only">{{ $learnMore }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 flex flex-col items-stretch gap-4 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    @if($moreCount > 0)
                        <p class="m-0 text-sm text-slate-500">{{ $moreLabel }}</p>
                    @else
                        <p class="m-0 text-sm text-slate-500">{{ $loc === 'id' ? 'Klik program untuk detail lengkap.' : 'Click a programme for full details.' }}</p>
                    @endif
                    <a href="{{ route($programRouteName) }}" class="home-program-preview-cta {{ $ctaClass }} inline-flex shrink-0 items-center justify-center rounded-xl px-5 py-2.5 text-center text-sm font-bold text-white shadow-md transition hover:brightness-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">
                        {{ $viewAllLabel }}
                        <span class="ml-1.5" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

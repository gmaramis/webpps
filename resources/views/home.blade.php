@extends('layouts.app')

@section('title', ($t['brandTitle'] ?? '').' — UNIMA')

@php
    $loc = app()->getLocale();
    $slides = $ppsData['SLIDE_IMAGES'] ?? [];
    $news = $ppsData['NEWS'] ?? [];
    $featured = $news[0] ?? null;
    $gridNews = array_slice($news, 1);
    $magister = $ppsData['PROGRAMS_MAGISTER'] ?? [];
    $doktor = $ppsData['PROGRAMS_DOKTOR'] ?? [];
    $magisterHeroSrc = \App\Models\HomepageProgramDisplay::publicHeroUrl($ppsData['MAGISTER_HERO'] ?? null, 'programs/magister-photo.png');
    $doktorHeroSrc = \App\Models\HomepageProgramDisplay::publicHeroUrl($ppsData['DOKTOR_HERO'] ?? null, 'programs/doktor-photo.png');
    $announcements = $ppsData['ANNOUNCEMENTS'] ?? [];
    $agenda = $ppsData['AGENDA'] ?? [];
    $newsHref = function (?array $entry, string $loc): string {
        if ($entry === null) {
            return '#';
        }
        $h = $entry['href'] ?? '#';

        return is_array($h) ? ($h[$loc] ?? ($h['id'] ?? '#')) : (string) $h;
    };
@endphp

@section('content')
<main id="main" class="site-home-root relative z-[1]">
    <section class="hero-slider relative isolate overflow-hidden">
        <div class="slider-viewport relative h-[52vh] min-h-[320px] max-h-[620px]">
            @foreach($slides as $idx => $src)
                <div class="slide {{ $idx === 0 ? 'is-active' : '' }}" data-slide="{{ $idx }}" aria-hidden="{{ $idx !== 0 ? 'true' : 'false' }}">
                    <img src="{{ \App\Models\HeroSlide::publicImageUrl(is_string($src) ? $src : '') }}" alt="" class="slide-img absolute inset-0 z-0 h-full w-full object-cover" width="1600" height="700" decoding="async">
                    <div class="slide-scrim absolute inset-0 z-[1]" aria-hidden="true"></div>
                </div>
            @endforeach
        </div>
        <div class="slider-dots absolute bottom-5 left-1/2 z-[3] flex -translate-x-1/2 items-center gap-2 rounded-full bg-black/30 px-3 py-2 backdrop-blur-sm">
            @foreach($slides as $i => $src)
                <button type="button" class="dot {{ $i === 0 ? 'is-active' : '' }}" data-slide-to="{{ $i }}" aria-label="Slide {{ $i + 1 }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
        <div class="site-home-hero-bottom-fade" aria-hidden="true"></div>
    </section>

    <section class="relative isolate overflow-hidden border-y border-slate-200/70 bg-gradient-to-b from-white/50 via-white/90 to-white py-12 md:py-16">
        <div class="mx-auto w-full max-w-6xl px-4">
            <div class="overflow-hidden rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10 backdrop-blur-sm md:p-9">
                <div class="mb-8 flex flex-col gap-4 border-l-4 border-primary/80 pl-5 md:mb-10 md:pl-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['historyEyebrow'] }}</p>
                    <h2 class="font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['historyTitle'] }}</h2>
                    <p class="max-w-3xl text-base leading-relaxed text-slate-700 md:text-lg">{{ $t['historyLead'] }}</p>
                </div>
                <div class="grid gap-6 lg:grid-cols-[1.2fr_0.95fr]">
                    <article class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm md:p-6">
                        <p class="m-0 text-sm leading-relaxed text-slate-700 md:text-base">{{ $t['historyP1'] }}</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-700 md:text-base">{{ $t['historyP2'] }}</p>
                    </article>
                    <article class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/60 to-white p-5 shadow-sm md:p-6">
                        <h3 class="mb-4 text-base font-bold text-primary md:text-lg">{{ $t['historyMilestonesTitle'] }}</h3>
                        <ul class="m-0 list-none space-y-3 p-0 text-sm leading-relaxed text-slate-700 md:text-base">
                            @foreach([$t['historyMilestone1'],$t['historyMilestone2'],$t['historyMilestone3'],$t['historyMilestone4']] as $m)
                                <li class="flex items-start gap-3"><span class="mt-1 inline-block h-2 w-2 shrink-0 rounded-full bg-primary"></span><span>{{ $m }}</span></li>
                            @endforeach
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @if($featured)
    <section id="berita" class="news-section relative isolate overflow-hidden border-y border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto w-full max-w-6xl px-4">
            <div class="news-section-head mb-8 flex flex-wrap items-end justify-between gap-4 md:mb-10">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-primary/80">{{ $t['newsEyebrow'] }}</p>
                    <h2 class="font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['newsTitle'] }}</h2>
                    <p class="mt-2 max-w-2xl text-slate-600">{{ $t['newsLead'] }}</p>
                </div>
                <a href="#" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-primary shadow-sm transition hover:border-primary/25 hover:bg-slate-50 md:text-base">{{ $t['newsViewAll'] }}</a>
            </div>
            <div class="news-feature relative overflow-hidden rounded-2xl bg-[#221E1B] text-white shadow-[0_28px_50px_-28px_rgba(0,0,0,0.45)] ring-1 ring-black/20 md:rounded-3xl">
                <div class="grid lg:grid-cols-2 lg:items-stretch">
                    @php
                        $featuredImgSrc = asset(ltrim($featured['image'], '/'));
                        $featuredImgAlt = $featured['imageAlt'][$loc] ?? '';
                        $newsZoomLabel = $loc === 'id' ? 'Perbesar gambar' : 'Enlarge image';
                    @endphp
                    <div class="relative isolate h-44 min-h-[11rem] w-full shrink-0 overflow-hidden bg-[#2a2420] sm:h-48 sm:min-h-[12rem] md:h-52 md:min-h-[13rem] lg:h-full lg:min-h-[15rem]">
                        <button type="button"
                            class="news-feature-media group absolute inset-0 z-0 block h-full w-full cursor-zoom-in border-0 bg-transparent p-0 text-left focus-visible:outline focus-visible:ring-2 focus-visible:ring-amber-400/90 focus-visible:ring-offset-2 focus-visible:ring-offset-[#2a2420]"
                            data-news-lightbox-src="{{ $featuredImgSrc }}"
                            data-news-lightbox-alt="{{ $featuredImgAlt }}"
                            aria-label="{{ $newsZoomLabel }}">
                            <img src="{{ $featuredImgSrc }}" alt="{{ $featuredImgAlt }}" width="640" height="400" class="news-feature-img pointer-events-none h-full w-full object-cover object-center transition duration-300 ease-out group-hover:scale-[1.02]" decoding="async" fetchpriority="high">
                            <span class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-black/45 via-transparent to-black/15 lg:bg-gradient-to-r lg:from-transparent lg:via-transparent lg:to-[#221E1B]/90" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="flex flex-col justify-center px-5 py-8 md:px-10 md:py-10 lg:max-w-xl lg:pr-12">
                        <h3 class="font-display text-2xl font-bold leading-tight tracking-tight text-white md:text-3xl lg:text-[1.85rem]">{{ $featured['title'][$loc] }}</h3>
                        <p class="mt-3 text-sm font-medium text-white/85 md:text-base">
                            <span class="text-white/95">{{ \App\Support\PpsContent::formatAnnouncementDate($featured['date'], $loc) }}</span>
                            <span class="mx-2 text-white/40" aria-hidden="true">·</span>
                            <span>{{ $featured['location'][$loc] ?? '' }}</span>
                        </p>
                        <p class="mt-4 text-sm leading-relaxed text-white/75 md:text-[0.95rem]">{{ $featured['excerpt'][$loc] }}</p>
                        <a href="{{ $newsHref($featured, $loc) }}" class="group/cta mt-7 inline-flex w-fit items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] text-white no-underline transition hover:text-amber-200">
                            <span>{{ $t['newsReadMoreCaps'] }}</span>
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-400 text-stone-900 transition group-hover/cta:bg-amber-300">
                                <svg class="h-4 w-4 translate-x-px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            @if(count($gridNews))
            <ul id="news-list" class="news-list m-0 mt-12 grid list-none gap-8 p-0 md:mt-14 md:grid-cols-3">
                @foreach($gridNews as $i => $n)
                    @php
                        $gridImgSrc = asset(ltrim($n['image'], '/'));
                        $gridImgAlt = $n['imageAlt'][$loc] ?? '';
                    @endphp
                    <li class="news-card group flex flex-col">
                        <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/[0.03] transition hover:border-slate-300 hover:shadow-md">
                            <button type="button"
                                class="relative flex h-36 w-full shrink-0 cursor-zoom-in overflow-hidden bg-slate-100 sm:h-40 border-0 p-0 text-left focus-visible:outline focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-inset"
                                data-news-lightbox-src="{{ $gridImgSrc }}"
                                data-news-lightbox-alt="{{ $gridImgAlt }}"
                                aria-label="{{ $newsZoomLabel }}">
                                <img src="{{ $gridImgSrc }}" alt="{{ $gridImgAlt }}" width="480" height="320" class="pointer-events-none h-full w-full object-cover object-center transition duration-500 ease-out group-hover:scale-[1.02]" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">
                            </button>
                            <a href="{{ $newsHref($n, $loc) }}" class="flex flex-1 flex-col px-1 pb-5 pt-4 no-underline text-inherit md:px-2">
                                <h3 class="font-display text-lg font-bold leading-snug tracking-tight text-slate-900 group-hover:text-primary md:text-[1.05rem]">{{ $n['title'][$loc] }}</h3>
                                <p class="mt-auto flex items-center gap-1.5 pt-4 text-[11px] font-medium uppercase tracking-[0.08em] text-slate-500 md:text-xs">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                                    <span>{{ \App\Support\PpsContent::formatAnnouncementDate($n['date'], $loc) }}</span>
                                </p>
                            </a>
                        </article>
                    </li>
                @endforeach
            </ul>
            @endif

            <dialog id="news-image-lightbox" class="news-image-lightbox" aria-labelledby="news-image-lightbox-title">
                <div class="news-image-lightbox__inner">
                    <h2 id="news-image-lightbox-title" class="sr-only">{{ $loc === 'id' ? 'Gambar berita' : 'News image' }}</h2>
                    <button type="button" id="news-image-lightbox-close" class="news-image-lightbox__close" aria-label="{{ $loc === 'id' ? 'Tutup' : 'Close' }}">×</button>
                    <img id="news-image-lightbox-img" src="" alt="" class="news-image-lightbox__img" width="1100" height="800" decoding="async">
                </div>
            </dialog>
        </div>
    </section>
    @endif

    <section id="magister" class="magister-section relative isolate overflow-hidden border-y border-slate-200/80 py-16 md:py-24">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(165deg,#f0f9ff_0%,#ffffff_42%,#f8fafc_100%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-16 top-24 h-80 w-80 rounded-full bg-sky-400/25 blur-3xl md:-right-8 md:h-[28rem] md:w-[28rem]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-48 w-48 rounded-full bg-cyan-300/15 blur-2xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-6xl px-4">
            <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="order-2 min-w-0 lg:order-1 lg:col-span-5 xl:col-span-5">
                    <div class="magister-panel rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-[0_24px_60px_-24px_rgba(15,23,42,0.14)] backdrop-blur-md md:p-8">
                        <span class="inline-flex items-center rounded-full border border-sky-200/90 bg-gradient-to-r from-sky-50 to-cyan-50 px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] text-sky-900">{{ $loc === 'en' ? 'Master (S2)' : 'Magister (S2)' }}</span>
                        <h2 class="font-display mt-5 text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['magisterTitle'] }}</h2>
                        <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['magisterLead'] }}</p>
                        <ul class="mt-8 grid list-none gap-3 p-0 sm:grid-cols-2">
                            @foreach($magister as $p)
                                @php
                                    $excerptText = trim((string) (($p['excerpt'] ?? [])[$loc] ?? ''));
                                    $cardTeaser = $excerptText !== '' ? $excerptText : ($loc === 'id' ? 'Lihat deskripsi lengkap program di halaman Magister.' : "See the full program description on the Master's page.");
                                @endphp
                                <li class="magister-card rounded-2xl border border-slate-200/70 bg-gradient-to-br from-white to-sky-50/50 shadow-sm transition hover:border-sky-300/80 hover:shadow-md">
                                    <a href="{{ route('program.s2', ['program' => $p['slug'] ?? '']) }}" class="group block rounded-2xl p-4 no-underline text-inherit outline-none ring-primary/25 focus-visible:ring-2">
                                        <div class="flex items-start gap-3">
                                            <span class="program-study-accent program-study-accent--sky mt-0.5 shrink-0" aria-hidden="true"></span>
                                            <div class="min-w-0">
                                                <h3 class="text-[1.02rem] font-semibold leading-snug text-primary group-hover:underline">{{ $p['name'][$loc] }}</h3>
                                                <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $cardTeaser }}</p>
                                                <p class="mt-2 text-xs font-semibold text-primary">{{ $loc === 'id' ? 'Selengkapnya' : 'Learn more' }} <span aria-hidden="true">→</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="order-1 min-w-0 lg:order-2 lg:col-span-7 xl:col-span-7">
                    <div class="relative mx-auto max-w-xl lg:mx-0 lg:max-w-none">
                        <div class="absolute -inset-3 rounded-[1.75rem] bg-gradient-to-br from-sky-400/35 via-cyan-300/15 to-transparent opacity-70 blur-2xl md:-inset-5 md:rounded-[2rem]" aria-hidden="true"></div>
                        <figure class="program-hero-frame program-hero-frame--showcase relative overflow-hidden rounded-3xl ring-1 ring-slate-900/[0.06] shadow-[0_28px_60px_-18px_rgba(15,23,42,0.28)]">
                            <img src="{{ $magisterHeroSrc }}" alt="" width="960" height="600" class="program-hero-img h-full w-full" loading="lazy" decoding="async">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="doktor" class="doktor-section relative isolate overflow-hidden border-b border-slate-200/80 py-16 md:py-24">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(195deg,#f5f3ff_0%,#ffffff_48%,#f8fafc_100%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-12 top-20 h-80 w-80 rounded-full bg-violet-400/20 blur-3xl md:h-[26rem] md:w-[26rem]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-10 right-0 h-56 w-56 rounded-full bg-indigo-400/15 blur-2xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-6xl px-4">
            <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="order-1 min-w-0 lg:col-span-7 xl:col-span-7">
                    <div class="relative mx-auto max-w-xl lg:mx-0 lg:max-w-none">
                        <div class="absolute -inset-3 rounded-[1.75rem] bg-gradient-to-br from-violet-500/30 via-indigo-400/15 to-transparent opacity-70 blur-2xl md:-inset-5 md:rounded-[2rem]" aria-hidden="true"></div>
                        <figure class="program-hero-frame program-hero-frame--showcase relative overflow-hidden rounded-3xl ring-1 ring-slate-900/[0.06] shadow-[0_28px_60px_-18px_rgba(30,27,75,0.32)]">
                            <img src="{{ $doktorHeroSrc }}" alt="" width="960" height="600" class="program-hero-img h-full w-full" loading="lazy" decoding="async">
                        </figure>
                    </div>
                </div>
                <div class="order-2 min-w-0 lg:col-span-5 xl:col-span-5">
                    <div class="doktor-panel rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-[0_24px_60px_-24px_rgba(15,23,42,0.14)] backdrop-blur-md md:p-8">
                        <span class="inline-flex items-center rounded-full border border-violet-200/90 bg-gradient-to-r from-violet-50 to-indigo-50 px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] text-indigo-950">{{ $loc === 'en' ? 'Doctorate (S3)' : 'Doktor (S3)' }}</span>
                        <h2 class="font-display mt-5 text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['doktorTitle'] }}</h2>
                        <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['doktorLead'] }}</p>
                        <ul class="mt-8 grid list-none gap-3 p-0 sm:grid-cols-2">
                            @foreach($doktor as $p)
                                @php
                                    $excerptText = trim((string) (($p['excerpt'] ?? [])[$loc] ?? ''));
                                    $cardTeaser = $excerptText !== '' ? $excerptText : ($loc === 'id' ? 'Lihat deskripsi lengkap program di halaman Doktor.' : 'See the full program description on the Doctoral page.');
                                @endphp
                                <li class="doktor-card rounded-2xl border border-slate-200/70 bg-gradient-to-br from-white to-violet-50/40 shadow-sm transition hover:border-violet-300/80 hover:shadow-md">
                                    <a href="{{ route('program.s3', ['program' => $p['slug'] ?? '']) }}" class="group block rounded-2xl p-4 no-underline text-inherit outline-none ring-primary/25 focus-visible:ring-2">
                                        <div class="flex items-start gap-3">
                                            <span class="program-study-accent program-study-accent--violet mt-0.5 shrink-0" aria-hidden="true"></span>
                                            <div class="min-w-0">
                                                <h3 class="text-[1.02rem] font-semibold leading-snug text-primary group-hover:underline">{{ $p['name'][$loc] }}</h3>
                                                <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $cardTeaser }}</p>
                                                <p class="mt-2 text-xs font-semibold text-primary">{{ $loc === 'id' ? 'Selengkapnya' : 'Learn more' }} <span aria-hidden="true">→</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pengumuman-agenda" class="border-t border-slate-200 bg-white py-14 md:py-20">
        <div class="mx-auto max-w-6xl px-4">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 md:gap-14 lg:gap-16">
                <div>
                    <header class="mb-2">
                        <h2 class="font-display text-2xl font-bold tracking-tight text-primary md:text-[1.65rem]">{{ $t['pengumumanTitle'] }}</h2>
                        <div class="mt-3 h-[3px] w-12 bg-amber-400 md:w-14" aria-hidden="true"></div>
                        <div class="mt-3 border-b border-slate-200" aria-hidden="true"></div>
                    </header>
                    <ul class="m-0 list-none p-0">
                        @foreach($announcements as $item)
                            <li class="border-b border-slate-200 last:border-b-0">
                                <a href="{{ $item['href'] }}" class="group block py-5 no-underline transition-colors">
                                    <h3 class="font-display text-[1.02rem] font-semibold leading-snug text-slate-900 group-hover:text-primary md:text-[1.06rem]">{{ $item['title'][$loc] }}</h3>
                                    <p class="mt-2.5 flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-[0.08em] text-slate-400 md:text-xs">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                                        <span>{{ \App\Support\PpsContent::formatAnnouncementDate($item['dateISO'], $loc) }}</span>
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6 flex justify-end">
                        <a href="#" class="inline-flex items-center gap-2.5 text-sm font-semibold uppercase tracking-[0.14em] text-sky-700 no-underline transition hover:text-primary">
                            <span>{{ $t['pengumumanViewAll'] }}</span>
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-current text-current" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 9.5L14 12l-3.5 2.5"/></svg></span>
                        </a>
                    </div>
                </div>
                <div>
                    <header class="mb-2">
                        <h2 class="font-display text-2xl font-bold tracking-tight text-primary md:text-[1.65rem]">{{ $t['agendaTitle'] }}</h2>
                        <div class="mt-3 h-[3px] w-12 bg-amber-400 md:w-14" aria-hidden="true"></div>
                        <div class="mt-3 border-b border-slate-200" aria-hidden="true"></div>
                    </header>
                    <ul class="m-0 list-none p-0">
                        @foreach($agenda as $item)
                            <li class="border-b border-slate-200 last:border-b-0">
                                <a href="{{ $item['href'] }}" class="group flex gap-4 py-5 no-underline md:gap-5">
                                    <div class="flex w-[3.35rem] shrink-0 flex-col items-center justify-center self-center text-center md:w-[3.5rem]">
                                        <span class="font-display text-[2.1rem] font-bold leading-none text-slate-400 md:text-[2.25rem]">{{ $item['day'] }}</span>
                                        <span class="mt-1.5 text-[11px] font-bold uppercase leading-none tracking-wide text-slate-600">{{ $item['month'][$loc] }}</span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 items-center">
                                        <h3 class="font-display text-[1.02rem] font-semibold leading-snug text-slate-900 group-hover:text-primary md:text-[1.06rem]">{{ $item['title'][$loc] }}</h3>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6 flex justify-end">
                        <a href="#" class="inline-flex items-center gap-2.5 text-sm font-semibold uppercase tracking-[0.14em] text-sky-700 no-underline transition hover:text-primary">
                            <span>{{ $t['agendaViewAll'] }}</span>
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-current text-current" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 9.5L14 12l-3.5 2.5"/></svg></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

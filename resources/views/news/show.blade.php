@extends('layouts.app')

@php
    $title = $post->getTranslation('title', $locale);
    $body = $post->getTranslation('body', $locale);
    $excerpt = $post->getTranslation('excerpt', $locale, false);
    $metaTitle = $post->getTranslation('meta_title', $locale, false) ?: $title;
    $metaDescription = $post->getTranslation('meta_description', $locale, false) ?: ($excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $body), 160));
    $sidebarHeading = $locale === 'en' ? 'Other news' : 'Berita lainnya';
    $publishedMoment = $post->published_at ?? $post->created_at;
    $publishedLabel = \App\Support\PpsContent::formatAnnouncementDate($publishedMoment->format('Y-m-d'), $locale);
    $publishedAgeHuman = \App\Support\PpsContent::newsPublishedAgeHuman($publishedMoment, $locale);
    $authorName = trim((string) ($post->author ?? ''));
@endphp

@section('title', $metaTitle.' — '.($t['brandTitle'] ?? 'Pascasarjana UNIMA'))

@push('head')
    <meta name="description" content="{{ e($metaDescription) }}">
    <link rel="alternate" hreflang="id"
        href="{{ url(route('news.show', ['locale' => 'id', 'slug' => $post->getTranslationWithoutFallback('slug', 'id')], false)) }}">
    @if ($post->hasTranslation('slug', 'en'))
        <link rel="alternate" hreflang="en"
            href="{{ url(route('news.show', ['locale' => 'en', 'slug' => $post->getTranslationWithoutFallback('slug', 'en')], false)) }}">
    @endif
@endpush

@section('content')
{{--
  Layout dua kolom diatur di resources/css/pps.css (.news-detail-two-col), bukan Tailwind grid,
  supaya kolom kanan selalu di samping detail berita di viewport ≥768px.
--}}
{{-- Lebar penuh viewport supaya kolom artikel memakai sisa ruang di kanan sidebar (tanpa jalur kosong akibat max-w-*). --}}
<main id="main" class="w-full px-4 py-10 md:py-14 lg:px-8 xl:px-10">
    <div class="@if($sidebarNews->isNotEmpty()) news-detail-two-col @else news-detail-no-sidebar @endif">
        @php $hasHero = (bool) $post->image_path; @endphp
        <article lang="{{ $locale }}" class="news-detail-main max-w-full break-words rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8 lg:p-10">
            @if ($hasHero)
                <figure class="news-detail-hero-float">
                    <img src="{{ $post->newsImageUrl() }}" alt="" width="800" height="450" decoding="async">
                </figure>
            @endif

            <p class="text-xs font-semibold uppercase tracking-wider text-primary/85 {{ $hasHero ? 'mt-0' : '' }}">{{ $t['newsEyebrow'] ?? 'Berita' }}</p>
            <h1 class="font-display mt-2 text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $title }}</h1>
            <p class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                <svg class="h-4 w-4 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                </svg>
                <time datetime="{{ $publishedMoment->format('Y-m-d') }}">{{ $publishedLabel }}</time>
            </p>

            @if ($authorName !== '')
                <p class="mt-3 text-sm text-slate-700">
                    <span class="font-semibold text-slate-900">{{ $locale === 'en' ? 'Author' : 'Penulis' }}</span>
                    <span class="text-slate-600"> — {{ $authorName }}</span>
                </p>
            @endif

            @if ($publishedAgeHuman !== '')
                <p class="{{ $authorName !== '' ? 'mt-1.5' : 'mt-3' }} text-xs text-gray-500">
                    {{ $locale === 'en' ? 'Published' : 'Sudah tayang' }} {{ $publishedAgeHuman }}.
                </p>
            @endif

            @if ($excerpt)
                <p class="news-detail-excerpt {{ $hasHero ? 'mt-4' : 'mt-8' }} text-lg leading-relaxed text-slate-700">{{ $excerpt }}</p>
            @endif

            <div class="news-body {{ $hasHero ? 'mt-4' : 'mt-8' }} space-y-4 text-base leading-relaxed text-slate-800 [&_a]:font-medium [&_a]:text-primary [&_h2]:clear-both [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h3]:clear-both [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_img]:mx-auto [&_img]:my-4 [&_img]:block [&_img]:h-auto [&_img]:max-h-[min(24rem,65vh)] [&_img]:w-auto [&_img]:max-w-full [&_img]:object-contain [&_img]:rounded-lg [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:pl-6">
                {!! $body !!}
            </div>
        </article>

        @if ($sidebarNews->isNotEmpty())
            <aside class="news-detail-sidebar max-w-full" aria-labelledby="sidebar-news-heading">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-5">
                    <h2 id="sidebar-news-heading" class="font-display border-b border-slate-200 pb-3 text-lg font-bold text-primary">{{ $sidebarHeading }}</h2>
                    <ul class="mt-4 flex flex-col gap-3 p-0">
                        @foreach ($sidebarNews as $item)
                            @php
                                $itemSlug = (string) $item->getTranslationWithoutFallback('slug', $locale);
                                if ($itemSlug === '') {
                                    continue;
                                }
                                $itemTitle = (string) $item->getTranslationWithoutFallback('title', $locale);
                                $itemDate = $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d');
                                $itemHref = route('news.show', ['locale' => $locale, 'slug' => $itemSlug]);
                                $itemDateFormatted = \App\Support\PpsContent::formatAnnouncementDate($itemDate, $locale);
                            @endphp
                            <li class="list-none">
                                <a href="{{ $itemHref }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-slate-50/80 p-2.5 no-underline transition hover:border-slate-300 hover:bg-white">
                                    <div class="flex h-14 w-[4.25rem] shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/80">
                                        <img src="{{ $item->newsImageUrl() }}" alt="" class="h-full w-full object-contain object-center" width="80" height="56" loading="lazy" decoding="async">
                                    </div>
                                    <div class="min-w-0 flex-1 py-0.5">
                                        <p class="line-clamp-2 text-sm font-semibold leading-snug text-slate-900 group-hover:text-primary">{{ $itemTitle !== '' ? $itemTitle : '—' }}</p>
                                        <time datetime="{{ $itemDate }}" class="mt-1 block text-xs text-gray-500">{{ $itemDateFormatted }}</time>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        @endif
    </div>
</main>
@endsection

{{-- Puter.js: layanan cloud/AI dari sisi klien — tambahkan pemanggilan puter.* sesuai kebutuhan Anda. --}}
@push('scripts')
    <script src="https://js.puter.com/v2/"></script>
@endpush

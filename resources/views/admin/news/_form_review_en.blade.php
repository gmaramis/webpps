{{--
  Pratinjau terjemahan EN — dipanggil dari _form (panel tab).
  @var \App\Models\NewsItem $item
--}}
@php
    $slugEn = (string) $item->getTranslationWithoutFallback('slug', 'en');
@endphp

<div class="rounded-xl border border-amber-200/90 bg-gradient-to-b from-amber-50/90 to-white p-3 shadow-sm md:p-4">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-900/80">Langkah 2 · English</p>
            <h3 class="mt-0.5 font-display text-base font-bold text-slate-900">Tinjau terjemahan</h3>
            <p class="mt-1 max-w-xl text-xs leading-relaxed text-slate-600">
                Otomatis — jika OK, pilih <strong>Dipublikasikan</strong> di kanan lalu simpan.
            </p>
        </div>
        @if ($hasEn && ! $item->is_published && ($tstat === 'ready_for_review' || $tstat === 'idle'))
            <div class="shrink-0 rounded-lg bg-primary px-3 py-2 text-center text-[10px] font-bold text-white shadow-sm">
                Lanjut → panel kanan
            </div>
        @endif
    </div>

    <div class="mt-3 flex flex-wrap items-center gap-1.5">
        @switch($tstat)
            @case('processing')
                <span class="inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2 py-0.5 text-[10px] font-semibold text-sky-900">
                    <span class="relative flex h-1.5 w-1.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span><span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-sky-500"></span></span>
                    Menerjemahkan…
                </span>
                @break
            @case('ready_for_review')
                <span class="inline-flex items-center gap-1 rounded-full border border-amber-300 bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-950">Siap ditinjau</span>
                @break
            @case('failed')
                <span class="inline-flex rounded-full border border-rose-300 bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-900">Gagal</span>
                @break
            @default
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-700">{{ $tstat }}</span>
        @endswitch
    </div>

    @if ($tstat === 'failed' && filled($item->translation_error))
        <div class="mt-2 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-2 text-xs text-rose-950">
            <p class="font-semibold text-rose-900">Error</p>
            <p class="mt-0.5 max-h-16 overflow-y-auto font-mono text-[10px] leading-relaxed">{{ $item->translation_error }}</p>
            @if (! $item->is_published && $item->needsEnglishAutofill())
                <form method="post" action="{{ route('admin.news.translation-retry', $item) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="rounded bg-rose-700 px-2 py-1 text-[10px] font-bold text-white hover:bg-rose-800">Coba lagi</button>
                </form>
            @endif
        </div>
    @endif

    @if (! $hasEn)
        <div class="mt-3 rounded-lg border border-slate-200 bg-white px-3 py-4 text-center">
            <p class="text-sm font-semibold text-slate-800">Belum ada EN</p>
            <p class="mx-auto mt-1 max-w-sm text-xs text-slate-600">Simpan draf, tunggu Puter/webhook, lalu muat ulang halaman.</p>
            @if (! $item->is_published && $item->needsEnglishAutofill())
                <form method="post" action="{{ route('admin.news.translation-retry', $item) }}" class="mt-3 inline-block">
                    @csrf
                    <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-xs font-bold text-white hover:bg-primary-dark">Jalankan terjemahan</button>
                </form>
            @endif
        </div>
    @else
        <div class="mt-3 space-y-2">
            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 bg-slate-50/80 px-2.5 py-1">
                    <h4 class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Judul EN</h4>
                </header>
                <div class="px-2.5 py-2">
                    <p class="text-sm font-semibold leading-snug text-slate-900">{{ $enTitle !== '' ? $enTitle : '—' }}</p>
                </div>
            </article>

            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 bg-slate-50/80 px-2.5 py-1">
                    <h4 class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Ringkasan EN</h4>
                </header>
                <div class="px-2.5 py-2">
                    <p class="whitespace-pre-wrap text-xs leading-relaxed text-slate-800">{{ $enExcerpt !== '' ? $enExcerpt : '—' }}</p>
                </div>
            </article>

            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-2.5 py-1">
                    <h4 class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Isi EN</h4>
                    <span class="text-[9px] text-slate-400">gulir di dalam</span>
                </header>
                <div class="max-h-[min(18rem,45vh)] overflow-y-auto px-2.5 py-2">
                    <div class="text-xs leading-relaxed text-slate-800 [&_img]:max-w-full [&_p]:mb-2 [&_ul]:my-2 [&_ol]:my-2 [&_h1]:text-base [&_h2]:text-sm [&_h3]:text-sm [&_h1]:font-bold [&_h2]:font-bold [&_h3]:font-semibold">
                        {!! $enBody !== '' ? $enBody : '<p class="text-slate-400">—</p>' !!}
                    </div>
                </div>
            </article>

            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2">
                @if ($item->is_published && $slugEn !== '')
                    <a href="{{ route('news.show', ['locale' => 'en', 'slug' => $slugEn]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-xs font-bold text-primary ring-1 ring-slate-200 hover:bg-sky-50">
                        Situs EN ↗
                    </a>
                @elseif (! $item->is_published && $slugEn !== '')
                    <p class="text-[10px] text-slate-600">Slug: <code class="rounded bg-white px-1 py-px text-[9px] ring-1 ring-slate-200">{{ $slugEn }}</code></p>
                @endif
                <button type="button" data-news-tab-trigger="id" class="border-0 bg-transparent p-0 text-xs font-semibold text-slate-600 underline hover:text-primary">← Konten ID</button>
            </div>
        </div>
    @endif
</div>

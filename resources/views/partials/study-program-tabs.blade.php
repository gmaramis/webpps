{{--
    Tab program studi (dipakai halaman /s2 dan /s3).

    @var list<array<string, mixed>> $programs
    @var int $activeTabIndex
    @var string $tabPrefix  Unik per halaman, mis. 's2' atau 's3' (untuk id tab/panel)
    @var string $programPagePath  Path URL untuk history.replaceState, mis. '/s2'
    @var string $selectedSlug
    @var bool $invalidProgramSelection
    @var string $tablistAriaLabel  aria-label untuk tablist
    @var string $invalidUrlMessage  Teks peringatan slug URL tidak valid
--}}
@php
    $loc = $loc ?? app()->getLocale();
@endphp
@if($invalidProgramSelection)
    <p class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">{{ $invalidUrlMessage }}</p>
@endif

<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" data-study-program-tabs data-program-path="{{ $programPagePath }}" data-initial-slug="{{ $selectedSlug }}">
    <div class="flex flex-wrap gap-1 border-b border-slate-200 bg-slate-50/90 px-2 pt-2" role="tablist" aria-label="{{ $tablistAriaLabel }}">
        @foreach($programs as $idx => $p)
            @php
                $slug = (string) ($p['slug'] ?? '');
                $name = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                $isTabActive = $idx === $activeTabIndex;
                $tabId = $tabPrefix.'-tab-'.$idx;
                $panelId = $tabPrefix.'-panel-'.$idx;
            @endphp
            <button type="button"
                id="{{ $tabId }}"
                role="tab"
                aria-selected="{{ $isTabActive ? 'true' : 'false' }}"
                aria-controls="{{ $panelId }}"
                tabindex="{{ $isTabActive ? 0 : -1 }}"
                data-tab-slug="{{ $slug }}"
                class="study-program-tab {{ $isTabActive ? 'study-program-tab--active' : '' }}">{{ $name }}</button>
        @endforeach
    </div>
    <div class="bg-white">
        @foreach($programs as $idx => $p)
            @php
                $slug = (string) ($p['slug'] ?? '');
                $pName = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                $pBlurb = $p['blurb'][$loc] ?? $p['blurb']['id'] ?? '';
                $official = isset($p['official_url']) && is_string($p['official_url']) ? trim($p['official_url']) : '';
                $isTabActive = $idx === $activeTabIndex;
                $tabId = $tabPrefix.'-tab-'.$idx;
                $panelId = $tabPrefix.'-panel-'.$idx;
            @endphp
            <div id="{{ $panelId }}"
                role="tabpanel"
                aria-labelledby="{{ $tabId }}"
                @if(! $isTabActive) hidden @endif
                class="border-t border-slate-100 p-5 md:p-8">
                <h3 class="font-display text-xl font-bold text-primary md:text-2xl">{{ $pName }}</h3>
                <p class="mt-4 text-base leading-relaxed text-slate-700">{{ $pBlurb }}</p>
                @if($official !== '')
                    <p class="mt-6">
                        <a href="{{ $official }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-dark">{{ $loc === 'id' ? 'Situs web resmi prodi' : 'Official programme website' }} ↗</a>
                    </p>
                @else
                    <p class="mt-6 text-sm text-slate-500">{{ $loc === 'id' ? 'Tautan situs resmi prodi belum diatur.' : 'The official programme website link has not been set yet.' }}</p>
                @endif
                @php
                    $brochureUrl = isset($p['brochure_image_url']) && is_string($p['brochure_image_url']) ? trim($p['brochure_image_url']) : '';
                @endphp
                @if($brochureUrl !== '')
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $loc === 'id' ? 'Brosur pendaftaran' : 'Admission brochure' }}</p>
                        <button type="button"
                            class="group mt-3 inline-flex max-w-full flex-col gap-1 rounded-xl border border-slate-200 bg-slate-50/80 p-2 text-left shadow-sm transition hover:border-primary/40 hover:bg-sky-50/80 focus:outline-none focus:ring-2 focus:ring-primary/30"
                            data-program-brochure-lightbox-src="{{ e($brochureUrl) }}"
                            data-program-brochure-lightbox-alt="{{ e(($loc === 'id' ? 'Brosur ' : 'Brochure: ').$pName) }}">
                            <span class="sr-only">{{ $loc === 'id' ? 'Buka brosur ukuran penuh' : 'Open brochure at full size' }}</span>
                            <img src="{{ e($brochureUrl) }}" alt="" class="h-28 max-h-32 w-auto max-w-[14rem] rounded-lg border border-slate-200/90 object-contain object-left shadow-sm transition group-hover:border-primary/25" width="224" height="112" loading="lazy" decoding="async">
                            <span class="text-[11px] font-semibold text-primary underline decoration-primary/30 underline-offset-2 group-hover:decoration-primary">{{ $loc === 'id' ? 'Klik untuk memperbesar' : 'Click to enlarge' }}</span>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

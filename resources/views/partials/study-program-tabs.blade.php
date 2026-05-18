{{--
    Navigasi program studi: select (mobile) + sidebar (desktop) + panel konten.
--}}
@php
    $loc = $loc ?? app()->getLocale();
    $selectId = $selectId ?? ($tabPrefix.'-program-select');
    $selectLabel = $loc === 'id' ? 'Pilih program studi' : 'Select a study programme';
@endphp

@if($invalidProgramSelection)
    <p class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">{{ $invalidUrlMessage }}</p>
@endif

<div class="study-program-explorer mt-6 md:mt-8" data-study-program-tabs data-program-path="{{ $programPagePath }}" data-initial-slug="{{ $selectedSlug }}">
    
        <div class="study-program-picker lg:hidden">
            <label for="{{ $selectId }}" class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">{{ $selectLabel }}</label>
            <select id="{{ $selectId }}" data-study-program-select class="study-program-picker__select w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-primary shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                @foreach($programs as $idx => $p)
                    @php
                        $slug = (string) ($p['slug'] ?? '');
                        $name = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                    @endphp
                    <option value="{{ $slug }}" data-tab-index="{{ $idx }}" @selected($idx === $activeTabIndex)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="study-program-layout">
            <nav class="study-program-nav hidden lg:block" role="tablist" aria-label="{{ $tablistAriaLabel }}" aria-orientation="vertical">
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
                        data-tab-index="{{ $idx }}"
                        class="study-program-nav__item {{ $isTabActive ? 'study-program-nav__item--active' : '' }}">{{ $name }}</button>
                @endforeach
            </nav>

            <div class="study-program-panels min-w-0">
                @foreach($programs as $idx => $p)
                    @php
                        $slug = (string) ($p['slug'] ?? '');
                        $pName = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                        $pBlurbRaw = $p['blurb'][$loc] ?? $p['blurb']['id'] ?? '';
                        $pBlurbHtml = \App\Support\StudyProgramBlurb::toHtml((string) $pBlurbRaw);
                        $official = isset($p['official_url']) && is_string($p['official_url']) ? trim($p['official_url']) : '';
                        $isTabActive = $idx === $activeTabIndex;
                        $tabId = $tabPrefix.'-tab-'.$idx;
                        $panelId = $tabPrefix.'-panel-'.$idx;
                    @endphp
                    <article id="{{ $panelId }}"
                        role="tabpanel"
                        aria-labelledby="{{ $tabId }}"
                        @if(! $isTabActive) hidden @endif
                        class="study-program-panel overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-slate-50/60 px-5 py-4 md:px-8 md:py-5">
                            <h3 class="font-display text-xl font-bold leading-snug text-primary md:text-2xl">{{ $pName }}</h3>
                        </div>
                        <div class="px-5 py-6 md:px-8 md:py-8">
                            @if($pBlurbHtml !== '')
                                
                                    <div class="study-program-prose text-base leading-relaxed text-slate-700">{!! $pBlurbHtml !!}</div>
                            @endif

                            @if($official !== '')
                                <p class="mt-8">
                                    <a href="{{ $official }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-dark">{{ $loc === 'id' ? 'Situs web resmi prodi' : 'Official programme website' }} <span aria-hidden="true" class="ml-1">↗</span></a>
                                </p>
                            @else
                                <p class="mt-8 text-sm text-slate-500">{{ $loc === 'id' ? 'Tautan situs resmi prodi belum diatur.' : 'The official programme website link has not been set yet.' }}</p>
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
                    </article>
                @endforeach
            </div>
        </div>
    </div>


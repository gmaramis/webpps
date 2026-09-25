@php
    $loc = app()->getLocale();
    $languages = [
        'id' => ['label' => 'Indonesia', 'flag' => 'images/flags/id.svg'],
        'en' => ['label' => 'English', 'flag' => 'images/flags/gb.svg'],
        'zh' => ['label' => '中文', 'flag' => 'images/flags/cn.svg'],
    ];
    $currentLang = $languages[$loc] ?? $languages['id'];
@endphp
<div class="site-header-wrap sticky top-0 z-[100] border-b border-black/20 shadow-md shadow-black/20">
    {{-- Hanya identitas: logo UNIMA + nama institusi + bahasa + tombol menu (mobile) --}}
    <header class="site-header site-header-brand border-b border-white/15">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex items-center justify-between gap-3 py-3 md:gap-4 md:py-3.5">
                <a href="{{ route('home') }}"
                    class="flex min-w-0 flex-1 items-center gap-3 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[rgb(0,0,230)]">
                    <img src="{{ asset('logo-unima.png') }}" alt="Logo Universitas Negeri Manado" width="52"
                        height="52" class="h-12 w-12 shrink-0 object-contain drop-shadow-sm">
                    <div class="min-w-0 leading-tight">
                        <span
                            class="block font-display text-base font-bold tracking-tight text-white sm:text-lg">{{ $t['brandTitle'] }}</span>
                        <span
                            class="mt-0.5 block truncate text-[11px] font-medium text-sky-100 sm:text-xs">{{ $t['brandSub'] }}</span>
                    </div>
                </a>
                <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                    <div class="relative" aria-label="Bahasa">
                        <details class="nav-details language-dropdown notranslate relative" translate="no">
                            <summary
                                class="flex cursor-pointer list-none items-center gap-1.5 rounded-lg border border-white/20 bg-white/10 px-2 py-1 text-xs font-semibold text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:gap-2 sm:px-2.5 sm:py-1.5"
                                aria-label="Ganti bahasa ({{ $currentLang['label'] }})">
                                <img src="{{ asset($currentLang['flag']) }}" alt="{{ $currentLang['label'] }}"
                                    width="20" height="15"
                                    class="h-3.5 w-5 shrink-0 rounded-[2px] object-cover shadow-xs border border-white/20">
                                <svg class="nav-details-chevron h-3.5 w-3.5 text-white/80" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </summary>
                            <ul
                                class="absolute right-0 top-full z-[210] mt-1.5 min-w-[9.5rem] rounded-lg border border-white/25 bg-[#001a99] py-1.5 shadow-xl ring-1 ring-black/40">
                                @foreach ($languages as $code => $lang)
                                    <li>
                                        <a href="{{ route('locale.switch', ['locale' => $code]) }}"
                                            class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium transition {{ $loc === $code ? 'bg-white/20 text-white font-semibold' : 'text-sky-100 hover:bg-white/10 hover:text-white' }}">
                                            <img src="{{ asset($lang['flag']) }}" alt="{{ $lang['label'] }}"
                                                width="20" height="15"
                                                class="h-3.5 w-5 shrink-0 rounded-[2px] object-cover shadow-xs border border-white/20">
                                            <span>{{ $lang['label'] }}</span>
                                            @if ($loc === $code)
                                                <svg class="ml-auto h-3.5 w-3.5 text-white" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </div>
                    <button type="button" id="mobile-menu-toggle"
                        class="nav-top-link inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white lg:hidden"
                        aria-expanded="false" aria-controls="mobile-menu" aria-label="{{ $t['navMenuToggle'] }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Menu navigasi di bawah bar logo/identitas --}}
    <div class="site-header site-header-nav-strip">
        <div class="mx-auto max-w-6xl px-4">
            <nav class="hidden py-2 lg:block" aria-label="{{ $t['navAriaMain'] }}">
                <ul class="m-0 flex list-none flex-wrap items-center gap-x-0.5 gap-y-1 p-0 lg:gap-x-1">
                    @foreach ($ppsData['NAV'] ?? [] as $item)
                        @if (isset($item['children']))
                            <li class="group nav-dropdown relative">
                                <button type="button"
                                    class="nav-dropdown-toggle nav-top-link flex w-full items-center gap-1 rounded-lg px-3 py-2 text-left text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white lg:w-auto lg:justify-center"
                                    aria-expanded="false" aria-haspopup="true"
                                    aria-controls="nav-dd-{{ $loop->index }}">
                                    <span>{{ $item['label'][$loc] }}</span>
                                    <svg class="h-3.5 w-3.5 shrink-0 opacity-80" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul id="nav-dd-{{ $loop->index }}" role="menu"
                                    class="nav-dropdown-panel absolute left-0 top-full z-[200] mt-0 min-w-[15rem] rounded-lg border border-white/30 bg-[#001a99] py-2 shadow-xl ring-1 ring-black/45">
                                    @foreach ($item['children'] as $c)
                                        <li role="none">
                                            <a role="menuitem" href="{{ $c['href'] }}"
                                                @if (\Illuminate\Support\Str::startsWith($c['href'], ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
                                                class="nav-sub-link block px-4 py-2 text-sm text-white/95 transition hover:bg-white/20 hover:text-white">{{ $c['label'][$loc] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $item['href'] }}"
                                    @if (\Illuminate\Support\Str::startsWith($item['href'], ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
                                    class="nav-top-link block rounded-lg px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white">{{ $item['label'][$loc] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <div id="mobile-menu"
                class="nav-mobile-panel hidden max-h-[min(70vh,calc(100dvh-8rem))] overflow-y-auto lg:hidden">
                <nav class="border-t border-white/15 px-1 pb-3 pt-2" aria-label="{{ $t['navAriaMain'] }}">
                    <ul class="m-0 flex list-none flex-col gap-0 p-0">
                        @foreach ($ppsData['NAV'] ?? [] as $item)
                            @if (isset($item['children']))
                                <li class="border-b border-white/10 py-1">
                                    <details class="nav-details">
                                        <summary
                                            class="flex cursor-pointer list-none items-center justify-between gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-white hover:bg-white/10">
                                            <span>{{ $item['label'][$loc] }}</span>
                                            <span class="inline-flex"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg></span>
                                        </summary>
                                        <ul class="m-0 list-none space-y-0.5 border-l border-white/20 pb-2 pl-4 pt-0">
                                            @foreach ($item['children'] as $c)
                                                <li><a href="{{ $c['href'] }}"
                                                        @if (\Illuminate\Support\Str::startsWith($c['href'], ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
                                                        class="mobile-nav-sublink block rounded-md px-3 py-2 text-sm text-sky-100/95 hover:bg-white/10 hover:text-white">{{ $c['label'][$loc] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </details>
                                </li>
                            @else
                                <li class="border-b border-white/10 last:border-b-0">
                                    <a href="{{ $item['href'] }}"
                                        @if (\Illuminate\Support\Str::startsWith($item['href'], ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
                                        class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white hover:bg-white/10">{{ $item['label'][$loc] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

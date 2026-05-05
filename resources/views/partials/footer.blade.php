<footer class="site-footer border-t border-black/20 text-slate-200">
    <div class="mx-auto grid w-full max-w-6xl grid-cols-1 gap-8 px-4 py-10 md:grid-cols-[1.2fr_1fr_1fr] md:items-start">
        <div class="flex gap-4">
            <img src="{{ asset('logo-unima.png') }}" alt="Logo Universitas Negeri Manado" width="48" height="48" class="h-12 w-12 shrink-0 object-contain opacity-95">
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-white">{{ $t['contactTitle'] }}</h3>
                <address class="not-italic text-sm leading-relaxed text-slate-200">{!! $t['addressHtml'] !!}</address>
            </div>
        </div>
        <div>
            <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-white">{{ $t['footerAcademic'] }}</h3>
            <ul class="m-0 flex list-none flex-col gap-2 p-0">
                <li><a href="{{ data_get($ppsData, 'ACADEMIC_EXTERNAL_URLS.lms', 'https://lms.unima.ac.id/') }}" target="_blank" rel="noopener noreferrer" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkElearning'] }}</a></li>
                <li><a href="#" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkSister'] }}</a></li>
                <li><a href="#" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkJurnal'] }}</a></li>
            </ul>
        </div>
        <div>
            <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-white">{{ $t['footerUnima'] }}</h3>
            <ul class="m-0 flex list-none flex-col gap-2 p-0">
                <li><a href="https://www.unima.ac.id" target="_blank" rel="noopener noreferrer" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkWebUtama'] }}</a></li>
                <li><a href="#" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkBeasiswa'] }}</a></li>
                <li><a href="#" class="text-sm text-slate-200 no-underline hover:text-white hover:underline">{{ $t['linkPmb'] }}</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-slate-300">{{ $t['copyright'] }}</div>
</footer>

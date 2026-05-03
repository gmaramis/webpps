@extends('layouts.app')

@section('title', ($t['lecturersTitle'] ?? 'Dosen').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $lecturersLead = $loc === 'id'
        ? 'Cari dan urutkan daftar dosen Pascasarjana UNIMA. Profil dilengkapi foto, nama, NIDN, NIP, program studi, jabatan fungsional, dan kontak.'
        : 'Search and sort UNIMA Graduate School faculty. Each profile includes a photo, name, NIDN, NIP, study program, academic rank, and contact.';
    $lecturerRows = collect($ppsData['LECTURERS'] ?? [])->map(function ($l) use ($loc) {
        $photo = $l['photo'] ?? '';
        $photoUrl = \App\Models\Lecturer::publicPhotoUrl($photo);

        return [
            'name' => $l['name'][$loc] ?? $l['name']['id'] ?? '',
            'nidn' => $l['nidn'] ?? '',
            'nip' => $l['nip'] ?? '',
            'program' => $l['studyProgram'][$loc] ?? $l['studyProgram']['id'] ?? '',
            'functional' => $l['functionalRole'][$loc] ?? $l['functionalRole']['id'] ?? '',
            'phone' => $l['phone'] ?? '',
            'photoUrl' => $photoUrl,
        ];
    })->values();
    $pageTpl = $t['lecturersPaginationSummary'] ?? 'Halaman {page} dari {pages}';
    $emptyMsg = $t['lecturersEmptySearch'] ?? '';
@endphp

@push('scripts')
    @vite(['resources/js/lecturers-table.js'])
@endpush

@section('content')
<main id="main" class="relative pb-16 pt-6 md:pb-24 md:pt-8">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-sky-100/90 via-sky-50/40 to-transparent" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-6xl px-4">
        <header class="mb-10 text-center md:mb-12 md:text-left">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['lecturersEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl lg:text-[2.35rem]">{{ $t['lecturersTitle'] }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-slate-600 md:mx-0 md:text-lg">{{ $lecturersLead }}</p>
        </header>

        <div
            id="lecturers-root"
            class="lecturers-panel overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-[0_20px_50px_-24px_rgb(15_23_42/0.18)] ring-1 ring-slate-200/60"
            data-lecturers='@json($lecturerRows)'
            data-page-template="{{ e($pageTpl) }}"
            data-empty-msg="{{ e($emptyMsg) }}"
        >
            <div class="border-b border-slate-100 bg-gradient-to-br from-slate-50/95 via-white to-sky-50/30 px-4 py-5 md:px-6 md:py-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <label class="block w-full flex-1 lg:max-w-md">
                        <span class="mb-1.5 flex items-center gap-2 text-sm font-semibold text-slate-800">
                            <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            {{ $t['lecturersSearchLabel'] }}
                        </span>
                        <input id="lecturer-search" type="search" autocomplete="off" placeholder="{{ $t['lecturersSearchPlaceholder'] }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm outline-none ring-primary/25 transition placeholder:text-slate-400 focus:border-primary focus:ring-2">
                    </label>
                    <div class="flex flex-wrap items-end gap-3 md:gap-4">
                        <label class="min-w-[10.5rem] flex-1 text-sm font-semibold text-slate-800 sm:flex-initial">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">{{ $loc === 'id' ? 'Urutkan' : 'Sort' }}</span>
                            <select id="lecturer-sort" class="w-full cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none ring-primary/20 focus:border-primary focus:ring-2 md:min-w-[13rem]">
                                <option value="name">{{ $t['lecturersSortByName'] }}</option>
                                <option value="nidn">{{ $t['lecturersColNidn'] }}</option>
                                <option value="nip">{{ $t['lecturersSortByNip'] }}</option>
                                <option value="functional">{{ $t['lecturersSortByFunctional'] }}</option>
                                <option value="program">{{ $t['lecturersColProgram'] }}</option>
                            </select>
                        </label>
                        <label class="w-full min-w-[5.5rem] text-sm font-semibold text-slate-800 sm:w-auto">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">{{ $loc === 'id' ? 'Per halaman' : 'Per page' }}</span>
                            <select id="lecturer-page-size" class="w-full cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-primary focus:ring-2">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto px-1 pb-1 md:px-2">
                <table class="lecturers-table w-full min-w-[820px] border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary to-primary-light text-[11px] font-bold uppercase tracking-wider text-white shadow-inner">
                            <th class="rounded-tl-xl px-4 py-3.5 pl-5 md:w-[5.5rem]">{{ $t['lecturersColPhoto'] }}</th>
                            <th class="px-4 py-3.5">{{ $t['lecturersColName'] }}</th>
                            <th class="px-4 py-3.5">{{ $t['lecturersColNidn'] }}</th>
                            <th class="px-4 py-3.5">{{ $t['lecturersColNip'] }}</th>
                            <th class="min-w-[8rem] px-4 py-3.5">{{ $t['lecturersColProgram'] }}</th>
                            <th class="min-w-[7rem] px-4 py-3.5">{{ $t['lecturersColFunctional'] }}</th>
                            <th class="rounded-tr-xl px-4 py-3.5 pr-5">{{ $t['lecturersColPhone'] }}</th>
                        </tr>
                    </thead>
                    <tbody id="lecturers-tbody" class="divide-y divide-slate-100 bg-white text-slate-700"></tbody>
                </table>
            </div>

            <div class="flex flex-col items-stretch justify-between gap-4 border-t border-slate-100 bg-slate-50/80 px-4 py-4 md:flex-row md:items-center md:px-6">
                <p id="lecturer-page-info" class="text-center text-sm font-medium text-slate-600 md:text-left"></p>
                <div class="flex justify-center gap-2 sm:justify-end">
                    <button type="button" id="lecturer-prev" class="inline-flex min-w-[6.5rem] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition hover:border-primary/30 hover:bg-sky-50 disabled:pointer-events-none disabled:opacity-40">{{ $t['lecturersPagePrev'] }}</button>
                    <button type="button" id="lecturer-next" class="inline-flex min-w-[6.5rem] items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-primary/25 transition hover:bg-primary-dark disabled:pointer-events-none disabled:opacity-40">{{ $t['lecturersPageNext'] }}</button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

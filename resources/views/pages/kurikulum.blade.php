@extends('layouts.app')

@section('title', ($t['kurikulumPageTitle'] ?? 'Kurikulum Program Studi').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $emptyText = $t['kurikulumEmpty'] ?? 'Belum ada dokumen kurikulum untuk program ini.';
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 rounded-3xl border border-sky-100 bg-gradient-to-br from-sky-50 via-white to-indigo-50 p-6 shadow-sm md:p-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['kurikulumNavLabel'] ?? 'Kurikulum' }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 md:text-4xl">{{ $t['kurikulumPageTitle'] ?? 'Kurikulum Program Studi' }}</h1>
            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600 md:text-base">{{ $t['kurikulumPageLead'] ?? 'Jelajahi dokumen kurikulum setiap program studi. Setiap berkas dapat dipratinjau langsung di halaman ini dan diunduh untuk referensi belajar.' }}</p>
        </header>

        @if (($kurikulumSchemaMissing ?? false) === true)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Data kurikulum belum siap. Jalankan migrasi basis data terlebih dahulu.
            </div>
        @endif

        @php
            $groups = [
                ['title' => $t['kurikulumSectionMagister'] ?? 'Program Magister (S2)', 'rows' => $s2Programs ?? collect(), 'type' => 's2'],
                ['title' => $t['kurikulumSectionDoktor'] ?? 'Program Doktor (S3)', 'rows' => $s3Programs ?? collect(), 'type' => 's3'],
            ];
        @endphp

        <div class="space-y-10">
            @foreach ($groups as $group)
                <section>
                    <h2 class="mb-4 text-xl font-bold text-slate-900 md:text-2xl">{{ $group['title'] }}</h2>

                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach ($group['rows'] as $program)
                            @php
                                $curriculum = $program->studyProgramCurriculum;
                                $pdfUrl = $curriculum?->resolvedPdfUrl() ?? '';
                                $programName = $loc === 'en'
                                    ? (trim((string) ($program->name_en ?? '')) !== '' ? $program->name_en : $program->name_id)
                                    : $program->name_id;
                            @endphp
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4">
                                    <h3 class="line-clamp-2 text-base font-semibold text-slate-900">{{ $programName }}</h3>
                                </div>

                                @if ($pdfUrl !== '')
                                    <div class="p-4">
                                        <div class="h-[360px] overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            <iframe src="{{ $pdfUrl }}#view=FitH" class="h-full w-full" loading="lazy" title="PDF Kurikulum {{ $programName }}"></iframe>
                                        </div>
                                        <div class="mt-3 flex items-center gap-2">
                                            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">{{ $t['kurikulumPreview'] ?? 'Pratinjau' }}</a>
                                            <a href="{{ $pdfUrl }}" download class="inline-flex rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white hover:bg-primary-dark">{{ $t['kurikulumDownload'] ?? 'Unduh PDF' }}</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="px-5 py-10 text-center text-sm text-slate-500">
                                        {{ $emptyText }}
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</main>
@endsection

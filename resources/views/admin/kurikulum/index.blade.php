@extends('admin.layouts.app')

@section('title', 'Kurikulum program studi')
@section('heading', 'Kurikulum program studi')

@section('content')
<div class="w-full min-w-0 space-y-6">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Kurikulum</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">Unggah file <strong class="text-slate-800">PDF</strong> kurikulum per program studi. File terbaru akan otomatis menggantikan file lama.</p>
        <a href="{{ route('kurikulum') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Lihat halaman publik ↗</a>
    </div>

    @php
        $sections = [
            [
                'title' => 'Program Magister (S2)',
                'rows' => $s2Programs,
                'uploadRoute' => 'admin.kurikulum.s2.update',
                'deleteRoute' => 'admin.kurikulum.s2.destroy',
                'nameField' => 'name_id',
                'enField' => 'name_en',
            ],
            [
                'title' => 'Program Doktor (S3)',
                'rows' => $s3Programs,
                'uploadRoute' => 'admin.kurikulum.s3.update',
                'deleteRoute' => 'admin.kurikulum.s3.destroy',
                'nameField' => 'name_id',
                'enField' => 'name_en',
            ],
        ];
    @endphp

    @foreach ($sections as $section)
        <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
            <header class="border-b border-slate-100 bg-slate-50/90 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">{{ $section['title'] }}</h2>
            </header>

            <div class="divide-y divide-slate-100">
                @forelse ($section['rows'] as $program)
                    @php
                        $curr = $program->studyProgramCurriculum;
                        $pdfUrl = $curr?->resolvedPdfUrl() ?? '';
                        $nameEn = trim((string) ($program->{$section['enField']} ?? ''));
                        $displayName = $program->{$section['nameField']};
                    @endphp
                    <article class="grid gap-4 px-6 py-5 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div class="min-w-0">
                            <h3 class="truncate text-base font-semibold text-slate-900">{{ $displayName }}</h3>
                            @if ($nameEn !== '')
                                <p class="truncate text-xs text-slate-500">{{ $nameEn }}</p>
                            @endif
                            @if ($pdfUrl !== '')
                                <p class="mt-2 text-xs text-emerald-700">PDF tersedia.</p>
                                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex text-xs font-semibold text-primary hover:underline">Buka file saat ini</a>
                            @else
                                <p class="mt-2 text-xs text-amber-700">Belum ada file PDF.</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <form method="post" action="{{ route($section['uploadRoute'], $program) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                @csrf
                                <input type="file" name="pdf" accept="application/pdf" required class="block w-full text-xs text-slate-600 file:mr-2 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200 sm:w-auto">
                                <button type="submit" class="inline-flex rounded-lg bg-primary px-3 py-2 text-xs font-bold text-white hover:bg-primary-dark">Simpan PDF</button>
                            </form>

                            @if ($curr !== null)
                                <form method="post" action="{{ route($section['deleteRoute'], $program) }}" onsubmit="return confirm('Hapus file kurikulum untuk program ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-100">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="px-6 py-10 text-sm text-slate-500">Belum ada data program studi.</p>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
@endsection

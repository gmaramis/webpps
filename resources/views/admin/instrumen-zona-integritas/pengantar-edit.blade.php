@extends('admin.layouts.app')

@section('title', 'ZI — Pengantar')
@section('heading', 'Instrumen ZI — teks pengantar')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.zi.hub') }}" class="font-semibold text-primary hover:underline">Instrumen ZI</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Pengantar</span>
    </nav>

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <p class="mb-6 text-sm text-slate-600">Konten kotak pengantar di bawah judul halaman. Jika dikosongkan lalu disimpan, halaman publik memakai teks dari <code class="rounded bg-slate-100 px-1 text-xs">STRINGS</code> di JSON.</p>
        <form method="post" action="{{ route('admin.zi.pengantar.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')
            <div>
                <label for="intro_heading_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul blok pengantar (ID)</label>
                <input id="intro_heading_id" type="text" name="intro_heading_id" required value="{{ old('intro_heading_id', $content->intro_heading_id) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_heading_id') border-rose-400 @enderror">
                @error('intro_heading_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="intro_heading_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (EN) <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="intro_heading_en" type="text" name="intro_heading_en" value="{{ old('intro_heading_en', $content->intro_heading_en) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_heading_en') border-rose-400 @enderror">
                @error('intro_heading_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="intro_p1_id" class="mb-1 block text-xs font-semibold text-slate-700">Paragraf 1 (ID)</label>
                <textarea id="intro_p1_id" name="intro_p1_id" rows="5" required maxlength="8000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_p1_id') border-rose-400 @enderror">{{ old('intro_p1_id', $content->intro_p1_id) }}</textarea>
                @error('intro_p1_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="intro_p1_en" class="mb-1 block text-xs font-semibold text-slate-700">Paragraf 1 (EN)</label>
                <textarea id="intro_p1_en" name="intro_p1_en" rows="5" maxlength="8000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_p1_en') border-rose-400 @enderror">{{ old('intro_p1_en', $content->intro_p1_en) }}</textarea>
                @error('intro_p1_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="intro_p2_id" class="mb-1 block text-xs font-semibold text-slate-700">Paragraf 2 (ID)</label>
                <textarea id="intro_p2_id" name="intro_p2_id" rows="5" required maxlength="8000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_p2_id') border-rose-400 @enderror">{{ old('intro_p2_id', $content->intro_p2_id) }}</textarea>
                @error('intro_p2_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="intro_p2_en" class="mb-1 block text-xs font-semibold text-slate-700">Paragraf 2 (EN)</label>
                <textarea id="intro_p2_en" name="intro_p2_en" rows="5" maxlength="8000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('intro_p2_en') border-rose-400 @enderror">{{ old('intro_p2_en', $content->intro_p2_en) }}</textarea>
                @error('intro_p2_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">Simpan</button>
                <a href="{{ route('admin.zi.hub') }}" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

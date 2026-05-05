@extends('admin.layouts.app')

@section('title', 'Edit dokumen akreditasi')
@section('heading', 'Edit dokumen akreditasi')

@section('content')
<div class="w-full min-w-0 space-y-6">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.dokumen-akreditasi.index') }}" class="font-semibold text-primary hover:underline">Dokumen akreditasi</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Edit</span>
    </nav>

    <div class="mx-auto max-w-2xl rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <form method="post" action="{{ route('admin.dokumen-akreditasi.update', $document) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            @php
                $in = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15';
                $lbl = 'mb-1 block text-sm font-semibold text-slate-800';
            @endphp
            <div>
                <label for="sort_order" class="{{ $lbl }}">Urutan</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $document->sort_order) }}" min="0" class="w-32 rounded-lg border border-slate-200 px-3 py-2 text-sm @error('sort_order') border-rose-400 @enderror">
                @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="title_id" class="{{ $lbl }}">Judul dokumen (Bahasa Indonesia)</label>
                <input id="title_id" type="text" name="title_id" value="{{ old('title_id', $document->title_id) }}" class="{{ $in }} @error('title_id') border-rose-400 @enderror" required>
                @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="title_en" class="{{ $lbl }}">Judul (English) <span class="font-normal text-slate-500">(opsional)</span></label>
                <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $document->title_en) }}" class="{{ $in }} @error('title_en') border-rose-400 @enderror">
                @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input id="is_published" type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-primary focus:ring-primary/30" {{ old('is_published', $document->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="text-sm font-medium text-slate-800">Tampilkan di situs publik</label>
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3 text-xs text-slate-600">
                <p class="font-semibold text-slate-800">Berkas saat ini</p>
                <p class="mt-1 font-mono break-all">{{ $document->file_path }}</p>
                <a href="{{ asset(ltrim($document->resolvedFilePublicPath(), '/')) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-xs font-bold text-primary hover:underline">Buka PDF ↗</a>
            </div>
            <div>
                <label for="pdf" class="{{ $lbl }}">Ganti PDF <span class="font-normal text-slate-500">(opsional)</span></label>
                <input id="pdf" type="file" name="pdf" accept=".pdf,application/pdf" class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-xs file:font-bold file:text-white @error('pdf') border border-rose-400 rounded-lg @enderror">
                <p class="mt-1 text-xs text-slate-500">Kosongkan bila tidak ingin mengganti berkas.</p>
                @error('pdf')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">Simpan</button>
                <a href="{{ route('admin.dokumen-akreditasi.index') }}" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

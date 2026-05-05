@extends('admin.layouts.app')

@section('title', 'Ubah halaman Stop Gratifikasi')
@section('heading', 'Ubah halaman Stop Gratifikasi')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-2 text-sm text-slate-600">
        <nav class="flex flex-wrap items-center gap-2" aria-label="Jejak navigasi">
            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('admin.stop-gratifikasi.hub') }}" class="font-semibold text-primary hover:underline">Stop Gratifikasi</a>
            <span aria-hidden="true">/</span>
            <span class="font-medium text-slate-900">Ubah teks</span>
        </nav>
        <a href="{{ route('stop-gratifikasi') }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center rounded-full border border-slate-200/90 bg-white px-3 py-1.5 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Lihat hasil ↗</a>
    </div>

    @if(! ($ready ?? false))
        <div class="rounded-2xl border border-amber-200/90 bg-gradient-to-br from-amber-50 to-white px-5 py-5 text-sm text-amber-950 shadow-md">
            <p class="font-display-admin font-bold text-amber-900">Tabel basis data belum ada</p>
            <pre class="mt-2 overflow-x-auto rounded-lg bg-amber-950/10 px-3 py-2 font-mono text-xs">php artisan migrate</pre>
        </div>
    @else
        @php /** @var \App\Models\StopGratifikasiPageContent $page */ @endphp
        @php
            $ta = 'w-full min-h-[2.75rem] max-h-32 resize-y rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm leading-snug focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15';
            $in = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15';
            $lbl = 'mb-1 block text-sm font-semibold text-slate-800';
            $hasEn = collect([
                $page->title_en,
                $page->simple_body_en,
            ])->filter(fn ($v) => $v !== null && trim((string) $v) !== '')->isNotEmpty();
        @endphp
        <div class="mx-auto max-w-2xl rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-md ring-1 ring-white/70 md:p-5">
            <p class="mb-4 text-sm text-slate-600">Isian di bawah untuk tampilan pengunjung di halaman <strong class="text-slate-900">Stop Gratifikasi</strong>. Kosongkan suatu kotak bila ingin memakai teks bawaan situs.</p>

            <form method="post" action="{{ route('admin.stop-gratifikasi.konten.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <fieldset class="space-y-4 rounded-xl border border-slate-100 bg-slate-50/40 p-4">
                    <legend class="px-1 text-xs font-bold uppercase tracking-wide text-primary">Bahasa Indonesia</legend>

                    <div>
                        <label for="title_id" class="{{ $lbl }}">Judul halaman</label>
                        <input id="title_id" type="text" name="title_id" value="{{ old('title_id', $page->title_id) }}" class="{{ $in }} @error('title_id') border-rose-400 @enderror" required>
                        @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="simple_body_id" class="{{ $lbl }}">Isi berita atau paragraf</label>
                        <textarea id="simple_body_id" name="simple_body_id" rows="6" class="{{ $ta }} @error('simple_body_id') border-rose-400 @enderror" required>{{ old('simple_body_id', $page->isiUntukForm()) }}</textarea>
                        @error('simple_body_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="link_instrumen_zi_url" class="{{ $lbl }}">Alamat tombol utama (Instrumen ZI) <span class="font-normal text-slate-500">(boleh kosong — pakai halaman ZI internal)</span></label>
                        <input id="link_instrumen_zi_url" type="text" name="link_instrumen_zi_url" value="{{ old('link_instrumen_zi_url', $page->link_instrumen_zi_url) }}" placeholder="https://… atau /path" class="{{ $in }} font-mono text-xs @error('link_instrumen_zi_url') border-rose-400 @enderror">
                        @error('link_instrumen_zi_url')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </fieldset>

                <details class="rounded-xl border border-slate-200 bg-white p-1 shadow-sm" @if($hasEn) open @endif>
                    <summary class="cursor-pointer list-none rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-50 [&::-webkit-details-marker]:hidden">
                        <span class="inline-flex items-center gap-2">
                            <span class="text-primary">▸</span>
                            Bahasa Inggris <span class="font-normal text-slate-500">(hanya jika situs Anda pakai bahasa Inggris)</span>
                        </span>
                    </summary>
                    <div class="space-y-4 border-t border-slate-100 p-4 pt-3">
                        <p class="text-xs text-slate-500">Urutan sama seperti di atas. Semua boleh dikosongkan.</p>
                        <div>
                            <label for="title_en" class="{{ $lbl }}">Judul halaman</label>
                            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $page->title_en) }}" class="{{ $in }} @error('title_en') border-rose-400 @enderror">
                            @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="simple_body_en" class="{{ $lbl }}">Isi berita atau paragraf</label>
                            <textarea id="simple_body_en" name="simple_body_en" rows="6" class="{{ $ta }} @error('simple_body_en') border-rose-400 @enderror">{{ old('simple_body_en', $page->isiEnUntukForm()) }}</textarea>
                            @error('simple_body_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </details>

                <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                    <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2 text-sm font-bold text-white shadow-md shadow-primary/25 transition hover:brightness-110">Simpan</button>
                    <a href="{{ route('admin.stop-gratifikasi.hub') }}" class="rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Batal</a>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

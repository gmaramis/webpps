@extends('admin.layouts.app')

@section('title', 'Visi & Misi')
@section('heading', 'Visi & Misi')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Visi & Misi</span>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        <div>
            <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">URL admin:</span> <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs font-mono text-slate-800">/admin/visi-misi</code></p>
            <p class="mt-1 text-sm text-slate-600">Halaman publik: <span class="font-semibold text-slate-800">/visi-misi</span> — Bahasa Indonesia &amp; Inggris.</p>
        </div>
        <a href="{{ route('visi-misi') }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-slate-200/90 bg-slate-50/90 px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:bg-white hover:shadow-md">
            Lihat halaman publik ↗
        </a>
    </div>

    @if(! ($visionMissionReady ?? false))
        <div class="rounded-3xl border border-amber-200/90 bg-gradient-to-br from-amber-50 to-white px-6 py-7 text-sm leading-relaxed text-amber-950 shadow-lg shadow-amber-900/[0.04]">
            <p class="font-display-admin font-bold text-amber-900">Tabel basis data untuk Visi &amp; Misi belum ada</p>
            <p class="mt-2 text-amber-900/90">Jalankan migrasi satu kali di folder proyek:</p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-amber-950/10 px-3 py-2 font-mono text-xs text-amber-950">php artisan migrate</pre>
            <p class="mt-3 text-xs text-amber-800/90">Setelah selesai, muat ulang halaman ini — form penyuntingan akan tampil.</p>
        </div>
    @else
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
            <form method="post" action="{{ route('admin.visi-misi.update') }}" class="space-y-8">
                @csrf
                @method('PATCH')

                <section class="space-y-4">
                    <h2 class="border-b border-slate-100/90 pb-2 font-display-admin text-lg font-bold text-primary">Bahasa Indonesia</h2>
                    <div>
                        <label for="vision_id" class="mb-1 block text-xs font-semibold text-slate-700">Visi</label>
                        <textarea id="vision_id" name="vision_id" rows="4" required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('vision_id') border-rose-400 @enderror">{{ old('vision_id', $content->vision_id) }}</textarea>
                        @error('vision_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mission_id" class="mb-1 block text-xs font-semibold text-slate-700">Misi <span class="font-normal text-slate-500">(satu kalimat per baris)</span></label>
                        <textarea id="mission_id" name="mission_id" rows="8" required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('mission_id') border-rose-400 @enderror">{{ old('mission_id', $mission_id_text) }}</textarea>
                        @error('mission_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="values_id" class="mb-1 block text-xs font-semibold text-slate-700">Nilai / budaya <span class="font-normal text-slate-500">(satu butir per baris)</span></label>
                        <textarea id="values_id" name="values_id" rows="6" required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('values_id') border-rose-400 @enderror">{{ old('values_id', $values_id_text) }}</textarea>
                        @error('values_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="border-b border-slate-100/90 pb-2 font-display-admin text-lg font-bold text-primary">English</h2>
                    <div>
                        <label for="vision_en" class="mb-1 block text-xs font-semibold text-slate-700">Vision <span class="font-normal text-slate-500">(opsional — kosongkan untuk pakai teks Indonesia di halaman EN)</span></label>
                        <textarea id="vision_en" name="vision_en" rows="4"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('vision_en') border-rose-400 @enderror">{{ old('vision_en', $content->vision_en) }}</textarea>
                        @error('vision_en')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mission_en" class="mb-1 block text-xs font-semibold text-slate-700">Mission <span class="font-normal text-slate-500">(one line per bullet)</span></label>
                        <textarea id="mission_en" name="mission_en" rows="8"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('mission_en') border-rose-400 @enderror">{{ old('mission_en', $mission_en_text) }}</textarea>
                        @error('mission_en')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="values_en" class="mb-1 block text-xs font-semibold text-slate-700">Values <span class="font-normal text-slate-500">(one item per line)</span></label>
                        <textarea id="values_en" name="values_en" rows="6"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('values_en') border-rose-400 @enderror">{{ old('values_en', $values_en_text) }}</textarea>
                        @error('values_en')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition hover:brightness-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

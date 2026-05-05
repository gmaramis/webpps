@extends('admin.layouts.app')

@section('title', 'Tautan portal akademik')
@section('heading', 'Tautan portal akademik')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Tautan portal akademik</span>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        <div>
            <p class="text-sm text-slate-600">URL untuk menu <strong class="text-slate-800">Akademik</strong> (Portal Akademik, LMS, SPADA Indonesia) di navigasi situs, serta tautan LMS di footer.</p>
            <p class="mt-1 text-xs text-slate-500">Setelah menyimpan, muat ulang halaman publik untuk melihat perubahan.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-slate-200/90 bg-slate-50/90 px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:bg-white hover:shadow-md">
            Situs publik ↗
        </a>
    </div>

    @if(! ($settingsReady ?? false))
        <div class="rounded-3xl border border-amber-200/90 bg-gradient-to-br from-amber-50 to-white px-6 py-7 text-sm leading-relaxed text-amber-950 shadow-lg shadow-amber-900/[0.04]">
            <p class="font-display-admin font-bold text-amber-900">Tabel basis data belum ada</p>
            <p class="mt-2 text-amber-900/90">Jalankan migrasi:</p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-amber-950/10 px-3 py-2 font-mono text-xs text-amber-950">php artisan migrate</pre>
        </div>
    @else
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
            <form method="post" action="{{ route('admin.tautan-portal-akademik.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="portal_url" class="mb-1 block text-xs font-semibold text-slate-700">Portal Akademik</label>
                    <input id="portal_url" type="url" name="portal_url" required value="{{ old('portal_url', $content->portal_url) }}" maxlength="2048"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('portal_url') border-rose-400 @enderror"
                        placeholder="https://…">
                    @error('portal_url')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="lms_url" class="mb-1 block text-xs font-semibold text-slate-700">LMS</label>
                    <input id="lms_url" type="url" name="lms_url" required value="{{ old('lms_url', $content->lms_url) }}" maxlength="2048"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('lms_url') border-rose-400 @enderror"
                        placeholder="https://…">
                    @error('lms_url')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="spada_url" class="mb-1 block text-xs font-semibold text-slate-700">SPADA Indonesia</label>
                    <input id="spada_url" type="url" name="spada_url" required value="{{ old('spada_url', $content->spada_url) }}" maxlength="2048"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('spada_url') border-rose-400 @enderror"
                        placeholder="https://…">
                    @error('spada_url')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">Simpan</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

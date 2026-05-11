@extends('admin.layouts.app')

@section('title', 'Gambar hero program beranda')
@section('heading', 'Gambar hero program beranda')

@section('content')
@php
    use App\Models\HomepageProgramDisplay;
    $magPreview = HomepageProgramDisplay::publicHeroUrl($display->magister_hero_path, 'programs/magister-photo.png');
    $dokPreview = HomepageProgramDisplay::publicHeroUrl($display->doktor_hero_path, 'programs/doktor-photo.png');
@endphp
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Gambar hero program</span>
    </nav>

    @if (session('status'))
        <p class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('status') }}</p>
    @endif

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <p class="mb-6 text-sm leading-relaxed text-slate-600">Gambar ini dipakai di blok Magister dan Doktor pada beranda serta di halaman <span class="font-mono text-xs">/s2</span> dan <span class="font-mono text-xs">/s3</span>. Kosongkan unggahan untuk mempertahankan gambar saat ini. Tanpa unggahan khusus, dipakai aset bawaan dari berkas statis.</p>

        <form method="post" action="{{ route('admin.program-heroes.update') }}" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PATCH')

            <div class="grid gap-8 md:grid-cols-2">
                <div class="space-y-3">
                    <h2 class="text-base font-bold text-primary">Magister (S2)</h2>
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img src="{{ $magPreview }}" alt="" width="640" height="400" class="h-auto w-full object-cover" loading="lazy" decoding="async">
                    </div>
                    <label for="magister_hero" class="mb-1 block text-xs font-semibold text-slate-700">Unggah gambar baru (JPEG, PNG, atau WebP, maks. 3 MB)</label>
                    <input id="magister_hero" name="magister_hero" type="file" accept="image/jpeg,image/png,image/webp"
                        class="block w-full max-w-md text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/15">
                    @error('magister_hero')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <h2 class="text-base font-bold text-primary">Doktor (S3)</h2>
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img src="{{ $dokPreview }}" alt="" width="640" height="400" class="h-auto w-full object-cover" loading="lazy" decoding="async">
                    </div>
                    <label for="doktor_hero" class="mb-1 block text-xs font-semibold text-slate-700">Unggah gambar baru (JPEG, PNG, atau WebP, maks. 3 MB)</label>
                    <input id="doktor_hero" name="doktor_hero" type="file" accept="image/jpeg,image/png,image/webp"
                        class="block w-full max-w-md text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/15">
                    @error('doktor_hero')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition hover:brightness-110">Simpan perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

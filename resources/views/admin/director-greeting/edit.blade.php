@extends('admin.layouts.app')

@section('title', 'Sambutan direktur beranda')
@section('heading', 'Sambutan direktur beranda')

@php
    $dg = is_array($ppsData['DIRECTOR_GREETING'] ?? null) ? $ppsData['DIRECTOR_GREETING'] : [];
    $sid = is_array($ppsData['STRINGS']['id'] ?? null) ? $ppsData['STRINGS']['id'] : [];
    $sen = is_array($ppsData['STRINGS']['en'] ?? null) ? $ppsData['STRINGS']['en'] : [];
    $nameBlock = is_array($dg['name'] ?? null) ? $dg['name'] : [];
    $roleBlock = is_array($dg['role'] ?? null) ? $dg['role'] : [];
    $paragraphRows = old('paragraphs', $dg['paragraphs'] ?? []);
    if (! is_array($paragraphRows)) {
        $paragraphRows = [];
    }
    while (count($paragraphRows) < 3) {
        $paragraphRows[] = ['id' => '', 'en' => ''];
    }
    $paragraphRows = array_slice($paragraphRows, 0, 12);
    $photoPath = isset($dg['photo']) && is_string($dg['photo']) ? trim($dg['photo']) : '';
    $photoPreview = \App\Support\PpsContent::directorGreetingPublicPhotoUrl($photoPath);
@endphp

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Sambutan direktur</span>
    </nav>

    @if (session('status'))
        <p class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
            <p class="font-semibold">Tidak dapat menyimpan</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <form method="post" action="{{ route('admin.director-greeting.update') }}" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PATCH')

            <fieldset class="space-y-4">
                <legend class="text-base font-bold text-primary">Teks bagian (judul di atas kartu)</legend>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Bahasa Indonesia</p>
                        <div>
                            <label for="section_eyebrow_id" class="mb-1 block text-xs font-semibold text-slate-700">Label kecil (eyebrow)</label>
                            <input id="section_eyebrow_id" name="section_eyebrow_id" type="text" value="{{ old('section_eyebrow_id', $sid['directorGreetingEyebrow'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="section_title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul bagian</label>
                            <input id="section_title_id" name="section_title_id" type="text" value="{{ old('section_title_id', $sid['directorGreetingTitle'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="section_quote_label_id" class="mb-1 block text-xs font-semibold text-slate-700">Subjudul / keterangan</label>
                            <input id="section_quote_label_id" name="section_quote_label_id" type="text" value="{{ old('section_quote_label_id', $sid['directorGreetingQuoteLabel'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">English</p>
                        <div>
                            <label for="section_eyebrow_en" class="mb-1 block text-xs font-semibold text-slate-700">Eyebrow</label>
                            <input id="section_eyebrow_en" name="section_eyebrow_en" type="text" value="{{ old('section_eyebrow_en', $sen['directorGreetingEyebrow'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="section_title_en" class="mb-1 block text-xs font-semibold text-slate-700">Section title</label>
                            <input id="section_title_en" name="section_title_en" type="text" value="{{ old('section_title_en', $sen['directorGreetingTitle'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="section_quote_label_en" class="mb-1 block text-xs font-semibold text-slate-700">Subtitle</label>
                            <input id="section_quote_label_en" name="section_quote_label_en" type="text" value="{{ old('section_quote_label_en', $sen['directorGreetingQuoteLabel'] ?? '') }}" required maxlength="200" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-4">
                <legend class="text-base font-bold text-primary">Foto direktur</legend>
                <div class="flex flex-wrap items-start gap-6">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img src="{{ $photoPreview }}" alt="" width="240" height="320" class="h-48 w-36 object-cover object-top md:h-52 md:w-40" loading="lazy" decoding="async">
                    </div>
                    <div class="min-w-0 flex-1 space-y-2">
                        <label for="photo" class="mb-1 block text-xs font-semibold text-slate-700">Unggah foto baru (JPEG, PNG, atau WebP, maks. 3 MB)</label>
                        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp"
                            class="block w-full max-w-md text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/15">
                        @error('photo')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                        @if ($photoPath !== '')
                            <p class="text-xs text-slate-500">Path saat ini: <code class="rounded bg-slate-100 px-1">{{ $photoPath }}</code></p>
                        @endif
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-4">
                <legend class="text-base font-bold text-primary">Nama dan jabatan (di bawah foto)</legend>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Bahasa Indonesia</p>
                        <div>
                            <label for="name_id" class="mb-1 block text-xs font-semibold text-slate-700">Nama</label>
                            <input id="name_id" name="name_id" type="text" value="{{ old('name_id', $nameBlock['id'] ?? '') }}" maxlength="255" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="role_id" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan</label>
                            <input id="role_id" name="role_id" type="text" value="{{ old('role_id', $roleBlock['id'] ?? '') }}" maxlength="255" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">English</p>
                        <div>
                            <label for="name_en" class="mb-1 block text-xs font-semibold text-slate-700">Name</label>
                            <input id="name_en" name="name_en" type="text" value="{{ old('name_en', $nameBlock['en'] ?? '') }}" maxlength="255" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="role_en" class="mb-1 block text-xs font-semibold text-slate-700">Role</label>
                            <input id="role_en" name="role_en" type="text" value="{{ old('role_en', $roleBlock['en'] ?? '') }}" maxlength="255" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-4">
                <legend class="text-base font-bold text-primary">Isi sambutan (paragraf)</legend>
                <p class="text-xs text-slate-500">Maksimal 12 paragraf. Baris yang ID dan EN-nya kosong akan diabaikan.</p>
                <div class="space-y-6">
                    @foreach ($paragraphRows as $i => $para)
                        @php
                            $p = is_array($para) ? $para : [];
                            $pid = old("paragraphs.$i.id", $p['id'] ?? '');
                            $pen = old("paragraphs.$i.en", $p['en'] ?? '');
                        @endphp
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <p class="mb-2 text-xs font-semibold text-slate-600">Paragraf {{ $i + 1 }}</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="paragraph_id_{{ $i }}" class="mb-1 block text-xs font-medium text-slate-600">Indonesia</label>
                                    <textarea id="paragraph_id_{{ $i }}" name="paragraphs[{{ $i }}][id]" rows="4" maxlength="20000" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ $pid }}</textarea>
                                </div>
                                <div>
                                    <label for="paragraph_en_{{ $i }}" class="mb-1 block text-xs font-medium text-slate-600">English</label>
                                    <textarea id="paragraph_en_{{ $i }}" name="paragraphs[{{ $i }}][en]" rows="4" maxlength="20000" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ $pen }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition hover:brightness-110">Simpan perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

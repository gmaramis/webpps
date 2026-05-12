@extends('admin.layouts.app')

@section('title', 'Sejarah beranda')
@section('heading', 'Sejarah Pascasarjana (beranda)')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Sejarah beranda</span>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        <div>
            <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">URL admin:</span> <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs font-mono text-slate-800">/admin/beranda-sejarah</code></p>
            <p class="mt-1 text-sm text-slate-600">Teks sejarah beranda (ID &amp; EN); opsional <span class="font-semibold text-slate-800">gambar di kanan</span> pada layar lebar.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-slate-200/90 bg-slate-50/90 px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:bg-white hover:shadow-md">
            Lihat beranda ↗
        </a>
    </div>

    @if(! ($ready ?? false))
        <div class="rounded-3xl border border-amber-200/90 bg-gradient-to-br from-amber-50 to-white px-6 py-7 text-sm leading-relaxed text-amber-950 shadow-lg shadow-amber-900/[0.04]">
            <p class="font-display-admin font-bold text-amber-900">Skema basis data untuk konten sejarah belum siap</p>
            <p class="mt-2 text-amber-900/90">Jalankan migrasi (termasuk penyederhanaan kolom):</p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-amber-950/10 px-3 py-2 font-mono text-xs text-amber-950">php artisan migrate</pre>
            <p class="mt-3 text-xs text-amber-800/90">Setelah selesai, muat ulang halaman ini.</p>
        </div>
    @else
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
            <form method="post" action="{{ route('admin.beranda-sejarah.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PATCH')

                <section class="space-y-4">
                    <h2 class="border-b border-slate-100/90 pb-2 font-display-admin text-lg font-bold text-primary">Bahasa Indonesia</h2>
                    <div>
                        <label for="eyebrow_id" class="mb-1 block text-xs font-semibold text-slate-700">Label atas (eyebrow)</label>
                        <input id="eyebrow_id" name="eyebrow_id" type="text" required maxlength="255" value="{{ old('eyebrow_id', $content->eyebrow_id) }}"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('eyebrow_id') border-rose-400 @enderror">
                        @error('eyebrow_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul</label>
                        <input id="title_id" name="title_id" type="text" required maxlength="500" value="{{ old('title_id', $content->title_id) }}"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_id') border-rose-400 @enderror">
                        @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="paragraph_id" class="mb-1 block text-xs font-semibold text-slate-700">Paragraf</label>
                        <textarea id="paragraph_id" name="paragraph_id" rows="8" required maxlength="20000"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm leading-relaxed text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('paragraph_id') border-rose-400 @enderror">{{ old('paragraph_id', $content->paragraph_id) }}</textarea>
                        <p class="mt-1 text-[11px] text-slate-500">Tampil sebagai satu blok teks di bawah judul pada beranda.</p>
                        @error('paragraph_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="border-b border-slate-100/90 pb-2 font-display-admin text-lg font-bold text-primary">English</h2>
                    <p class="text-xs text-slate-500">Kosongkan paragraf Inggris untuk memakai teks Indonesia saat locale EN.</p>
                    <div>
                        <label for="eyebrow_en" class="mb-1 block text-xs font-semibold text-slate-700">Eyebrow</label>
                        <input id="eyebrow_en" name="eyebrow_en" type="text" maxlength="255" value="{{ old('eyebrow_en', $content->eyebrow_en) }}"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('eyebrow_en') border-rose-400 @enderror">
                        @error('eyebrow_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Title</label>
                        <input id="title_en" name="title_en" type="text" maxlength="500" value="{{ old('title_en', $content->title_en) }}"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_en') border-rose-400 @enderror">
                        @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="paragraph_en" class="mb-1 block text-xs font-semibold text-slate-700">Paragraph</label>
                        <textarea id="paragraph_en" name="paragraph_en" rows="8" maxlength="20000"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm leading-relaxed text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('paragraph_en') border-rose-400 @enderror">{{ old('paragraph_en', $content->paragraph_en) }}</textarea>
                        @error('paragraph_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </section>

                @if($imageColumnReady ?? false)
                    <section class="space-y-4">
                        <h2 class="border-b border-slate-100/90 pb-2 font-display-admin text-lg font-bold text-primary">Gambar beranda</h2>
                        <p class="text-xs text-slate-500">Diletakkan di kanan teks dari layar <span class="font-medium text-slate-700">sm</span> ke atas (kolom tetap ±216–264&nbsp;px). Format gambar (JPEG/PNG/WebP), maks. <span class="font-medium text-slate-700">1,5&nbsp;MB</span>, maks. <span class="font-medium text-slate-700">1200×1600</span> piksel.</p>
                        @php
                            $curImg = trim((string) ($content->image_path ?? ''));
                            $curImgUrl = $curImg !== '' ? \App\Models\GraduateSchoolHistoryContent::publicImageUrl($curImg) : '';
                        @endphp
                        @if($curImgUrl !== '')
                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $curImgUrl }}" alt="" class="max-h-56 w-full object-contain object-center" width="800" height="600" decoding="async">
                            </div>
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-primary focus:ring-primary/40">
                                <span>Hapus gambar saat simpan</span>
                            </label>
                        @endif
                        <div>
                            <label for="history_image" class="mb-1 block text-xs font-semibold text-slate-700">{{ $curImgUrl !== '' ? 'Ganti gambar' : 'Unggah gambar' }}</label>
                            <input id="history_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp"
                                class="block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark @error('image') border border-rose-400 rounded-lg @enderror">
                            @error('image')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </section>
                @endif

                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-primary/20 transition hover:bg-primary-dark">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

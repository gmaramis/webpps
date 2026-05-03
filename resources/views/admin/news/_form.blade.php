@php
    /** @var \App\Models\NewsItem $item */
    $isEdit = $item->exists;
    $inp = 'w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15';
    $sectionKicker = 'mb-0.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500';
    $sectionTitle = 'text-sm font-semibold text-slate-900';

    $enTitle = '';
    $enExcerpt = '';
    $enBody = '';
    $hasEn = false;
    $tstat = 'idle';
    $enNeedsAttention = false;
    if ($isEdit) {
        $enTitle = (string) $item->getTranslationWithoutFallback('title', 'en');
        $enExcerpt = (string) $item->getTranslationWithoutFallback('excerpt', 'en');
        $enBody = (string) $item->getTranslationWithoutFallback('body', 'en');
        $hasEn = trim($enTitle) !== '' || trim($enExcerpt) !== '' || trim($enBody) !== '';
        $tstat = $item->translation_status ?? 'idle';
        $enNeedsAttention = in_array($tstat, ['ready_for_review', 'failed'], true);
    }
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-900">
        <p class="font-semibold">Perlu diperbaiki:</p>
        <ul class="mt-1 list-disc space-y-0.5 pl-4 text-xs sm:text-sm">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($isEdit)
    <div class="mb-4 rounded-xl bg-slate-100/90 p-1 shadow-inner" role="tablist" aria-label="Langkah mengedit berita">
        <div class="grid grid-cols-2 gap-0.5 sm:flex">
            <button type="button" role="tab" aria-selected="true" data-news-tab="id" id="tab-news-id"
                class="news-tab-btn flex flex-1 items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-medium transition sm:px-3 sm:text-sm">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white text-[10px] font-bold text-primary shadow-sm ring-1 ring-slate-200/80">1</span>
                <span class="min-w-0 text-left leading-tight">
                    <span class="block font-bold text-slate-900">Konten ID</span>
                    <span class="hidden text-[10px] font-normal text-slate-600 sm:block">Judul, ringkasan, isi</span>
                </span>
            </button>
            <button type="button" role="tab" aria-selected="false" data-news-tab="en" id="tab-news-en"
                class="news-tab-btn flex flex-1 items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-medium transition sm:px-3 sm:text-sm">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white text-[10px] font-bold text-slate-600 shadow-sm ring-1 ring-slate-200/80">2</span>
                <span class="flex min-w-0 flex-1 items-center gap-1.5 text-left leading-tight">
                    <span class="min-w-0 font-bold text-slate-900">Tinjau EN</span>
                    @if ($enNeedsAttention)
                        <span class="shrink-0 rounded bg-amber-500 px-1 py-px text-[9px] font-bold uppercase text-white">Cek</span>
                    @endif
                </span>
            </button>
        </div>
    </div>
@else
    <div class="mb-4 flex gap-2 rounded-xl border border-emerald-100 bg-emerald-50/80 px-3 py-2.5 text-xs text-emerald-950 sm:text-sm">
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-[11px] font-bold text-white">1</span>
        <p><strong>Tulis Indonesia</strong> → simpan <strong>draf</strong> → terjemahan otomatis → buka lagi tab EN sebelum terbit.</p>
    </div>
@endif

<div class="flex flex-col gap-5 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-6 xl:gap-x-8">
    <div class="min-w-0 space-y-5 lg:col-span-8">
        <div data-news-panel="id" id="panel-news-id">
            @if ($isEdit)
                <p class="rounded-lg border border-slate-100 bg-slate-50 px-2.5 py-1.5 text-xs text-slate-700">
                    <strong class="text-slate-900">Langkah 1:</strong> sunting di bawah · versi Inggris di tab <strong>2</strong>.
                </p>
            @endif

            <div class="@if($isEdit) mt-3 @endif grid gap-5 md:grid-cols-2 md:items-start">
                <div>
                    <p class="{{ $sectionKicker }}">Judul</p>
                    <h2 class="{{ $sectionTitle }}">Judul berita</h2>
                    <label for="title_id" class="mt-1.5 mb-0.5 block text-[11px] font-medium text-slate-600">Indonesia</label>
                    <input id="title_id" type="text" name="title[id]" required maxlength="255" value="{{ old('title.id', $item->getTranslationWithoutFallback('title', 'id')) }}" class="{{ $inp }}" placeholder="Judul">
                </div>
                <div>
                    <p class="{{ $sectionKicker }}">Ringkasan</p>
                    <h2 class="{{ $sectionTitle }}">Cuplikan</h2>
                    <label for="excerpt_id" class="mt-1.5 mb-0.5 block text-[11px] font-medium text-slate-600">Indonesia</label>
                    <textarea id="excerpt_id" name="excerpt[id]" rows="4" required class="{{ $inp }} resize-y" placeholder="Singkat untuk kartu beranda">{{ old('excerpt.id', $item->getTranslationWithoutFallback('excerpt', 'id')) }}</textarea>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <p class="{{ $sectionKicker }}">Isi</p>
                <h2 class="{{ $sectionTitle }}">Isi lengkap</h2>
                <label for="body_id" class="mt-1.5 mb-0.5 block text-[11px] font-medium text-slate-600">Indonesia · boleh HTML ringan</label>
                <textarea id="body_id" name="body[id]" rows="8" required class="{{ $inp }} max-h-[38vh] min-h-[10rem] resize-y leading-relaxed" placeholder="Paragraf artikel…">{{ old('body.id', $item->getTranslationWithoutFallback('body', 'id')) }}</textarea>
                <p class="mt-1 text-[10px] text-slate-500">Gulir di dalam kotak jika isi panjang — halaman tetap ringkas.</p>
            </div>
        </div>

        @if ($isEdit)
            <div data-news-panel="en" id="panel-news-en" hidden>
                @include('admin.news._form_review_en', [
                    'item' => $item,
                    'enTitle' => $enTitle,
                    'enExcerpt' => $enExcerpt,
                    'enBody' => $enBody,
                    'hasEn' => $hasEn,
                    'tstat' => $tstat,
                ])
            </div>
        @endif
    </div>

    <aside class="min-w-0 space-y-4 border-t border-slate-200 pt-5 lg:col-span-4 lg:border-l lg:border-t-0 lg:border-slate-200 lg:pl-5 lg:pt-0 xl:pl-6">
        <div class="lg:sticky lg:top-4 space-y-4">
            @if ($isEdit && ! $item->is_published && $hasEn && trim((string) $item->getTranslationWithoutFallback('body', 'id')) !== '')
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-950">
                    <strong>Siap tayang?</strong> Cek tab <strong>Tinjau EN</strong>, lalu status <strong>Dipublikasikan</strong> di bawah.
                </div>
            @endif

            <div>
                <p class="{{ $sectionKicker }}">Gambar</p>
                <h2 id="image-field-heading" class="{{ $sectionTitle }}">Foto</h2>
                <p class="mt-0.5 text-[11px] text-slate-600">Opsional · max 300&nbsp;KB → WebP 1200px</p>

                @if ($isEdit && $item->image_path)
                    <div class="mt-2">
                        <p class="mb-0.5 text-[10px] font-medium text-slate-500">Saat ini</p>
                        <div class="inline-block max-w-[10rem] rounded-md border border-slate-200 bg-slate-100 p-1 shadow-inner">
                            <img src="{{ $item->newsImageUrl() }}" alt="" class="block h-auto max-h-24 w-auto max-w-full rounded object-contain" decoding="async">
                        </div>
                    </div>
                    <label class="mt-2 flex cursor-pointer items-center gap-2 text-xs text-slate-700">
                        <input type="hidden" name="remove_image" value="0">
                        <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-primary focus:ring-primary/30" @checked(old('remove_image'))>
                        <span>Hapus gambar</span>
                    </label>
                @endif

                <div class="mt-2">
                    <input
                        id="image"
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        class="sr-only"
                        tabindex="-1"
                        aria-describedby="image-hint">

                    <label
                        id="news-upload-zone"
                        for="image"
                        tabindex="0"
                        role="button"
                        aria-labelledby="image-field-heading"
                        class="group flex cursor-pointer items-center gap-2 rounded-xl border border-dashed border-slate-300 bg-slate-50/80 px-3 py-2.5 text-left transition hover:border-primary/40 hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/25">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-slate-900">Pilih berkas</span>
                            <span class="block text-[10px] text-slate-600">JPG/PNG/GIF/WebP</span>
                        </span>
                    </label>

                    <div id="image-hint" class="sr-only">Unggah gambar hero</div>
                    <div id="news-image-filename" class="mt-1.5 hidden rounded border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] text-emerald-900" aria-live="polite" role="status">
                        <span class="font-medium">Terpilih:</span> <span id="news-image-filename-name" class="break-all"></span>
                    </div>
                </div>

                <script>
                    (function () {
                        var input = document.getElementById('image');
                        var box = document.getElementById('news-image-filename');
                        var nameEl = document.getElementById('news-image-filename-name');
                        var zone = document.getElementById('news-upload-zone');
                        if (!input || !box || !nameEl || !zone) return;
                        input.addEventListener('change', function () {
                            if (input.files && input.files.length > 0) {
                                nameEl.textContent = input.files[0].name;
                                box.classList.remove('hidden');
                            }
                        });
                        zone.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                input.click();
                            }
                        });
                    })();
                </script>
            </div>

            @php
                $pubDefault = $item->exists ? ($item->is_published ? '1' : '0') : '0';
                $pubOld = old('is_published', $pubDefault);
                $pubVal = is_bool($pubOld) ? ($pubOld ? '1' : '0') : (string) $pubOld;
                if ($pubVal !== '0' && $pubVal !== '1') {
                    $pubVal = $pubDefault;
                }
            @endphp

            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                <p class="{{ $sectionKicker }}">Penulis</p>
                <h2 class="{{ $sectionTitle }}">Nama pengarang</h2>
                <label for="author" class="mt-2 mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-slate-500">Tampil di halaman berita</label>
                <input id="author" type="text" name="author" maxlength="191" value="{{ old('author', $item->author) }}" class="{{ $inp }}" placeholder="Mis. Humas Pascasarjana">
                <p class="mt-1.5 text-[10px] leading-snug text-slate-600">Opsional · kosongkan jika tidak ingin ditampilkan.</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                <p class="{{ $sectionKicker }}">Publikasi</p>
                <h2 class="{{ $sectionTitle }}">Status</h2>
                <label for="is_published" class="mt-2 mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-slate-500">Tayang?</label>
                <select id="is_published" name="is_published" required class="{{ $inp }} w-full text-sm font-semibold">
                    <option value="0" @selected($pubVal === '0')>Draf</option>
                    <option value="1" @selected($pubVal === '1')>Dipublikasikan</option>
                </select>
                <p class="mt-2 text-[10px] leading-snug text-slate-600">Terbit butuh EN valid — cek tab <strong>2</strong>. Tanggal beranda = saat pertama kali terbit.</p>
            </div>

            <details class="rounded-xl border border-slate-200 bg-slate-50/50 open:bg-white open:shadow-sm">
                <summary class="cursor-pointer list-none px-3 py-2 text-sm font-semibold text-slate-800 marker:hidden [&::-webkit-details-marker]:hidden">
                    Meta SEO <span class="text-xs font-normal text-slate-500">(klik jika perlu)</span>
                </summary>
                <div class="border-t border-slate-100 px-3 pb-3 pt-2">
                    <label for="meta_title_id" class="mb-0.5 block text-[11px] font-medium text-slate-600">Meta judul (ID)</label>
                    <input id="meta_title_id" type="text" name="meta_title[id]" maxlength="255" value="{{ old('meta_title.id', $item->getTranslationWithoutFallback('meta_title', 'id')) }}" class="{{ $inp }}">
                    <label for="meta_description_id" class="mb-0.5 mt-2 block text-[11px] font-medium text-slate-600">Meta deskripsi (ID)</label>
                    <textarea id="meta_description_id" name="meta_description[id]" rows="2" maxlength="500" class="{{ $inp }} resize-y">{{ old('meta_description.id', $item->getTranslationWithoutFallback('meta_description', 'id')) }}</textarea>
                </div>
            </details>
        </div>
    </aside>
</div>

<div class="mt-5 flex flex-wrap items-center gap-2 border-t border-slate-200 pt-4">
    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-primary-dark">{{ $isEdit ? 'Simpan' : 'Simpan berita' }}</button>
    <a href="{{ route('admin.news.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">← Daftar</a>
</div>

@if ($isEdit)
    <style>
        .news-tab-btn[aria-selected="true"] {
            background: white;
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.06);
            color: var(--color-primary, #0c4a6e);
        }
        .news-tab-btn[aria-selected="true"] span.rounded-full:first-of-type {
            color: var(--color-primary, #0c4a6e);
            font-weight: 800;
        }
    </style>
    <script>
        (function () {
            var tabButtons = document.querySelectorAll('[data-news-tab]');
            if (!tabButtons.length) return;
            var panels = document.querySelectorAll('[data-news-panel]');

            function setHashForTab(which) {
                var path = window.location.pathname + window.location.search;
                try {
                    if (which === 'en') history.replaceState(null, '', path + '#tinjau-en');
                    else history.replaceState(null, '', path);
                } catch (e) {}
            }

            function show(which) {
                tabButtons.forEach(function (btn) {
                    var on = btn.getAttribute('data-news-tab') === which;
                    btn.setAttribute('aria-selected', on ? 'true' : 'false');
                });
                panels.forEach(function (p) {
                    p.hidden = p.getAttribute('data-news-panel') !== which;
                });
                setHashForTab(which);
            }

            tabButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    show(btn.getAttribute('data-news-tab'));
                });
            });
            document.querySelectorAll('[data-news-tab-trigger]').forEach(function (el) {
                el.addEventListener('click', function () {
                    show(el.getAttribute('data-news-tab-trigger'));
                });
            });

            if (window.location.hash === '#tinjau-en') show('en');
            else show('id');
        })();
    </script>
@endif

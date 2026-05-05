@php
    /** @var \App\Models\AlumniActivity $activity */
    $isEdit = $activity->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $activity->sort_order ?? 0) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror"
                aria-describedby="aa-sort-help">
            <p id="aa-sort-help" class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">
                Angka <strong>lebih kecil</strong> dipajang lebih awal pada grid kegiatan alumni.
            </p>
            @error('sort_order')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="is_published" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30" @checked(old('is_published', $activity->is_published ?? false))>
            <span>
                <span class="block text-xs font-semibold text-slate-800">Tampilkan di situs publik</span>
                <span class="mt-0.5 block text-[11px] leading-relaxed text-slate-600">Nonaktifkan untuk menyimpan sebagai draf; entri draf tidak muncul di halaman <code class="rounded bg-white px-1 font-mono text-[10px]">/kegiatan-alumni</code>.</span>
            </span>
        </label>
        @error('is_published')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul (Bahasa Indonesia)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $activity->title_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_id') border-rose-400 @enderror">
            @error('title_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $activity->title_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_en') border-rose-400 @enderror">
            @error('title_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="excerpt_id" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan (Bahasa Indonesia)</label>
            <textarea id="excerpt_id" name="excerpt_id" rows="5" required maxlength="5000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('excerpt_id') border-rose-400 @enderror">{{ old('excerpt_id', $activity->excerpt_id) }}</textarea>
            @error('excerpt_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="excerpt_en" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <textarea id="excerpt_en" name="excerpt_en" rows="5" maxlength="5000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('excerpt_en') border-rose-400 @enderror">{{ old('excerpt_en', $activity->excerpt_en) }}</textarea>
            @error('excerpt_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="image_alt_id" class="mb-1 block text-xs font-semibold text-slate-700">Teks alternatif gambar — ID</label>
            <input id="image_alt_id" type="text" name="image_alt_id" required value="{{ old('image_alt_id', $activity->image_alt_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('image_alt_id') border-rose-400 @enderror">
            @error('image_alt_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="image_alt_en" class="mb-1 block text-xs font-semibold text-slate-700">Teks alternatif gambar — EN <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="image_alt_en" type="text" name="image_alt_en" value="{{ old('image_alt_en', $activity->image_alt_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('image_alt_en') border-rose-400 @enderror">
            @error('image_alt_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="photo" class="mb-1 block text-xs font-semibold text-slate-700">Gambar sampul</label>
        @if($isEdit)
            <p class="mb-2 text-[11px] text-slate-500">JPEG, PNG, WebP, GIF, atau SVG — maks. 4&nbsp;MB. Lewati untuk mempertahankan gambar yang sudah tersimpan.</p>
        @else
            <p class="mb-2 text-[11px] text-slate-500">JPEG, PNG, WebP, GIF, atau SVG — maks. 4&nbsp;MB. Wajib untuk entri baru.</p>
        @endif
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('photo') border-rose-400 @enderror">
        @error('photo')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && ($activity->image ?? '') !== '')
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <img src="{{ $activity->resolvedImageUrl() }}" alt="" class="h-20 w-32 rounded-xl border border-slate-200 object-cover shadow-sm" width="128" height="80">
                <p class="max-w-md text-[11px] leading-relaxed text-slate-500">Berkas: <code class="rounded bg-slate-100 px-1">{{ $activity->image }}</code></p>
            </div>
        @endif
    </div>
</div>

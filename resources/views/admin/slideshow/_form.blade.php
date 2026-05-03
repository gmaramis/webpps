@php
    /** @var \App\Models\HeroSlide $slide */
    $isEdit = $slide->exists;
@endphp
<div class="space-y-6">
    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $slide->sort_order ?? 0) }}"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror">
        <p class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">Angka lebih kecil akan tampil sebagai slide awal.</p>
        @error('sort_order')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="mb-1 block text-xs font-semibold text-slate-700">Gambar slide {{ $isEdit ? '(opsional jika tidak diganti)' : '' }}</label>
        <p class="mb-2 text-[11px] text-slate-500">
            Wajib <strong>JPEG (.jpg/.jpeg)</strong>, maksimal <strong>500KB</strong>, ukuran tepat <strong>1600 x 700 px</strong>
            (sekitar <strong>42.3 x 18.5 cm</strong> pada 96 DPI).
        </p>
        <input id="image" type="file" name="image" accept="image/jpeg,.jpg,.jpeg"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('image') border-rose-400 @enderror">
        @error('image')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror

        @if($isEdit)
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <img src="{{ $slide->resolvedImageUrl() }}" alt="" class="h-24 w-44 rounded-xl border border-slate-200 object-cover shadow-sm" width="176" height="96">
                <p class="max-w-md text-[11px] leading-relaxed text-slate-500">Path tersimpan: <code class="rounded bg-slate-100 px-1 break-all">{{ $slide->image }}</code></p>
            </div>
        @endif
    </div>
</div>

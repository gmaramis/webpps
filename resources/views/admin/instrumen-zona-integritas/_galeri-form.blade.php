@php
    /** @var \App\Models\ZiGalleryItem $item */
    $isEdit = $item->exists;
@endphp
<div class="space-y-6">
    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="w-full max-w-xs rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('sort_order') border-rose-400 @enderror">
        @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="is_published" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary" @checked(old('is_published', $item->is_published ?? false))>
            <span class="text-xs font-semibold text-slate-800">Tampilkan di galeri publik</span>
        </label>
        @error('is_published')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="image_alt_id" class="mb-1 block text-xs font-semibold text-slate-700">Alt gambar (ID)</label>
            <input id="image_alt_id" type="text" name="image_alt_id" required value="{{ old('image_alt_id', $item->image_alt_id) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('image_alt_id') border-rose-400 @enderror">
            @error('image_alt_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="image_alt_en" class="mb-1 block text-xs font-semibold text-slate-700">Alt gambar (EN)</label>
            <input id="image_alt_en" type="text" name="image_alt_en" value="{{ old('image_alt_en', $item->image_alt_en) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('image_alt_en') border-rose-400 @enderror">
            @error('image_alt_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="caption_id" class="mb-1 block text-xs font-semibold text-slate-700">Keterangan (ID)</label>
            <input id="caption_id" type="text" name="caption_id" required value="{{ old('caption_id', $item->caption_id) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('caption_id') border-rose-400 @enderror">
            @error('caption_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="caption_en" class="mb-1 block text-xs font-semibold text-slate-700">Keterangan (EN)</label>
            <input id="caption_en" type="text" name="caption_en" value="{{ old('caption_en', $item->caption_en) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('caption_en') border-rose-400 @enderror">
            @error('caption_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label for="photo" class="mb-1 block text-xs font-semibold text-slate-700">Gambar</label>
        @if($isEdit)<p class="mb-2 text-[11px] text-slate-500">Lewati untuk mempertahankan gambar saat ini.</p>@else<p class="mb-2 text-[11px] text-slate-500">Wajib untuk entri baru.</p>@endif
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" class="block w-full rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white @error('photo') border-rose-400 @enderror">
        @error('photo')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        @if($isEdit && ($item->image ?? '') !== '')
            <div class="mt-3 flex items-center gap-4">
                <img src="{{ $item->resolvedImageUrl() }}" alt="" class="h-20 w-28 rounded-lg border object-cover">
                <code class="text-[11px] text-slate-500">{{ $item->image }}</code>
            </div>
        @endif
    </div>
</div>

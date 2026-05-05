@php
    /** @var \App\Models\ZiPillar $pillar */
    $isEdit = $pillar->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $pillar->sort_order ?? 0) }}" class="w-full max-w-xs rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('sort_order') border-rose-400 @enderror">
            @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="is_published" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30" @checked(old('is_published', $pillar->is_published ?? false))>
            <span class="text-xs font-semibold text-slate-800">Tampilkan di situs publik</span>
        </label>
        @error('is_published')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul (ID)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $pillar->title_id) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_id') border-rose-400 @enderror">
            @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (EN)</label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $pillar->title_en) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_en') border-rose-400 @enderror">
            @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="desc_id" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi (ID)</label>
            <textarea id="desc_id" name="desc_id" rows="5" required maxlength="5000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('desc_id') border-rose-400 @enderror">{{ old('desc_id', $pillar->desc_id) }}</textarea>
            @error('desc_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="desc_en" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi (EN)</label>
            <textarea id="desc_en" name="desc_en" rows="5" maxlength="5000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('desc_en') border-rose-400 @enderror">{{ old('desc_en', $pillar->desc_en) }}</textarea>
            @error('desc_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

@php
    /** @var \App\Models\ZiUpdateItem $item */
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
            <span class="text-xs font-semibold text-slate-800">Tampilkan di daftar pembaruan publik</span>
        </label>
        @error('is_published')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="date_iso" class="mb-1 block text-xs font-semibold text-slate-700">Tanggal</label>
        <input id="date_iso" type="date" name="date_iso" required value="{{ old('date_iso', $item->date_iso?->format('Y-m-d') ?? '') }}" class="w-full max-w-xs rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('date_iso') border-rose-400 @enderror">
        @error('date_iso')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="href" class="mb-1 block text-xs font-semibold text-slate-700">Tautan</label>
        <input id="href" type="text" name="href" required value="{{ old('href', $item->href) }}" maxlength="2048" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-xs @error('href') border-rose-400 @enderror">
        @error('href')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul (ID)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $item->title_id) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_id') border-rose-400 @enderror">
            @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (EN)</label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $item->title_en) }}" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_en') border-rose-400 @enderror">
            @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

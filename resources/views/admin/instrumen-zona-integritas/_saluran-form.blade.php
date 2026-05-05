@php
    /** @var \App\Models\ZiComplaintChannel $channel */
@endphp
<div class="space-y-6">
    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $channel->sort_order ?? 0) }}" class="w-full max-w-xs rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('sort_order') border-rose-400 @enderror">
        @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="is_published" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary" @checked(old('is_published', $channel->is_published ?? false))>
            <span class="text-xs font-semibold text-slate-800">Tampilkan di situs publik</span>
        </label>
        @error('is_published')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="external" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary" @checked(old('external', $channel->external ?? false))>
            <span class="text-xs font-semibold text-slate-800">Buka tautan di tab baru (eksternal)</span>
        </label>
        @error('external')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="href" class="mb-1 block text-xs font-semibold text-slate-700">Tautan (URL atau #)</label>
        <input id="href" type="text" name="href" required value="{{ old('href', $channel->href) }}" maxlength="2048" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-xs @error('href') border-rose-400 @enderror">
        @error('href')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul (ID)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $channel->title_id) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_id') border-rose-400 @enderror">
            @error('title_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (EN)</label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $channel->title_en) }}" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('title_en') border-rose-400 @enderror">
            @error('title_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="summary_id" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan (ID)</label>
            <textarea id="summary_id" name="summary_id" rows="4" required maxlength="5000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('summary_id') border-rose-400 @enderror">{{ old('summary_id', $channel->summary_id) }}</textarea>
            @error('summary_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="summary_en" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan (EN)</label>
            <textarea id="summary_en" name="summary_en" rows="4" maxlength="5000" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm @error('summary_en') border-rose-400 @enderror">{{ old('summary_en', $channel->summary_en) }}</textarea>
            @error('summary_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

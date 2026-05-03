@php
    /** @var \App\Models\CooperationPartner $partner */
    $isEdit = $partner->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $partner->sort_order ?? 0) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror"
                aria-describedby="partner-sort-help">
            <p id="partner-sort-help" class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">
                Angka <strong>lebih kecil</strong> dipajang lebih awal dalam tabel kerjasama.
            </p>
            @error('sort_order')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="name_id" class="mb-1 block text-xs font-semibold text-slate-700">Nama instansi (Bahasa Indonesia)</label>
            <input id="name_id" type="text" name="name_id" required value="{{ old('name_id', $partner->name_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_id') border-rose-400 @enderror">
            @error('name_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name_en" class="mb-1 block text-xs font-semibold text-slate-700">Nama instansi (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="name_en" type="text" name="name_en" value="{{ old('name_en', $partner->name_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_en') border-rose-400 @enderror">
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cooperation_id" class="mb-1 block text-xs font-semibold text-slate-700">Bentuk kerjasama (Bahasa Indonesia)</label>
            <textarea id="cooperation_id" name="cooperation_id" rows="4" required maxlength="500"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('cooperation_id') border-rose-400 @enderror">{{ old('cooperation_id', $partner->cooperation_id) }}</textarea>
            @error('cooperation_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="cooperation_en" class="mb-1 block text-xs font-semibold text-slate-700">Bentuk kerjasama (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <textarea id="cooperation_en" name="cooperation_en" rows="4" maxlength="500"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('cooperation_en') border-rose-400 @enderror">{{ old('cooperation_en', $partner->cooperation_en) }}</textarea>
            @error('cooperation_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="logo" class="mb-1 block text-xs font-semibold text-slate-700">Logo</label>
        <p class="mb-2 text-[11px] text-slate-500">PNG, JPG, atau WebP — maks. 4&nbsp;MB. Lewati untuk mempertahankan logo yang sudah tersimpan.</p>
        <input id="logo" type="file" name="logo" accept="image/jpeg,image/png,image/webp"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('logo') border-rose-400 @enderror">
        @error('logo')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && ($partner->logo ?? '') !== '')
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <img src="{{ $partner->resolvedLogoUrl() }}" alt="" class="h-16 w-16 rounded-xl border border-slate-200 object-contain p-1 shadow-sm" width="64" height="64">
                <p class="max-w-xs text-[11px] leading-relaxed text-slate-500">Berkas: <code class="rounded bg-slate-100 px-1">{{ $partner->logo }}</code></p>
            </div>
        @endif
    </div>
</div>

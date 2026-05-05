@php
    /** @var \App\Models\S2Program $program */
    $isEdit = $program->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $program->sort_order ?? 0) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror">
            @error('sort_order')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="slug" class="mb-1 block text-xs font-semibold text-slate-700">Slug URL <span class="font-normal text-slate-400">(opsional, huruf kecil &amp; tanda hubung)</span></label>
        <p class="mb-2 text-[11px] text-slate-500">Digunakan di alamat <code class="rounded bg-slate-100 px-1">/s2?program=<strong>slug</strong></code>. Kosongkan untuk dibuat otomatis dari nama Indonesia.</p>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $program->slug) }}" maxlength="255" pattern="[a-z0-9]+(-[a-z0-9]+)*"
            class="w-full max-w-md rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('slug') border-rose-400 @enderror"
            placeholder="contoh: pendidikan">
        @error('slug')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="name_id" class="mb-1 block text-xs font-semibold text-slate-700">Nama program (Bahasa Indonesia)</label>
            <input id="name_id" type="text" name="name_id" required value="{{ old('name_id', $program->name_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_id') border-rose-400 @enderror">
            @error('name_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name_en" class="mb-1 block text-xs font-semibold text-slate-700">Nama program (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="name_en" type="text" name="name_en" value="{{ old('name_en', $program->name_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_en') border-rose-400 @enderror">
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="blurb_id" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi singkat (Bahasa Indonesia)</label>
            <textarea id="blurb_id" name="blurb_id" rows="5" required maxlength="5000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('blurb_id') border-rose-400 @enderror">{{ old('blurb_id', $program->blurb_id) }}</textarea>
            @error('blurb_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="blurb_en" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi singkat (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <textarea id="blurb_en" name="blurb_en" rows="5" maxlength="5000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('blurb_en') border-rose-400 @enderror">{{ old('blurb_en', $program->blurb_en) }}</textarea>
            @error('blurb_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="official_url" class="mb-1 block text-xs font-semibold text-slate-700">Situs web resmi prodi <span class="font-normal text-slate-400">(opsional)</span></label>
        <input id="official_url" type="url" name="official_url" value="{{ old('official_url', $program->official_url) }}" maxlength="2048"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('official_url') border-rose-400 @enderror"
            placeholder="https://…">
        @error('official_url')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

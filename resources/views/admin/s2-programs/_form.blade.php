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
            <label for="excerpt_id" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan beranda (Bahasa Indonesia) <span class="font-normal text-slate-400">(opsional)</span></label>
            <p class="mb-2 text-[11px] text-slate-500">Teks singkat untuk kartu program di beranda. Kosongkan untuk menampilkan kalimat pengganti singkat di situs publik.</p>
            <textarea id="excerpt_id" name="excerpt_id" rows="3" maxlength="2000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('excerpt_id') border-rose-400 @enderror">{{ old('excerpt_id', $program->excerpt_id) }}</textarea>
            @error('excerpt_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="excerpt_en" class="mb-1 block text-xs font-semibold text-slate-700">Ringkasan beranda (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <p class="mb-2 text-[11px] text-slate-500">Versi Inggris untuk beranda (locale EN).</p>
            <textarea id="excerpt_en" name="excerpt_en" rows="3" maxlength="2000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('excerpt_en') border-rose-400 @enderror">{{ old('excerpt_en', $program->excerpt_en) }}</textarea>
            @error('excerpt_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="blurb_id" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi lengkap (Bahasa Indonesia)</label>
            <p class="mb-2 text-[11px] text-slate-500">Teks panjang di halaman <span class="font-mono">/s2</span> saat program dipilih.</p>
            <textarea id="blurb_id" name="blurb_id" rows="5" required maxlength="5000"
                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('blurb_id') border-rose-400 @enderror">{{ old('blurb_id', $program->blurb_id) }}</textarea>
            @error('blurb_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="blurb_en" class="mb-1 block text-xs font-semibold text-slate-700">Deskripsi lengkap (English) <span class="font-normal text-slate-400">(opsional)</span></label>
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
    <div>
        <label for="brochure_image" class="mb-1 block text-xs font-semibold text-slate-700">Brosur pendaftaran <span class="font-normal text-slate-400">(gambar, opsional)</span></label>
        <p class="mb-2 text-[11px] text-slate-500">PNG, JPG, atau WebP — maks. 5&nbsp;MB. Tampil di halaman publik <span class="font-mono">/s2</span> sebagai pratinjau kecil; pengunjung dapat membuka ukuran besar.</p>
        <input id="brochure_image" type="file" name="brochure_image" accept="image/jpeg,image/png,image/webp"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('brochure_image') border-rose-400 @enderror">
        @error('brochure_image')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && trim((string) ($program->brochure_image ?? '')) !== '')
            <div class="mt-3 flex flex-wrap items-start gap-4">
                <a href="{{ $program->resolvedBrochureUrl() }}" target="_blank" rel="noopener noreferrer" class="shrink-0 rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
                    <img src="{{ $program->resolvedBrochureUrl() }}" alt="" class="h-24 max-w-[10rem] rounded-md object-contain" width="160" height="96" loading="lazy">
                </a>
                <label class="flex cursor-pointer items-center gap-2 text-[11px] text-slate-600">
                    <input type="checkbox" name="remove_brochure" value="1" class="rounded border-slate-300 text-primary focus:ring-primary/30">
                    <span>Hapus brosur yang tersimpan</span>
                </label>
            </div>
        @endif
    </div>
</div>

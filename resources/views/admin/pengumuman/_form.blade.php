@php
    /** @var \App\Models\AnnouncementItem $announcement */
@endphp
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
        <label class="flex items-start gap-3 text-sm text-slate-700">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30" @checked(old('is_published', $announcement->is_published ?? false))>
            <span>
                <span class="font-semibold text-slate-800">Tayangkan di situs publik</span>
                <span class="mt-0.5 block text-xs text-slate-500">Jika tidak dicentang, item tetap sebagai draf dan tidak tampil di beranda.</span>
            </span>
        </label>
        @error('is_published')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $announcement->sort_order ?? 0) }}"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror">
        <p class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">Angka lebih kecil ditampilkan lebih dulu pada daftar pengumuman di beranda.</p>
        @error('sort_order')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul (Bahasa Indonesia)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $announcement->title_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_id') border-rose-400 @enderror">
            @error('title_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $announcement->title_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_en') border-rose-400 @enderror">
            @error('title_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="date_iso" class="mb-1 block text-xs font-semibold text-slate-700">Tanggal</label>
            <input id="date_iso" type="date" name="date_iso" required value="{{ old('date_iso', $announcement->date_iso ? \Illuminate\Support\Carbon::parse($announcement->date_iso)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('date_iso') border-rose-400 @enderror">
            @error('date_iso')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="href" class="mb-1 block text-xs font-semibold text-slate-700">Tautan</label>
            <input id="href" type="text" name="href" required value="{{ old('href', $announcement->href ?? '#') }}" maxlength="500"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('href') border-rose-400 @enderror">
            <p class="mt-1 text-[11px] text-slate-500">Gunakan URL penuh (mis. https://...) atau <code class="rounded bg-slate-100 px-1">#</code> jika belum ada tujuan.</p>
            @error('href')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


@php
    /** @var \App\Models\AcademicGuide $guide */
    $isEdit = $guide->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $guide->sort_order ?? 0) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror"
                aria-describedby="guide-sort-help">
            <p id="guide-sort-help" class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">
                Angka <strong>lebih kecil</strong> tampil lebih awal dalam daftar unduhan.
            </p>
            @error('sort_order')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="name_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul menu (Bahasa Indonesia)</label>
            <input id="name_id" type="text" name="name_id" required value="{{ old('name_id', $guide->name_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_id') border-rose-400 @enderror">
            @error('name_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul menu (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="name_en" type="text" name="name_en" value="{{ old('name_en', $guide->name_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_en') border-rose-400 @enderror">
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="pdf" class="mb-1 block text-xs font-semibold text-slate-700">Berkas PDF</label>
        <p class="mb-2 text-[11px] text-slate-500">Hanya PDF — maks. 20&nbsp;MB.@if($isEdit) Kosongkan untuk mempertahankan berkas yang sudah ada.@endif</p>
        <input id="pdf" type="file" name="pdf" accept="application/pdf"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('pdf') border-rose-400 @enderror"
            @if(!$isEdit) required @endif>
        @error('pdf')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && ($guide->file_path ?? '') !== '')
            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">Berkas saat ini: <code class="rounded bg-slate-100 px-1">{{ $guide->file_path }}</code></p>
            <p class="mt-2">
                <a href="{{ asset(ltrim($guide->resolvedFilePublicPath(), '/')) }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-primary hover:underline">Pratinjau unduhan ↗</a>
            </p>
        @endif
    </div>
    <div class="rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3">
        <input type="hidden" name="show_on_academic_calendar" value="0">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="show_on_academic_calendar" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                @checked(old('show_on_academic_calendar', $guide->show_on_academic_calendar ?? false))>
            <span>
                <span class="block text-xs font-semibold text-slate-800">Tampilkan di halaman Kalender Akademik</span>
                <span class="mt-1 block text-[11px] leading-relaxed text-slate-500">Jika dicentang, PDF ini muncul di pemilih tahun pada <code class="rounded bg-white px-1 font-mono">/kalender-akademik</code> dengan judul yang sama seperti di Panduan Akademik.</span>
            </span>
        </label>
        @error('show_on_academic_calendar')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@php
    /** @var \App\Models\LeadershipPerson $person */
    $isEdit = $person->exists;
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $person->sort_order) }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('sort_order') border-rose-400 @enderror"
                aria-describedby="sort-order-help">
            <p id="sort-order-help" class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">
                <span class="font-semibold text-slate-800">Cara pakai angka:</span> angka <strong>lebih kecil</strong> tampil lebih dulu (biasanya paling atas), angka <strong>lebih besar</strong> mengikuti di bawahnya — sesuai struktur resmi (mis. Direktur = 0 atau 1, lalu Wakil 2, 3, …). Tidak harus berurutan 1, 2, 3; jarak antar angka boleh (0, 10, 20) jika nanti ingin menyisip orang baru.
            </p>
            @error('sort_order')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="nip" class="mb-1 block text-xs font-semibold text-slate-700">NIP <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="nip" type="text" name="nip" value="{{ old('nip', $person->nip) }}" maxlength="128"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('nip') border-rose-400 @enderror">
            @error('nip')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="name" class="mb-1 block text-xs font-semibold text-slate-700">Nama lengkap & gelar</label>
        <input id="name" type="text" name="name" required value="{{ old('name', $person->name) }}" maxlength="191"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name') border-rose-400 @enderror">
        @error('name')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="role_id" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan (Bahasa Indonesia)</label>
            <input id="role_id" type="text" name="role_id" required value="{{ old('role_id', $person->role_id) }}" maxlength="191"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('role_id') border-rose-400 @enderror">
            @error('role_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="role_en" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="role_en" type="text" name="role_en" value="{{ old('role_en', $person->role_en) }}" maxlength="191"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('role_en') border-rose-400 @enderror">
            @error('role_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="photo" class="mb-1 block text-xs font-semibold text-slate-700">Foto</label>
        <p class="mb-2 text-[11px] text-slate-500">PNG, JPG, atau WebP — maks. 4&nbsp;MB. Lewati jika pakai foto yang sudah ada.</p>
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('photo') border-rose-400 @enderror">
        @error('photo')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && ($person->photo ?? '') !== '')
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <img src="{{ $person->resolvedPhotoUrl() }}" alt="" class="h-24 w-32 rounded-xl border border-slate-200 object-cover shadow-sm" width="128" height="96">
                <p class="max-w-xs text-[11px] leading-relaxed text-slate-500">Tersimpan: <code class="rounded bg-slate-100 px-1">{{ $person->photo }}</code></p>
            </div>
        @endif
    </div>
</div>

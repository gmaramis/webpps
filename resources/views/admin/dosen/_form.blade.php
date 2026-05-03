@php
    /** @var \App\Models\Lecturer $lecturer */
    $isEdit = $lecturer->exists;
@endphp
<div class="space-y-6">
    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil di halaman publik</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $lecturer->sort_order ?? 0) }}"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror"
            aria-describedby="lec-sort-help">
        <p id="lec-sort-help" class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">
            Angka <strong>lebih kecil</strong> lebih dulu dalam daftar dosen yang diunduh dari basis data (tabel utama mengurutkan sendiri nama/NIDN saat pengunjung memilih).</p>
        @error('sort_order')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="name_id" class="mb-1 block text-xs font-semibold text-slate-700">Nama lengkap &amp; gelar (Bahasa Indonesia)</label>
            <input id="name_id" type="text" name="name_id" required value="{{ old('name_id', $lecturer->name_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_id') border-rose-400 @enderror">
            @error('name_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name_en" class="mb-1 block text-xs font-semibold text-slate-700">Nama lengkap &amp; gelar (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="name_en" type="text" name="name_en" value="{{ old('name_en', $lecturer->name_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name_en') border-rose-400 @enderror">
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="nidn" class="mb-1 block text-xs font-semibold text-slate-700">NIDN <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="nidn" type="text" name="nidn" value="{{ old('nidn', $lecturer->nidn) }}" maxlength="32"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('nidn') border-rose-400 @enderror">
            @error('nidn')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="nip" class="mb-1 block text-xs font-semibold text-slate-700">NIP <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="nip" type="text" name="nip" value="{{ old('nip', $lecturer->nip) }}" maxlength="128"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('nip') border-rose-400 @enderror">
            @error('nip')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="study_program_id" class="mb-1 block text-xs font-semibold text-slate-700">Program studi (Bahasa Indonesia)</label>
            <input id="study_program_id" type="text" name="study_program_id" required value="{{ old('study_program_id', $lecturer->study_program_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('study_program_id') border-rose-400 @enderror">
            @error('study_program_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="study_program_en" class="mb-1 block text-xs font-semibold text-slate-700">Program studi (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="study_program_en" type="text" name="study_program_en" value="{{ old('study_program_en', $lecturer->study_program_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('study_program_en') border-rose-400 @enderror">
            @error('study_program_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="functional_role_id" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan fungsional (Bahasa Indonesia)</label>
            <input id="functional_role_id" type="text" name="functional_role_id" required value="{{ old('functional_role_id', $lecturer->functional_role_id) }}" maxlength="191"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('functional_role_id') border-rose-400 @enderror">
            @error('functional_role_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="functional_role_en" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan fungsional (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="functional_role_en" type="text" name="functional_role_en" value="{{ old('functional_role_en', $lecturer->functional_role_en) }}" maxlength="191"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('functional_role_en') border-rose-400 @enderror">
            @error('functional_role_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div>
        <label for="phone" class="mb-1 block text-xs font-semibold text-slate-700">Kontak / telepon <span class="font-normal text-slate-400">(opsional)</span></label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $lecturer->phone) }}" maxlength="64"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('phone') border-rose-400 @enderror">
        @error('phone')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="photo" class="mb-1 block text-xs font-semibold text-slate-700">Foto</label>
        <p class="mb-2 text-[11px] text-slate-500">PNG, JPG, atau WebP — maks. 4&nbsp;MB. Lewati jika memakai path aset statis (mis. <code class="rounded bg-slate-100 px-1">faculty/…</code>) yang sudah tersimpan.</p>
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp"
            class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:border-primary/40 @error('photo') border-rose-400 @enderror">
        @error('photo')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($isEdit && ($lecturer->photo ?? '') !== '')
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <img src="{{ $lecturer->resolvedPhotoUrl() }}" alt="" class="h-20 w-20 rounded-xl border border-slate-200 object-cover shadow-sm" width="80" height="80">
                <p class="max-w-md text-[11px] leading-relaxed text-slate-500">Berkas / path: <code class="rounded bg-slate-100 px-1 break-all">{{ $lecturer->photo }}</code></p>
            </div>
        @endif
    </div>
</div>

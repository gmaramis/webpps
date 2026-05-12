@php
    /** @var \App\Models\Lecturer $lecturer */
    $isEdit = $lecturer->exists;
    $studyProgramGroups = $studyProgramGroups ?? ['s2' => collect(), 's3' => collect()];
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
    <div>
        @php
            $allowedProgramIds = \App\Models\Lecturer::studyProgramNameIdsFromDatabase();
            $currentProgram = old('study_program_id', $lecturer->study_program_id ?? '');
            $currentProgram = is_string($currentProgram) ? trim($currentProgram) : '';
            $programValid = in_array($currentProgram, $allowedProgramIds, true);
            $hasPrograms = $studyProgramGroups['s2']->isNotEmpty() || $studyProgramGroups['s3']->isNotEmpty();
        @endphp
        <label for="study_program_id" class="mb-1 block text-xs font-semibold text-slate-700">Program studi</label>
        <select id="study_program_id" name="study_program_id" required
            class="w-full max-w-2xl cursor-pointer rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2.5 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('study_program_id') border-rose-400 @enderror">
            <option value="" {{ $programValid ? '' : 'selected' }}>— Pilih program studi —</option>
            @if ($studyProgramGroups['s2']->isNotEmpty())
                <optgroup label="Magister (S2)">
                    @foreach ($studyProgramGroups['s2'] as $p)
                        <option value="{{ $p->name_id }}" @selected($currentProgram === trim($p->name_id))>{{ $p->name_id }}</option>
                    @endforeach
                </optgroup>
            @endif
            @if ($studyProgramGroups['s3']->isNotEmpty())
                <optgroup label="Doktor (S3)">
                    @foreach ($studyProgramGroups['s3'] as $p)
                        <option value="{{ $p->name_id }}" @selected($currentProgram === trim($p->name_id))>{{ $p->name_id }}</option>
                    @endforeach
                </optgroup>
            @endif
        </select>
        <p class="mt-1.5 text-[11px] leading-relaxed text-slate-500">Daftar diambil dari program Magister dan Doktor di basis data. Teks bahasa Inggris di situs publik mengikuti nama program (EN) yang tersimpan di masing-masing prodi.</p>
        @if (! $hasPrograms)
            <p class="mt-2 rounded-lg border border-amber-200 bg-amber-50/90 px-3 py-2 text-[11px] leading-relaxed text-amber-900">
                Belum ada program studi di basis data. Kelola dulu lewat
                <a href="{{ route('admin.prodi-s2.index') }}" class="font-bold text-primary underline decoration-primary/40 underline-offset-2 hover:decoration-primary">Prodi Magister (S2)</a>
                atau
                <a href="{{ route('admin.prodi-s3.index') }}" class="font-bold text-primary underline decoration-primary/40 underline-offset-2 hover:decoration-primary">Prodi Doktor (S3)</a>.
            </p>
        @endif
        @if (! $programValid && $currentProgram !== '')
            <p class="mt-2 rounded-lg border border-amber-200 bg-amber-50/90 px-3 py-2 text-[11px] text-amber-900">Program saat ini tidak cocok dengan data prodi. Pilih ulang dari daftar lalu simpan.</p>
        @endif
        @error('study_program_id')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        @php
            $rankIds = \App\Models\Lecturer::functionalRankIds();
            $currentRank = old('functional_role_id', $lecturer->functional_role_id ?? '');
            $currentRank = is_string($currentRank) ? trim($currentRank) : '';
            $rankValid = in_array($currentRank, $rankIds, true);
        @endphp
        <label for="functional_role_id" class="mb-1 block text-xs font-semibold text-slate-700">Jabatan fungsional</label>
        <select id="functional_role_id" name="functional_role_id" required
            class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2.5 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-md @error('functional_role_id') border-rose-400 @enderror">
            <option value="" {{ $rankValid ? '' : 'selected' }}>— Pilih jabatan —</option>
            @foreach ($rankIds as $rid)
                <option value="{{ $rid }}" @selected($currentRank === $rid)>{{ $rid }}</option>
            @endforeach
        </select>
        <p class="mt-1.5 text-[11px] leading-relaxed text-slate-500">Teks bahasa Inggris di situs publik mengikuti jabatan ini secara otomatis (mis. Lektor → Lecturer).</p>
        @if (! $rankValid && $currentRank !== '')
            <p class="mt-2 rounded-lg border border-amber-200 bg-amber-50/90 px-3 py-2 text-[11px] text-amber-900">Nilai saat ini tidak termasuk daftar standar. Pilih salah satu opsi di atas lalu simpan untuk menyelaraskan data.</p>
        @endif
        @error('functional_role_id')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="rounded-2xl border border-slate-200/90 bg-gradient-to-br from-slate-50/90 to-sky-50/20 p-4 shadow-sm ring-1 ring-slate-100/80 md:p-5">
        <h2 class="text-sm font-bold tracking-tight text-slate-900">Kontak &amp; Google Scholar <span class="font-normal text-slate-500">(tampil di halaman publik <code class="rounded bg-white/80 px-1 py-0.5 text-[11px] font-mono text-slate-600">/dosen</code>)</span></h2>
        <p class="mt-1.5 text-[11px] leading-relaxed text-slate-600">Isi email untuk tautan <code class="rounded bg-white/70 px-1">mailto:</code> di situs. URL Scholar harus lengkap (<code class="rounded bg-white/70 px-1">https://…</code>), biasanya halaman profil <em>citations?user=…</em> di Google Scholar.</p>
        <div class="mt-4 space-y-4">
            <div>
                <label for="phone" class="mb-1 block text-xs font-semibold text-slate-700">Kontak / telepon <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $lecturer->phone) }}" maxlength="64"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('phone') border-rose-400 @enderror">
                @error('phone')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="mb-1 block text-xs font-semibold text-slate-700">Email <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email', $lecturer->email) }}" maxlength="255" autocomplete="email"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('email') border-rose-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="google_scholar_url" class="mb-1 block text-xs font-semibold text-slate-700">URL Google Scholar <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="google_scholar_url" type="url" name="google_scholar_url" value="{{ old('google_scholar_url', $lecturer->google_scholar_url) }}" maxlength="512" placeholder="https://scholar.google.com/citations?user=…"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-xs transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('google_scholar_url') border-rose-400 @enderror">
                    @error('google_scholar_url')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
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

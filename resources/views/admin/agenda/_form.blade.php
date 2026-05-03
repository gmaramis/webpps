@php
    /** @var \App\Models\AgendaItem $agenda */
    $monthOptions = [
        ['id' => 'JAN', 'en' => 'JAN', 'label' => 'Januari'],
        ['id' => 'FEB', 'en' => 'FEB', 'label' => 'Februari'],
        ['id' => 'MAR', 'en' => 'MAR', 'label' => 'Maret'],
        ['id' => 'APR', 'en' => 'APR', 'label' => 'April'],
        ['id' => 'MEI', 'en' => 'MAY', 'label' => 'Mei'],
        ['id' => 'JUN', 'en' => 'JUN', 'label' => 'Juni'],
        ['id' => 'JUL', 'en' => 'JUL', 'label' => 'Juli'],
        ['id' => 'AGS', 'en' => 'AUG', 'label' => 'Agustus'],
        ['id' => 'SEP', 'en' => 'SEP', 'label' => 'September'],
        ['id' => 'OKT', 'en' => 'OCT', 'label' => 'Oktober'],
        ['id' => 'NOV', 'en' => 'NOV', 'label' => 'November'],
        ['id' => 'DES', 'en' => 'DEC', 'label' => 'Desember'],
    ];
    $monthEnById = collect($monthOptions)->mapWithKeys(fn (array $m): array => [$m['id'] => $m['en']])->all();
    $selectedMonthId = old('month_id', $agenda->month_id);
    $selectedMonthEn = old('month_en', $agenda->month_en ?? ($monthEnById[$selectedMonthId] ?? 'JAN'));
@endphp
<div class="space-y-6">
    <div>
        <label for="sort_order" class="mb-1 block text-xs font-semibold text-slate-700">Urutan tampil</label>
        <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $agenda->sort_order ?? 0) }}"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:max-w-xs @error('sort_order') border-rose-400 @enderror">
        <p class="mt-2 rounded-xl border border-sky-100 bg-sky-50/80 px-3 py-2 text-[11px] leading-relaxed text-slate-600">Angka lebih kecil ditampilkan lebih dulu pada daftar agenda di beranda.</p>
        @error('sort_order')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label for="day" class="mb-1 block text-xs font-semibold text-slate-700">Hari (angka)</label>
            <input id="day" type="text" name="day" required value="{{ old('day', $agenda->day) }}" maxlength="2" placeholder="05"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('day') border-rose-400 @enderror">
            <p class="mt-1 text-[11px] text-slate-500">Format wajib 2 digit: <code class="rounded bg-slate-100 px-1">01</code> s/d <code class="rounded bg-slate-100 px-1">31</code>.</p>
            @error('day')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="month_id" class="mb-1 block text-xs font-semibold text-slate-700">Bulan (ID)</label>
            <select id="month_id" name="month_id" required
                class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('month_id') border-rose-400 @enderror">
                @foreach($monthOptions as $opt)
                    <option value="{{ $opt['id'] }}" data-month-en="{{ $opt['en'] }}" @selected($selectedMonthId === $opt['id'])>{{ $opt['label'] }} ({{ $opt['id'] }})</option>
                @endforeach
            </select>
            @error('month_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="month_en" class="mb-1 block text-xs font-semibold text-slate-700">Bulan (EN)</label>
            <select id="month_en" name="month_en"
                class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('month_en') border-rose-400 @enderror">
                @foreach($monthOptions as $opt)
                    <option value="{{ $opt['en'] }}" @selected($selectedMonthEn === $opt['en'])>{{ $opt['en'] }}</option>
                @endforeach
            </select>
            @error('month_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title_id" class="mb-1 block text-xs font-semibold text-slate-700">Judul agenda (Bahasa Indonesia)</label>
            <input id="title_id" type="text" name="title_id" required value="{{ old('title_id', $agenda->title_id) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_id') border-rose-400 @enderror">
            @error('title_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="title_en" class="mb-1 block text-xs font-semibold text-slate-700">Judul agenda (English) <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="title_en" type="text" name="title_en" value="{{ old('title_en', $agenda->title_en) }}" maxlength="255"
                class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('title_en') border-rose-400 @enderror">
            @error('title_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="href" class="mb-1 block text-xs font-semibold text-slate-700">Tautan</label>
        <input id="href" type="text" name="href" required value="{{ old('href', $agenda->href ?? '#') }}" maxlength="500"
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 font-mono text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('href') border-rose-400 @enderror">
        <p class="mt-1 text-[11px] text-slate-500">Gunakan URL penuh (mis. https://...) atau <code class="rounded bg-slate-100 px-1">#</code> jika belum ada tujuan.</p>
        @error('href')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthId = document.getElementById('month_id');
        const monthEn = document.getElementById('month_en');
        if (!monthId || !monthEn) return;

        const syncMonth = () => {
            const selected = monthId.options[monthId.selectedIndex];
            if (!selected) return;
            const mappedEn = selected.getAttribute('data-month-en') || '';
            if (mappedEn !== '') {
                monthEn.value = mappedEn;
            }
        };

        monthId.addEventListener('change', syncMonth);
    });
</script>
@endpush


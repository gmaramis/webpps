{{-- Tayang/draf: satu baris status + satu tombol aksi (POST toggle-publish). --}}
@php
    /** @var bool $published */
    /** @var string $action */
    /** @var string $scope Untuk tooltip (lokasi di situs), mis. "bagian galeri …" */
    $scope = $scope ?? 'situs';
@endphp
<div class="flex flex-col gap-1.5">
    <p class="text-xs leading-snug text-slate-600">
        @if($published)
            <span class="font-semibold text-emerald-700">Tayang</span>
            <span class="text-slate-500">·</span>
            tamu situs bisa melihat.
        @else
            <span class="font-semibold text-slate-700">Draf</span>
            <span class="text-slate-500">·</span>
            tamu situs belum melihat.
        @endif
    </p>
    <form method="post" action="{{ $action }}" class="inline">@csrf
        @include('admin.components.form-page-hidden')
        <button type="submit"
            title="{{ $published ? 'Menyembunyikan dari: '.$scope : 'Menampilkan di: '.$scope }}"
            class="inline-flex items-center rounded-lg border px-2.5 py-1.5 text-xs font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-1 {{ $published ? 'border-slate-200 bg-white text-slate-800 hover:bg-slate-50' : 'border-emerald-600 bg-emerald-600 text-white hover:bg-emerald-700' }}">
            {{ $published ? 'Sembunyikan' : 'Tayangkan' }}
        </button>
    </form>
</div>

@extends('admin.layouts.app')

@section('title', 'Kegiatan alumni')
@section('heading', 'Kegiatan alumni')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Kegiatan alumni</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Data menampil halaman publik <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">/kegiatan-alumni</code>. Hanya entri <strong class="text-slate-800">ditayangkan</strong> yang muncul di situs. Struktur sama dengan key <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">ALUMNI_ACTIVITIES</code> di JSON. Urutan: angka <strong class="text-slate-800">lebih kecil</strong> lebih dulu.</p>
            @if($activities->isEmpty())
                <p class="mt-2 text-xs font-medium text-amber-800">Belum ada data — impor dari <code class="rounded bg-amber-50 px-1 font-mono">pps-content.json</code> atau tambah manual.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            @if($ppsJsonExists)
                <form method="post" action="{{ route('admin.kegiatan-alumni.import-json') }}" class="inline" onsubmit="return confirm('Ini akan menghapus semua kegiatan alumni di basis data dan menggantinya dari key ALUMNI_ACTIVITIES di resources/data/pps-content.json. Berkas gambar yang diunggah lewat admin (folder alumni-activities) ikut dihapus. Lanjut?');">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Impor dari JSON</button>
                </form>
            @endif
            <a href="{{ route('kegiatan-alumni') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Halaman publik ↗</a>
            <a href="{{ route('admin.kegiatan-alumni.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Tambah</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($activities->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Belum ada kegiatan alumni.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Gambar</th>
                            <th class="px-4 py-3">Judul (ID)</th>
                            <th class="hidden px-4 py-3 lg:table-cell">Ringkasan (ID)</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($activities as $a)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $a->sort_order }}</td>
                                <td class="px-4 py-2">
                                    <img src="{{ $a->resolvedImageUrl() }}" alt="" class="h-14 w-[5.5rem] rounded-lg border border-slate-200 object-cover" width="88" height="56" loading="lazy">
                                </td>
                                <td class="max-w-[14rem] px-4 py-3">
                                    <span class="font-semibold text-slate-900">{{ $a->title_id }}</span>
                                </td>
                                <td class="hidden max-w-[20rem] px-4 py-3 text-slate-600 lg:table-cell">{{ \Illuminate\Support\Str::limit($a->excerpt_id, 140) }}</td>
                                <td class="px-4 py-3">
                                    @include('admin.components.toggle-published', [
                                        'published' => $a->is_published,
                                        'action' => route('admin.kegiatan-alumni.toggle-publish', $a),
                                        'scope' => 'halaman kegiatan alumni',
                                    ])
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.kegiatan-alumni.edit', $a) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.kegiatan-alumni.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus kegiatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        @include('admin.components.form-page-hidden')
                                        <button type="submit" class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-600">
                    Menampilkan <strong>{{ $activities->firstItem() }}</strong>–<strong>{{ $activities->lastItem() }}</strong> dari <strong>{{ $activities->total() }}</strong> kegiatan
                </p>
                <div class="min-w-0 overflow-x-auto">{{ $activities->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection

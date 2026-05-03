@extends('admin.layouts.app')

@section('title', 'Pengumuman')
@section('heading', 'Pengumuman')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Pengumuman</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Data ini ditampilkan di blok <strong class="text-slate-800">Pengumuman</strong> pada halaman beranda <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">/</code>. Urutan kecil tampil dulu.</p>
            @if($announcements->isEmpty())
                <p class="mt-2 text-xs font-medium text-amber-800">Belum ada data — impor dari <code class="rounded bg-amber-50 px-1 font-mono">pps-content.json</code> (key ANNOUNCEMENTS) atau tambah manual.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            @if($ppsJsonExists)
                <form method="post" action="{{ route('admin.pengumuman.import-json') }}" class="inline" onsubmit="return confirm('Ini akan menghapus semua pengumuman di basis data dan menggantinya dari key ANNOUNCEMENTS di resources/data/pps-content.json. Lanjut?');">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Impor dari JSON</button>
                </form>
            @endif
            <a href="{{ route('home') }}#pengumuman-agenda" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Lihat di beranda ↗</a>
            <a href="{{ route('admin.pengumuman.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Tambah</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($announcements->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Belum ada pengumuman.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Judul (ID)</th>
                            <th class="hidden px-4 py-3 md:table-cell">Tautan</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($announcements as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $item->sort_order }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-600">{{ \Illuminate\Support\Carbon::parse($item->date_iso)->format('Y-m-d') }}</td>
                                <td class="max-w-[22rem] px-4 py-3 font-semibold text-slate-900">{{ \Illuminate\Support\Str::limit($item->title_id, 120) }}</td>
                                <td class="hidden max-w-[14rem] px-4 py-3 text-xs text-slate-500 md:table-cell">
                                    <code class="rounded bg-slate-100 px-1 py-0.5 break-all">{{ $item->href }}</code>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.pengumuman.edit', $item) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.pengumuman.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus pengumuman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection


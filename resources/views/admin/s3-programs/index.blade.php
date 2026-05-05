@extends('admin.layouts.app')

@section('title', 'Program S3 (Doktor)')
@section('heading', 'Program S3 (Doktor)')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Program S3</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Data menampil halaman publik <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">/s3</code>. Pengunjung memilih program (parameter URL <code class="rounded bg-slate-100 px-1 font-mono">program</code>) lalu melihat deskripsi singkat dan tautan situs resmi prodi.</p>
            @if($programs->isEmpty())
                <p class="mt-2 text-xs font-medium text-amber-800">Belum ada data — impor dari <code class="rounded bg-amber-50 px-1 font-mono">pps-content.json</code> atau tambah manual.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            @if($ppsJsonExists)
                <form method="post" action="{{ route('admin.prodi-s3.import-json') }}" class="inline" onsubmit="return confirm('Ini akan menghapus semua program S3 di basis data dan menggantinya dari key PROGRAMS_DOKTOR di resources/data/pps-content.json. Lanjut?');">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Impor dari JSON</button>
                </form>
            @endif
            <a href="{{ route('program.s3') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Halaman publik ↗</a>
            <a href="{{ route('admin.prodi-s3.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Tambah program</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($programs->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Belum ada program S3.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Slug URL</th>
                            <th class="px-4 py-3">Nama (ID)</th>
                            <th class="hidden px-4 py-3 lg:table-cell">Situs resmi</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($programs as $p)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $p->sort_order }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $p->slug }}</td>
                                <td class="max-w-[14rem] px-4 py-3 font-semibold text-slate-900">{{ $p->name_id }}</td>
                                <td class="hidden max-w-[12rem] truncate px-4 py-3 text-xs text-slate-500 lg:table-cell">{{ $p->official_url ? \Illuminate\Support\Str::limit($p->official_url, 48) : '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.prodi-s3.edit', $p) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.prodi-s3.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus program ini?');">
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

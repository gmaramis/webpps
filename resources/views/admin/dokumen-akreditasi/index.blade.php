@extends('admin.layouts.app')

@section('title', 'Dokumen akreditasi')
@section('heading', 'Dokumen akreditasi')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Dokumen akreditasi</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Daftar ini menampil halaman publik <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">/dokumen-akreditasi</code>. Unggah hanya berkas <strong class="text-slate-800">PDF</strong>. Urutan: angka <strong>kecil dulu</strong>.</p>
            @if($documents->isEmpty())
                <p class="mt-2 text-xs font-medium text-amber-800">Belum ada dokumen — tambah dengan tombol di kanan.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dokumen-akreditasi') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Halaman publik ↗</a>
            <a href="{{ route('admin.dokumen-akreditasi.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Unggah PDF</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($documents->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Belum ada dokumen akreditasi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Judul (ID)</th>
                            <th class="whitespace-nowrap px-4 py-3">Tayang</th>
                            <th class="hidden px-4 py-3 lg:table-cell">Berkas</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($documents as $d)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $d->sort_order }}</td>
                                <td class="max-w-[18rem] px-4 py-3 font-semibold text-slate-900">{{ $d->title_id }}</td>
                                <td class="px-4 py-3">
                                    @if($d->is_published)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800">Ya</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-600">Tidak</span>
                                    @endif
                                </td>
                                <td class="hidden max-w-[20rem] px-4 py-3 font-mono text-xs text-slate-500 lg:table-cell">{{ \Illuminate\Support\Str::limit($d->file_path, 80) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.dokumen-akreditasi.edit', $d) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.dokumen-akreditasi.destroy', $d) }}" class="inline" onsubmit="return confirm('Hapus dokumen ini beserta berkas PDF?');">
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

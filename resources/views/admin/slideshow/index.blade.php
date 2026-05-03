@extends('admin.layouts.app')

@section('title', 'Slideshow beranda')
@section('heading', 'Slideshow beranda')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Slideshow beranda</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Kelola gambar yang tampil pada hero slider halaman <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">/</code>. Urutan kecil tampil lebih dulu.</p>
            @if($usingBuiltinFallback)
                <p class="mt-2 text-xs font-medium text-amber-800">Saat ini memakai fallback bawaan: <code class="rounded bg-amber-50 px-1">slides/slide-1.svg</code> sampai <code class="rounded bg-amber-50 px-1">slide-3.svg</code>. Tambah slide agar memakai data basis data.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="post" action="{{ route('admin.slideshow.restore-built-in') }}" class="inline" onsubmit="return confirm('Ini akan menghapus semua slide yang tersimpan dan mengembalikan tiga slide bawaan. Lanjut?');">
                @csrf
                <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Kembalikan bawaan</button>
            </form>
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Lihat beranda ↗</a>
            <a href="{{ route('admin.slideshow.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Tambah slide</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($slides->isEmpty())
            <div class="space-y-2 px-6 py-12 text-center">
                <p class="text-sm text-slate-600">Belum ada slide tersimpan di basis data.</p>
                <p class="text-xs text-slate-500">Hero slider publik tetap tampil dari gambar bawaan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Pratinjau</th>
                            <th class="px-4 py-3">Path gambar</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($slides as $slide)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $slide->sort_order }}</td>
                                <td class="px-4 py-2">
                                    <img src="{{ $slide->resolvedImageUrl() }}" alt="" class="h-12 w-24 rounded-lg border border-slate-200 object-cover" width="96" height="48" loading="lazy">
                                </td>
                                <td class="max-w-[18rem] px-4 py-3 text-xs text-slate-600">
                                    <code class="rounded bg-slate-100 px-1 py-0.5 break-all">{{ $slide->image }}</code>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.slideshow.edit', $slide) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.slideshow.destroy', $slide) }}" class="inline" onsubmit="return confirm('Hapus slide ini?');">
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

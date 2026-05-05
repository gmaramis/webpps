@extends('admin.layouts.app')

@section('title', 'Stop Korupsi — poin')
@section('heading', 'Stop Korupsi — daftar poin')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span>/</span>
        <a href="{{ route('admin.stop-korupsi.hub') }}" class="font-semibold text-primary hover:underline">Stop Korupsi</a>
        <span>/</span>
        <span class="font-medium text-slate-900">Poin</span>
    </nav>

    <div class="flex flex-wrap justify-between gap-3">
        <p class="text-sm text-slate-600">Setiap baris = satu bullet di kotak merah muda pada <code class="rounded bg-slate-100 px-1 font-mono text-xs">/stop-korupsi</code>. Urutan lebih kecil tampil lebih dulu.</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('stop-korupsi') }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-primary">Halaman publik ↗</a>
            <a href="{{ route('admin.stop-korupsi.poin.create') }}" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white">+ Tambah poin</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl ring-1 ring-white/70">
        @if($rows->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-slate-500">Belum ada poin. Tambah manual atau impor dari JSON lewat hub.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Teks (ID)</th>
                            <th class="hidden px-4 py-3 md:table-cell">Teks (EN)</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rows as $r)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 pl-6 font-bold">{{ $r->sort_order }}</td>
                                <td class="max-w-md px-4 py-3 text-slate-800">{{ \Illuminate\Support\Str::limit($r->text_id, 120) }}</td>
                                <td class="hidden max-w-md px-4 py-3 text-slate-600 md:table-cell">{{ \Illuminate\Support\Str::limit($r->text_en ?? '—', 120) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.stop-korupsi.poin.edit', $r) }}" class="mr-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary">Edit</a>
                                    <form method="post" action="{{ route('admin.stop-korupsi.poin.destroy', $r) }}" class="inline" onsubmit="return confirm('Hapus poin ini?');">@csrf @method('DELETE')
                                        @include('admin.components.form-page-hidden')
                                        <button type="submit" class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-600">
                    Menampilkan <strong>{{ $rows->firstItem() }}</strong>–<strong>{{ $rows->lastItem() }}</strong> dari <strong>{{ $rows->total() }}</strong> poin
                </p>
                <div class="min-w-0 overflow-x-auto">{{ $rows->links() }}</div>
            </div>
        @endif
    </div>

    <a href="{{ route('admin.stop-korupsi.hub') }}" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">← Kembali ke hub</a>
</div>
@endsection

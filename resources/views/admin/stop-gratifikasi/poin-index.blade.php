@extends('admin.layouts.app')

@section('title', 'Poin Stop Gratifikasi')
@section('heading', 'Stop Gratifikasi — daftar poin')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span>/</span>
        <a href="{{ route('admin.stop-gratifikasi.hub') }}" class="font-semibold text-primary hover:underline">Stop Gratifikasi</a>
        <span>/</span>
        <span class="font-medium text-slate-900">Poin</span>
    </nav>

    <div class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-md sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">Setiap baris = satu bullet di kotak kuning pada <code class="rounded bg-slate-100 px-1 font-mono text-xs">/stop-gratifikasi</code>. Urutan lebih kecil tampil lebih dulu.</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('stop-gratifikasi') }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-primary">Halaman publik ↗</a>
            <a href="{{ route('admin.stop-gratifikasi.poin.create') }}" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white">+ Tambah poin</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white/90 shadow-md">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Urutan</th>
                    <th class="px-4 py-3">Teks ID</th>
                    <th class="px-4 py-3">Teks EN</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $r)
                    <tr class="hover:bg-slate-50/50">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs">{{ $r->sort_order }}</td>
                        <td class="max-w-md px-4 py-3 text-slate-800">{{ \Illuminate\Support\Str::limit($r->text_id, 120) }}</td>
                        <td class="max-w-md px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($r->text_en ?? '—', 120) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('admin.stop-gratifikasi.poin.edit', $r) }}" class="mr-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary">Edit</a>
                            <form method="post" action="{{ route('admin.stop-gratifikasi.poin.destroy', $r) }}" class="inline" onsubmit="return confirm('Hapus poin ini?');">@csrf @method('DELETE')
                                @include('admin.components.form-page-hidden')
                                <button type="submit" class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada poin. Impor dari JSON atau tambah manual.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center">{{ $rows->links() }}</div>

    <a href="{{ route('admin.stop-gratifikasi.hub') }}" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">← Kembali ke hub</a>
</div>
@endsection

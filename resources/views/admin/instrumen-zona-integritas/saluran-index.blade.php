@extends('admin.layouts.app')
@section('title', 'ZI — Saluran')
@section('heading', 'Instrumen ZI — saluran pengaduan')
@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap gap-2 text-sm text-slate-600">
        <a href="{{ route('admin.zi.hub') }}" class="font-semibold text-primary hover:underline">Instrumen ZI</a>
        <span>/</span>
        <span class="font-medium text-slate-900">Saluran</span>
    </nav>
    <div class="flex flex-wrap justify-between gap-3">
        <p class="text-sm text-slate-600">Saluran <strong>ditayangkan</strong> tampil di halaman publik.</p>
        <a href="{{ route('admin.zi.saluran.create') }}" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white">+ Tambah</a>
    </div>
    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl ring-1 ring-white/70">
        @if($rows->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-slate-500">Belum ada saluran.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Judul (ID)</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rows as $r)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 pl-6 font-bold">{{ $r->sort_order }}</td>
                                <td class="max-w-xs px-4 py-3 font-semibold">{{ $r->title_id }}</td>
                                <td class="px-4 py-3">
                                    @include('admin.components.toggle-published', [
                                        'published' => $r->is_published,
                                        'action' => route('admin.zi.saluran.toggle-publish', $r),
                                        'scope' => 'daftar saluran pengaduan halaman Instrumen Zona Integritas',
                                    ])
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.zi.saluran.edit', $r) }}" class="mr-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary">Edit</a>
                                    <form method="post" action="{{ route('admin.zi.saluran.destroy', $r) }}" class="inline" onsubmit="return confirm('Hapus?');">@csrf @method('DELETE')
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
                    Menampilkan <strong>{{ $rows->firstItem() }}</strong>–<strong>{{ $rows->lastItem() }}</strong> dari <strong>{{ $rows->total() }}</strong> saluran
                </p>
                <div class="min-w-0 overflow-x-auto">{{ $rows->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection

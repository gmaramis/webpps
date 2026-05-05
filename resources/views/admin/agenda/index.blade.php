@extends('admin.layouts.app')

@section('title', 'Agenda')
@section('heading', 'Agenda')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Agenda</span>
    </nav>

    <form method="get" action="{{ route('admin.agenda.index') }}" class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-end">
        <div class="flex flex-wrap items-center gap-2 sm:mr-2">
            <a href="{{ route('admin.agenda.index') }}" class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold transition {{ ($status ?? 'all') === 'all' ? 'border-primary/30 bg-sky-50 text-primary' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                <span>Semua</span>
                <span class="rounded-full bg-white/80 px-1.5 py-0.5 text-[10px]">{{ $statusCounts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.agenda.index', ['status' => 'published']) }}" class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold transition {{ ($status ?? 'all') === 'published' ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                <span>Tayang</span>
                <span class="rounded-full bg-white/80 px-1.5 py-0.5 text-[10px]">{{ $statusCounts['published'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.agenda.index', ['status' => 'draft']) }}" class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold transition {{ ($status ?? 'all') === 'draft' ? 'border-amber-300 bg-amber-50 text-amber-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                <span>Draf</span>
                <span class="rounded-full bg-white/80 px-1.5 py-0.5 text-[10px]">{{ $statusCounts['draft'] ?? 0 }}</span>
            </a>
        </div>
        <div class="sm:w-56">
            <label for="agenda-filter-status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
            <select id="agenda-filter-status" name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-900 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                <option value="all" @selected(($status ?? 'all') === 'all')>Semua</option>
                <option value="published" @selected(($status ?? 'all') === 'published')>Tayang</option>
                <option value="draft" @selected(($status ?? 'all') === 'draft')>Draf</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Terapkan</button>
            @if (($status ?? 'all') !== 'all')
                <a href="{{ route('admin.agenda.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">Data ini ditampilkan di blok <strong class="text-slate-800">Agenda</strong> pada halaman beranda <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">/</code>. Urutan kecil tampil dulu.</p>
            @if($agendaItems->isEmpty())
                <p class="mt-2 text-xs font-medium text-amber-800">Belum ada data — impor dari <code class="rounded bg-amber-50 px-1 font-mono">pps-content.json</code> (key AGENDA) atau tambah manual.</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            @if($ppsJsonExists)
                <form method="post" action="{{ route('admin.agenda.import-json') }}" class="inline" onsubmit="return confirm('Ini akan menghapus semua agenda di basis data dan menggantinya dari key AGENDA di resources/data/pps-content.json. Lanjut?');">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Impor dari JSON</button>
                </form>
            @endif
            <a href="{{ route('home') }}#pengumuman-agenda" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Lihat di beranda ↗</a>
            <a href="{{ route('admin.agenda.create') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-primary to-primary-light px-4 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">+ Tambah</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($agendaItems->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">
                {{ ($status ?? 'all') === 'all' ? 'Belum ada agenda.' : 'Tidak ada agenda untuk status terpilih.' }}
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Urutan</th>
                            <th class="px-4 py-3">Tanggal ringkas</th>
                            <th class="px-4 py-3">Judul (ID)</th>
                            <th class="hidden px-4 py-3 md:table-cell">Tautan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($agendaItems as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3 pl-6 font-display-admin font-bold text-slate-900">{{ $item->sort_order }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">{{ $item->day }} {{ $item->month_id }}</span>
                                </td>
                                <td class="max-w-[22rem] px-4 py-3 font-semibold text-slate-900">{{ \Illuminate\Support\Str::limit($item->title_id, 120) }}</td>
                                <td class="hidden max-w-[14rem] px-4 py-3 text-xs text-slate-500 md:table-cell">
                                    <code class="rounded bg-slate-100 px-1 py-0.5 break-all">{{ $item->href }}</code>
                                </td>
                                <td class="px-4 py-3">
                                    @include('admin.components.toggle-published', [
                                        'published' => $item->is_published,
                                        'action' => route('admin.agenda.toggle-publish', $item),
                                        'scope' => 'blok agenda beranda',
                                    ])
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 pr-6 text-right">
                                    <a href="{{ route('admin.agenda.edit', $item) }}" class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Edit</a>
                                    <form method="post" action="{{ route('admin.agenda.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus agenda ini?');">
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

            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-600">
                    Menampilkan <strong>{{ $agendaItems->firstItem() }}</strong>–<strong>{{ $agendaItems->lastItem() }}</strong> dari <strong>{{ $agendaItems->total() }}</strong> agenda
                </p>
                <div class="min-w-0 overflow-x-auto">{{ $agendaItems->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection


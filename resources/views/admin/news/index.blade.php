@extends('admin.layouts.app')

@section('title', 'Berita')
@section('heading', 'Kelola berita')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">Saring berdasarkan status atau kata kunci judul / penulis.</p>
        <a href="{{ route('admin.news.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-dark">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Berita baru
        </a>
    </div>

    <form method="get" action="{{ route('admin.news.index') }}" class="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-end">
        <div class="min-w-0 flex-1 sm:max-w-md">
            <label for="news-search-q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Cari</label>
            <input
                id="news-search-q"
                type="search"
                name="q"
                value="{{ $search }}"
                autocomplete="off"
                placeholder="Judul (ID/EN) atau penulis…"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
        </div>
        <div class="sm:w-44">
            <label for="news-filter-status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
            <select id="news-filter-status" name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-900 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                <option value="all" @selected($status === 'all')>Semua</option>
                <option value="published" @selected($status === 'published')>Published / Terbit</option>
                <option value="draft" @selected($status === 'draft')>Draft / Draf</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Terapkan</button>
            @if ($search !== '' || $status !== 'all')
                <a href="{{ route('admin.news.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    @if ($items->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
            @if ($search !== '' || $status !== 'all')
                <p class="font-medium text-slate-800">Tidak ada berita yang cocok.</p>
                <p class="mt-1 text-sm text-slate-600">Ubah kata kunci atau status, atau <a href="{{ route('admin.news.index') }}" class="font-semibold text-primary hover:underline">reset filter</a>.</p>
            @else
                <p class="font-medium text-slate-800">Belum ada berita</p>
                <a href="{{ route('admin.news.create') }}" class="mt-3 inline-flex rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Buat berita</a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[32rem] border-collapse text-left text-sm text-slate-800">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <th class="whitespace-nowrap px-3 py-2.5" scope="col">Gambar</th>
                        <th class="px-3 py-2.5" scope="col">Judul berita</th>
                        <th class="whitespace-nowrap px-3 py-2.5" scope="col">Tgl publikasi</th>
                        <th class="whitespace-nowrap px-3 py-2.5" scope="col">Status</th>
                        <th class="whitespace-nowrap px-3 py-2.5 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($items as $row)
                        @php
                            $titleId = $row->getTranslationWithoutFallback('title', 'id');
                            $editUrl = route('admin.news.edit', $row);
                        @endphp
                        <tr class="hover:bg-slate-50/80">
                            <td class="whitespace-nowrap px-3 py-2 align-middle">
                                <div class="flex h-12 w-14 items-center justify-center overflow-hidden rounded border border-slate-100 bg-slate-50">
                                    <img src="{{ $row->newsImageUrl() }}" alt="" class="max-h-full max-w-full object-contain" width="56" height="48" loading="lazy" decoding="async">
                                </div>
                            </td>
                            <td class="max-w-[14rem] px-3 py-2 align-middle sm:max-w-xs md:max-w-md lg:max-w-lg xl:max-w-2xl">
                                <a href="{{ $editUrl }}" class="font-medium text-primary hover:underline">{{ $titleId !== '' ? $titleId : '(Tanpa judul)' }}</a>
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 align-middle text-slate-600">
                                @if ($row->published_at)
                                    {{ $row->published_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 align-middle">
                                @if ($row->is_published)
                                    <span class="inline-flex rounded px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-900">Published</span>
                                @else
                                    <span class="inline-flex rounded px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-950">Draft</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 align-middle text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ $editUrl }}" class="rounded border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-primary hover:bg-slate-50">Edit</a>
                                    <form method="post" action="{{ route('admin.news.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus berita ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-600">
                Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong> dari <strong>{{ $items->total() }}</strong> berita
            </p>
            <div class="min-w-0 overflow-x-auto">{{ $items->links() }}</div>
        </div>
    @endif
</div>
@endsection

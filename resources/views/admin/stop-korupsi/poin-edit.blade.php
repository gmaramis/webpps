@extends('admin.layouts.app')

@section('title', 'Edit poin Stop Korupsi')
@section('heading', 'Stop Korupsi — edit poin')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap gap-2 text-sm text-slate-600">
        <a href="{{ route('admin.stop-korupsi.hub') }}" class="font-semibold text-primary hover:underline">Stop Korupsi</a>
        <span>/</span>
        <a href="{{ route('admin.stop-korupsi.poin.index') }}" class="font-semibold text-primary hover:underline">Poin</a>
        <span>/</span>
        <span class="font-medium text-slate-900">Edit</span>
    </nav>

    <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-lg ring-1 ring-white/70 md:p-5">
        <form method="post" action="{{ route('admin.stop-korupsi.poin.update', $bullet) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.components.form-page-hidden')
            @php $ta = 'w-full min-h-[3.25rem] max-h-40 resize-y rounded-lg border border-slate-200 bg-slate-50/80 px-2.5 py-1.5 text-sm leading-snug focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/15'; @endphp
            <div>
                <label for="sort_order" class="mb-0.5 block text-[11px] font-semibold text-slate-700">Urutan</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $bullet->sort_order) }}" min="0" class="w-28 rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm @error('sort_order') border-rose-400 @enderror">
                @error('sort_order')<p class="mt-0.5 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-3 md:grid-cols-2">
                <div>
                    <label for="text_id" class="mb-0.5 block text-[11px] font-semibold text-slate-700">Teks Indonesia <span class="text-rose-600">*</span></label>
                    <textarea id="text_id" name="text_id" rows="3" required class="{{ $ta }} @error('text_id') border-rose-400 @enderror">{{ old('text_id', $bullet->text_id) }}</textarea>
                    @error('text_id')<p class="mt-0.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="text_en" class="mb-0.5 block text-[11px] font-semibold text-slate-700">Teks English <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="text_en" name="text_en" rows="3" class="{{ $ta }} @error('text_en') border-rose-400 @enderror">{{ old('text_en', $bullet->text_en) }}</textarea>
                    @error('text_en')<p class="mt-0.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-5 py-2 text-sm font-bold text-white shadow-md shadow-primary/25">Simpan</button>
                <a href="{{ route('admin.stop-korupsi.poin.index') }}" class="rounded-full border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Edit berita')
@section('heading', 'Edit berita')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.news.index') }}" class="font-semibold text-primary hover:underline">Berita</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Edit</span>
    </nav>
    <div id="puter-translate-banner" class="hidden rounded-xl border px-4 py-3 text-sm shadow-sm" role="status"></div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
        <form method="post" action="{{ route('admin.news.update', $item) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.news._form', ['item' => $item])
        </form>
    </div>
</div>

@if (session('run_puter_translate'))
    @push('scripts')
        @include('admin.news._puter_translate', ['item' => $item])
    @endpush
@endif
@endsection

@extends('admin.layouts.app')

@section('title', 'Berita baru')
@section('heading', 'Berita baru')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.news.index') }}" class="font-semibold text-primary hover:underline">Berita</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Berita baru</span>
    </nav>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
        <form method="post" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.news._form', ['item' => $item])
        </form>
    </div>
</div>
@endsection

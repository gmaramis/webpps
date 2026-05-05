@extends('admin.layouts.app')
@section('title', 'ZI — Edit pembaruan')
@section('heading', 'Edit pembaruan')
@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap gap-2 text-sm text-slate-600">
        <a href="{{ route('admin.zi.hub') }}" class="font-semibold text-primary hover:underline">Instrumen ZI</a>
        <span>/</span>
        <a href="{{ route('admin.zi.pembaruan.index') }}" class="font-semibold text-primary hover:underline">Pembaruan</a>
        <span>/</span>
        <span class="font-medium text-slate-900">Edit</span>
    </nav>
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 md:p-8">
        <form method="post" action="{{ route('admin.zi.pembaruan.update', $item) }}" class="space-y-8">@csrf @method('PUT')
            @include('admin.instrumen-zona-integritas._pembaruan-form', ['item' => $item])
            <div class="flex gap-3 border-t pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white">Perbarui</button>
                <a href="{{ route('admin.zi.pembaruan.index') }}" class="rounded-full border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Edit program S2')
@section('heading', 'Edit program S2')

@section('content')
<div class="w-full min-w-0 space-y-6">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.prodi-s2.index') }}" class="font-semibold text-primary hover:underline">Program S2</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Edit</span>
    </nav>

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <form method="post" action="{{ route('admin.prodi-s2.update', $program) }}" class="space-y-8">
            @csrf
            @method('PUT')
            @include('admin.s2-programs._form', ['program' => $program])
            <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-full bg-gradient-to-r from-primary to-primary-light px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110">Simpan perubahan</button>
                <a href="{{ route('admin.prodi-s2.index') }}" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

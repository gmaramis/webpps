@extends('admin.layouts.app')

@section('title', 'Profil')
@section('heading', 'Profil akun')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Profil</span>
    </nav>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
        <p class="mb-4 text-sm text-slate-600">Perbarui nama tampilan dan alamat email untuk akun admin Anda.</p>
        <form method="post" action="{{ route('admin.profile.update') }}" class="max-w-md space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="name" class="mb-1 block text-xs font-semibold text-slate-700">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="mb-1 block text-xs font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror">
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-wrap gap-3 pt-1">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-md shadow-primary/20 transition hover:bg-primary-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    Simpan profil
                </button>
                <a href="{{ route('admin.profile.password.edit') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                    Ubah kata sandi
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

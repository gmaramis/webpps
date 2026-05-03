@extends('admin.layouts.app')

@section('title', 'Ubah kata sandi')
@section('heading', 'Ubah kata sandi')

@section('content')
<div class="w-full min-w-0 space-y-4">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.profile.edit') }}" class="font-semibold text-primary hover:underline">Profil</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Ubah kata sandi</span>
    </nav>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
        <p class="mb-4 text-sm text-slate-600">Masukkan kata sandi saat ini lalu tentukan kata sandi baru.</p>
        <form method="post" action="{{ route('admin.profile.password.update') }}" class="max-w-md space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="current_password" class="mb-1 block text-xs font-semibold text-slate-700">Kata sandi saat ini</label>
                <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('current_password') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror">
                @error('current_password')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="mb-1 block text-xs font-semibold text-slate-700">Kata sandi baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('password') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror">
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-xs font-semibold text-slate-700">Konfirmasi kata sandi baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="flex flex-wrap gap-3 pt-1">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-md shadow-primary/20 transition hover:bg-primary-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    Simpan kata sandi
                </button>
                <a href="{{ route('admin.profile.edit') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                    Kembali ke profil
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

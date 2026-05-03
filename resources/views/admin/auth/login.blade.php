@extends('admin.layouts.guest')

@section('title', 'Masuk')

@section('content')
{{-- Lebar tetap sempit: jangan pakai w-full di dalam flex kolom (akan meregang selebar layar). --}}
<div class="mx-auto w-[min(20.5rem,calc(100vw-2rem))] shrink-0 sm:w-[22rem]">
    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-[0_24px_48px_-12px_rgb(15_23_42/0.12)] ring-1 ring-slate-900/[0.04]">
        <div class="border-b border-slate-100 bg-gradient-to-r from-primary/[0.06] via-transparent to-sky-500/[0.06] px-5 pb-4 pt-5 text-center sm:px-6 sm:pb-5 sm:pt-6">
            <img src="{{ asset('logo-unima.png') }}" alt="Logo UNIMA" width="56" height="56" class="mx-auto h-14 w-14 rounded-xl border border-slate-100 bg-white object-contain p-1 shadow-sm">
            <h1 class="mt-4 font-display text-lg font-bold leading-snug tracking-tight text-primary sm:text-xl">
                Admin Web Pascasarjana
            </h1>
            <p class="mt-1 text-xs font-medium text-slate-500">Universitas Negeri Manado</p>
        </div>

        <div class="px-5 pb-5 pt-4 sm:px-6 sm:pb-6 sm:pt-5">
            <p class="mb-3 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-400">Masuk ke panel</p>

            <form method="post" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-xs font-semibold text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror"
                        placeholder="nama@unima.ac.id">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="mb-1 block text-xs font-semibold text-slate-700">Kata sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-900 transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between gap-3 pt-0.5">
                    <label class="flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-primary focus:ring-primary/30">
                        Ingat saya
                    </label>
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-primary hover:text-primary-dark hover:underline">Situs publik</a>
                </div>
                <button type="submit" class="mt-1 w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-white shadow-md shadow-primary/20 transition hover:bg-primary-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <p class="mt-8 text-center text-[11px] text-slate-500">© {{ date('Y') }} Sekolah Pascasarjana UNIMA</p>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Tambah user')
@section('heading', 'Tambah user')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.users.index') }}" class="font-semibold text-primary hover:underline">Kelola user</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Tambah user</span>
    </nav>

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        <form method="post" action="{{ route('admin.users.store') }}" class="max-w-md space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</label>
                <input id="name" type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                <input id="email" type="email" name="email" required value="{{ old('email') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="role" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Role</label>
                <select id="role" name="role" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', \App\Support\AdminRoles::ADMIN) === $role->name)>{{ \App\Support\AdminRoles::label($role->name) }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kata sandi</label>
                <input id="password" type="password" name="password" required minlength="8" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Konfirmasi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

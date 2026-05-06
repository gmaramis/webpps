@extends('admin.layouts.app')

@section('title', 'Kelola user')
@section('heading', 'Kelola user')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Kelola user</span>
    </nav>

    <div class="flex items-center justify-end">
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-dark">
            Tambah user
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pengaturan menu per role</p>
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($roles as $role)
                <a href="{{ route('admin.users.role.permissions.edit', $role) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-semibold text-slate-700 transition hover:border-primary hover:text-primary" title="{{ $role->name }}">
                    <span>{{ \App\Support\AdminRoles::label($role->name) }}</span>
                    <span class="font-mono font-normal text-[10px] text-slate-400">{{ $role->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm">
        @if($users->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Belum ada user.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/90 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-4 py-3 pl-6">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-4 py-3 pl-6 font-semibold text-slate-900">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <form method="post" action="{{ route('admin.users.role.update', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" title="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ \App\Support\AdminRoles::label($role->name) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 transition hover:bg-slate-200">Simpan role</button>
                                    </form>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.users.password.edit', $user) }}" class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-primary transition hover:bg-sky-100">Ubah kata sandi</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

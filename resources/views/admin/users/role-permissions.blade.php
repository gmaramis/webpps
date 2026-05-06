@extends('admin.layouts.app')

@section('title', 'Permission role — '.$roleDisplayName)
@section('heading', 'Permission untuk '.$roleDisplayName)

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.users.index') }}" class="font-semibold text-primary hover:underline">Kelola user</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">{{ $roleDisplayName }}</span>
    </nav>

    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-xl shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm md:p-8">
        @if($role->name === \App\Support\AdminRoles::SUPER_ADMIN)
            <p class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                Role <span class="font-semibold">{{ $roleDisplayName }}</span> (<code class="rounded bg-emerald-100 px-1 text-[11px]">{{ $role->name }}</code>) selalu punya akses penuh.
            </p>
        @endif

        <form method="post" action="{{ route('admin.users.role.permissions.update', $role) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                @foreach($permissionGroups as $groupName => $groupPermissions)
                    <div class="rounded-2xl border border-slate-200/90 bg-slate-50/70 p-3.5">
                        <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">{{ $groupName }}</p>
                        <div class="grid gap-2 md:grid-cols-2">
                            @foreach($groupPermissions as $permissionName)
                                <label class="flex items-start gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm">
                                    <input type="checkbox" name="permissions[]" value="{{ $permissionName }}" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                                        @checked(in_array($permissionName, old('permissions', $selectedPermissions), true))
                                        @disabled($role->name === \App\Support\AdminRoles::SUPER_ADMIN)>
                                    <span>
                                        <span class="block font-medium text-slate-700">{{ $permissionLabels[$permissionName] ?? $permissionName }}</span>
                                        <span class="block text-[11px] text-slate-400">{{ $permissionName }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark" @disabled($role->name === \App\Support\AdminRoles::SUPER_ADMIN)>
                    Simpan permission
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

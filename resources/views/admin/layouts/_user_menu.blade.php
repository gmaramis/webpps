@php
    $u = Auth::user();
    $initial = mb_strtoupper(mb_substr(trim($u->name !== '' ? $u->name : $u->email), 0, 1));
    $canManageUsers = $u->can('users.manage');
@endphp
<div class="relative z-[100] shrink-0">
    <details class="group relative">
        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border border-slate-200/80 bg-white/90 py-1.5 pl-2.5 pr-2 shadow-md shadow-slate-900/[0.04] backdrop-blur-sm transition hover:border-slate-300 hover:bg-white hover:shadow-md [&::-webkit-details-marker]:hidden" aria-label="Menu akun: {{ $u->name }}">
            <span class="hidden text-[13px] text-slate-500 sm:inline">Hai,</span>
            <span class="hidden max-w-[10rem] truncate text-[13px] font-semibold tracking-tight text-slate-900 sm:inline" title="{{ $u->email }}">{{ $u->name }}</span>
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-[13px] font-bold text-white shadow-inner shadow-teal-900/25" aria-hidden="true">{{ $initial }}</span>
            <svg class="hidden h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200 group-open:rotate-180 md:inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
        </summary>
        <div class="absolute right-0 z-[200] mt-2 min-w-[13rem] overflow-hidden rounded-2xl border border-slate-200/80 bg-white/95 py-1 shadow-xl shadow-slate-900/10 backdrop-blur-md ring-1 ring-white/70">
            <p class="border-b border-slate-100/90 px-4 py-2.5 text-xs text-slate-500">{{ $u->email }}</p>
            <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2.5 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50 {{ request()->routeIs('admin.profile.edit') ? 'bg-sky-50/90 font-semibold text-primary' : '' }}">Profil</a>
            <a href="{{ route('admin.profile.password.edit') }}" class="block px-4 py-2.5 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50 {{ request()->routeIs('admin.profile.password.edit') ? 'bg-sky-50/90 font-semibold text-primary' : '' }}">Ubah kata sandi</a>
            @if($canManageUsers)
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50 {{ request()->routeIs('admin.users.*') ? 'bg-sky-50/90 font-semibold text-primary' : '' }}">Kelola user</a>
            @endif
            <div class="my-1 border-t border-slate-100"></div>
            <form method="post" action="{{ route('admin.logout') }}" class="px-1.5 pb-1.5">
                @csrf
                <button type="submit" class="w-full rounded-xl px-3 py-2 text-left text-[13px] font-semibold text-rose-700 transition hover:bg-rose-50">
                    Keluar
                </button>
            </form>
        </div>
    </details>
</div>

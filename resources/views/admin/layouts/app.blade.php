<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') — Admin PPS</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-unima.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-unima.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[var(--color-dash-canvas)] font-admin text-slate-800 antialiased [font-feature-settings:'liga'_1,'cv02'_1,'cv03'_1,'cv04'_1]">
    <div class="flex min-h-screen">
        <aside class="relative flex w-[17rem] shrink-0 flex-col border-r border-white/60 bg-white/90 shadow-[4px_0_24px_-4px_rgb(15_23_42/0.06)] backdrop-blur-xl md:w-[18rem]">
            <span class="pointer-events-none absolute inset-y-0 left-0 w-px bg-gradient-to-b from-sky-400/80 via-primary to-teal-500/60" aria-hidden="true"></span>
            <div class="relative z-10 px-3 pt-4">
                <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-white px-3.5 py-3.5 ring-1 ring-slate-200/80 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('logo-unima.png') }}" alt="Logo UNIMA" class="h-10 w-10 shrink-0 object-contain">
                        <div class="min-w-0">
                            <a href="{{ route('admin.dashboard') }}" class="block font-display-admin text-lg font-bold tracking-tight text-primary transition hover:text-primary-light">Admin PPS</a>
                            <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">Pascasarjana UNIMA</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative z-10 min-h-0 flex-1 pb-2 pt-1">
                @include('admin.layouts._admin_sidebar_nav')
            </div>
            <div class="relative z-10 border-t border-slate-100/90 p-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100/80 hover:text-primary">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </span>
                    <span>Situs publik</span>
                </a>
            </div>
        </aside>
        <div class="relative flex min-w-0 flex-1 flex-col bg-gradient-to-br from-[var(--color-dash-canvas)] via-white/40 to-sky-50/30">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_90%_48%_at_50%_-8%,var(--color-dash-mesh),transparent_55%)]" aria-hidden="true"></div>
            <header class="relative z-40 flex items-center justify-between gap-4 border-b border-slate-200/50 bg-white/70 px-4 py-3.5 shadow-sm shadow-slate-900/[0.03] backdrop-blur-md md:px-7">
                <h2 class="truncate font-display-admin text-lg font-bold tracking-tight text-slate-900 md:text-xl">@yield('heading', 'Dasbor')</h2>
                <div class="flex shrink-0 items-center gap-2 md:gap-2.5">
                    <a href="{{ route('admin.visi-misi.edit') }}" class="hidden items-center gap-1.5 rounded-full border border-sky-200/80 bg-sky-50/90 px-3.5 py-2 text-xs font-semibold text-primary shadow-sm transition hover:border-sky-300 hover:bg-white md:inline-flex">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Visi &amp; Misi
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="hidden items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 md:inline-flex">
                        <svg class="h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-11.25h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5"/></svg>
                        Berita
                    </a>
                    @include('admin.layouts._notifications_bell')
                    @include('admin.layouts._user_menu')
                </div>
            </header>
            <main class="relative z-10 flex min-h-0 min-w-0 flex-1 flex-col p-4 md:p-7 lg:p-10">
                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200/90 bg-gradient-to-br from-rose-50 to-white px-4 py-3.5 text-sm font-medium text-rose-950 shadow-md shadow-rose-900/[0.04]" role="alert">
                        @foreach ($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                @endif
                @if(session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-emerald-50 to-white px-4 py-3.5 text-sm font-semibold text-emerald-950 shadow-md shadow-emerald-900/[0.04]">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

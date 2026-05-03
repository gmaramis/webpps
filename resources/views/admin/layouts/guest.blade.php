<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') — Admin PPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="relative min-h-screen overflow-x-hidden font-sans text-slate-900 antialiased">
    {{-- Latar: gradien lembut + sorot halus (tanpa gambar) --}}
    <div class="pointer-events-none fixed inset-0 -z-10 bg-gradient-to-br from-slate-100 via-white to-sky-50" aria-hidden="true"></div>
    <div class="pointer-events-none fixed -left-32 top-1/4 -z-10 h-72 w-72 rounded-full bg-primary/[0.06] blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none fixed -right-24 bottom-0 -z-10 h-80 w-80 rounded-full bg-sky-400/[0.12] blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-px bg-gradient-to-r from-transparent via-primary/25 to-transparent" aria-hidden="true"></div>

    <div class="relative z-0 flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:py-16">
        @yield('content')
    </div>
</body>
</html>

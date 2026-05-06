<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $t['brandTitle'] ?? 'Pascasarjana UNIMA')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-unima.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-unima.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen font-sans text-slate-900 antialiased {{ ($isHome ?? false) ? '' : 'bg-slate-50' }}">
    <a class="skip-link" href="#main">{{ $t['skip'] ?? 'Lewati ke konten' }}</a>
    @include('partials.header')
    @yield('content')
    @include('partials.footer')
    @stack('scripts')
</body>
</html>

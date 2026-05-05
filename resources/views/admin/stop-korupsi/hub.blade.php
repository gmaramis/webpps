@extends('admin.layouts.app')

@section('title', 'Stop Korupsi')
@section('heading', 'Stop Korupsi')

@section('content')
<div class="w-full min-w-0 space-y-5">
    <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-600" aria-label="Jejak navigasi">
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-primary hover:underline">Dasbor</a>
        <span aria-hidden="true">/</span>
        <span class="font-medium text-slate-900">Stop Korupsi</span>
    </nav>

    @if(! $ready)
        <div class="rounded-2xl border border-amber-200 bg-amber-50/90 px-4 py-3 text-sm text-amber-950">Jalankan migrasi: <code class="rounded bg-white px-1 font-mono text-xs">php artisan migrate</code></div>
    @else
        <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white/90 px-5 py-4 shadow-lg shadow-slate-900/[0.04] ring-1 ring-white/70 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-600">Kelola konten halaman publik <code class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-mono">/stop-korupsi</code> — struktur sama dengan tampilan depan: judul &amp; lead, dua paragraf, judul kotak poin, daftar poin, blok CTA dengan dua tautan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($ppsJsonExists)
                    <form method="post" action="{{ route('admin.stop-korupsi.import-json') }}" class="inline" onsubmit="return confirm('Ini akan mengisi ulang teks halaman dan daftar poin dari pps-content.json (STRINGS + STOP_KORUPSI_BULLETS). Data poin di basis data diganti seluruhnya. Lanjut?');">
                        @csrf
                        <button type="submit" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-white">Impor dari JSON</button>
                    </form>
                @endif
                <a href="{{ route('stop-korupsi') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-slate-200/90 bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm transition hover:shadow-md">Halaman publik ↗</a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('admin.stop-korupsi.konten.edit') }}" class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-md ring-1 ring-white/70 transition hover:border-primary/30 hover:shadow-lg">
                <p class="text-xs font-bold uppercase tracking-widest text-primary">Teks halaman</p>
                <p class="mt-2 font-display-admin text-lg font-bold text-slate-900">Judul, paragraf, tombol</p>
                <p class="mt-1 text-sm text-slate-600">Form pendek bahasa Indonesia; Inggris ada di bagian terlipat.</p>
            </a>
            <a href="{{ route('admin.stop-korupsi.poin.index') }}" class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-md ring-1 ring-white/70 transition hover:border-primary/30 hover:shadow-lg">
                <p class="text-xs font-bold uppercase tracking-widest text-primary">Daftar poin</p>
                <p class="mt-2 font-display-admin text-lg font-bold text-slate-900">{{ $counts['poin'] }} poin</p>
                <p class="mt-1 text-sm text-slate-600">Bullet di kotak merah muda — urutan &amp; teks ID/EN</p>
            </a>
        </div>
    @endif
</div>
@endsection

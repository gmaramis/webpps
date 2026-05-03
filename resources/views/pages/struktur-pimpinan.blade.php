@extends('layouts.app')

@section('title', ($t['leadersPageTitle'] ?? 'Pimpinan').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $leaders = $ppsData['LEADERS'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['leadersPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['leadersPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['leadersPageLead'] }}</p>
        </header>

        <section class="mb-12">
            <h2 class="mb-6 font-display text-lg font-bold text-primary">{{ $t['leadersDirectorTitle'] }} &amp; {{ $t['leadersViceTitle'] }}</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($leaders as $person)
                    @php
                        $role = $person['role'][$loc] ?? $person['role']['id'] ?? '';
                        $src = \App\Models\LeadershipPerson::publicPhotoUrl($person['photo'] ?? null);
                    @endphp
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="aspect-[4/3] bg-slate-100">
                            <img src="{{ $src }}" alt="" class="h-full w-full object-cover" width="400" height="300" loading="lazy" decoding="async">
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-primary">{{ $role }}</p>
                            <p class="mt-1 font-display text-base font-bold text-slate-900">{{ $person['name'] ?? '' }}</p>
                            <p class="mt-2 text-xs text-slate-500"><span class="font-medium text-slate-600">{{ $t['leadersNipLabel'] }}</span> {{ $person['nip'] ?? '' }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-sky-100 bg-white p-6 shadow-sm md:p-8">
            <h2 class="font-display text-lg font-bold text-primary">{{ $t['leadersStructureTitle'] }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $t['leadersOrgTopSub'] }}</p>
            <ol class="mt-6 space-y-3 border-l-2 border-primary/30 pl-5 text-sm text-slate-800">
                <li><span class="font-semibold text-primary">{{ $t['leadersFlow1'] }}</span></li>
                <li><span class="font-semibold text-primary">{{ $t['leadersFlow2'] }}</span></li>
                <li><span class="font-semibold text-primary">{{ $t['leadersFlow3'] }}</span></li>
                <li><span class="font-semibold text-primary">{{ $t['leadersFlow4'] }}</span></li>
            </ol>
        </section>
        <p class="mt-8 text-xs text-slate-500">{{ $t['leadersUpdatedLabel'] }}</p>
    </div>
</main>
@endsection

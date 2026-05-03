@extends('layouts.app')

@section('title', ($t['vmPageTitle'] ?? 'Visi & Misi').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['vmPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['vmPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['vmPageLead'] }}</p>
        </header>

        <div class="grid gap-8 lg:grid-cols-3">
            <section class="rounded-2xl border border-sky-100 bg-white p-6 shadow-sm md:p-8">
                <h2 class="font-display text-xl font-bold text-primary">{{ $t['vmVisionTitle'] }}</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-700 md:text-base">{{ $blocks['vision'] }}</p>
            </section>
            <section class="rounded-2xl border border-sky-100 bg-white p-6 shadow-sm md:p-8 lg:col-span-2">
                <h2 class="font-display text-xl font-bold text-primary">{{ $t['vmMissionTitle'] }}</h2>
                <ul class="mt-4 list-disc space-y-2 pl-5 text-sm leading-relaxed text-slate-700 md:text-base">
                    @foreach($blocks['mission'] as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            </section>
            <section class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/40 to-white p-6 shadow-sm md:p-8 lg:col-span-3">
                <h2 class="font-display text-xl font-bold text-primary">{{ $t['vmValuesTitle'] }}</h2>
                <ul class="mt-4 grid gap-3 md:grid-cols-3">
                    @foreach($blocks['values'] as $line)
                        <li class="rounded-xl border border-emerald-100/80 bg-white/80 px-4 py-3 text-sm text-slate-700">{{ $line }}</li>
                    @endforeach
                </ul>
            </section>
        </div>
        <p class="mt-8 text-xs text-slate-500">{{ $t['vmUpdatedLabel'] }}</p>
    </div>
</main>
@endsection

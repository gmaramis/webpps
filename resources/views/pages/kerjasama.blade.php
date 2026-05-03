@extends('layouts.app')

@section('title', ($t['partnershipsTitle'] ?? 'Kerjasama').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $partners = $ppsData['PARTNERS'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['partnershipsEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['partnershipsTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['partnershipsLead'] }}</p>
        </header>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="w-24 px-4 py-3 md:w-32">{{ $t['partnershipsColLogo'] }}</th>
                        <th class="px-4 py-3">{{ $t['partnershipsColName'] }}</th>
                        <th class="hidden px-4 py-3 md:table-cell">{{ $t['partnershipsColType'] }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($partners as $p)
                        @php
                            $name = $p['name'][$loc] ?? $p['name']['id'] ?? '';
                            $coop = $p['cooperation'][$loc] ?? $p['cooperation']['id'] ?? '';
                            $logo = \App\Models\CooperationPartner::publicLogoUrl($p['logo'] ?? '');
                        @endphp
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 align-middle">
                                <img src="{{ $logo }}" alt="" class="h-12 w-12 object-contain" width="48" height="48" loading="lazy">
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-medium text-slate-900">{{ $name }}</div>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600 md:hidden">{{ $coop }}</p>
                            </td>
                            <td class="hidden px-4 py-3 align-top text-slate-600 md:table-cell">{{ $coop }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

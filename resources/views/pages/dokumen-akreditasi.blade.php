@extends('layouts.app')

@section('title', ($t['accreditationPageTitle'] ?? 'Akreditasi').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $docs = $ppsData['ACCREDITATION_DOCUMENTS'] ?? [];
@endphp

@section('content')
<main id="main" class="pb-16 pt-6 md:pb-20 md:pt-8">
    <div class="mx-auto max-w-6xl px-4">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $t['accreditationPageEyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-primary md:text-4xl">{{ $t['accreditationPageTitle'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 md:text-lg">{{ $t['accreditationPageLead'] }}</p>
        </header>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">{{ $t['accreditationFileColName'] }}</th>
                        <th class="w-40 px-4 py-3 text-right">{{ $t['accreditationFileColAction'] }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($docs as $d)
                        @php
                            $name = $d['name'][$loc] ?? $d['name']['id'] ?? '';
                            $href = asset(ltrim($d['file'] ?? '', '/'));
                        @endphp
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $name }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ $href }}" download class="inline-flex rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-primary-dark">{{ $t['accreditationDownloadBtn'] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

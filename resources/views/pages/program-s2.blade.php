@extends('layouts.app')

@section('title', ($t['magisterTitle'] ?? 'Program S2').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $programs = $programs ?? [];
    $selectedSlug = $selectedSlug ?? '';
    $invalidProgramSelection = $invalidProgramSelection ?? false;
    $hero = $ppsData['MAGISTER_HERO'] ?? 'programs/magister-photo.png';
    $heroSrc = \App\Models\HomepageProgramDisplay::publicHeroUrl($hero, 'programs/magister-photo.png');
    $programPagePath = parse_url(route('program.s2'), PHP_URL_PATH) ?: '/s2';
@endphp

@section('content')
    @include('partials.program-study-page', [
        'level' => 's2',
        'programs' => $programs,
        'selectedSlug' => $selectedSlug,
        'invalidProgramSelection' => $invalidProgramSelection,
        'heroSrc' => $heroSrc,
        'programPagePath' => $programPagePath,
        'tabPrefix' => 's2',
        'programsHeading' => $loc === 'id' ? 'Program Magister (S2)' : "Master's programmes (S2)",
        'programsHint' => $loc === 'id' ? 'Pilih program di daftar (atau menu di ponsel) untuk membaca deskripsi lengkap, tautan resmi, dan brosur.' : 'Choose a programme from the list (or menu on mobile) for the full description, official link, and brochure.',
        'tablistAriaLabel' => $loc === 'id' ? 'Daftar program Magister (S2)' : "Master's programmes (S2)",
        'invalidUrlMessage' => $loc === 'id' ? 'Program pada URL tidak ditemukan; menampilkan program pertama.' : 'The programme in the URL was not found; showing the first programme.',
        'emptyMessage' => $loc === 'id' ? 'Belum ada program S2 yang dipublikasikan.' : 'No master’s (S2) programmes are published yet.',
    ])
@endsection

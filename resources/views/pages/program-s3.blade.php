@extends('layouts.app')

@section('title', ($t['doktorTitle'] ?? 'Program S3').' — '.($t['brandTitle'] ?? 'PPS UNIMA'))

@php
    $loc = app()->getLocale();
    $programs = $programs ?? [];
    $selectedSlug = $selectedSlug ?? '';
    $invalidProgramSelection = $invalidProgramSelection ?? false;
    $hero = $ppsData['DOKTOR_HERO'] ?? 'programs/doktor-photo.png';
    $heroSrc = \App\Models\HomepageProgramDisplay::publicHeroUrl($hero, 'programs/doktor-photo.png');
    $programPagePath = parse_url(route('program.s3'), PHP_URL_PATH) ?: '/s3';
@endphp

@section('content')
    @include('partials.program-study-page', [
        'level' => 's3',
        'programs' => $programs,
        'selectedSlug' => $selectedSlug,
        'invalidProgramSelection' => $invalidProgramSelection,
        'heroSrc' => $heroSrc,
        'programPagePath' => $programPagePath,
        'tabPrefix' => 's3',
        'programsHeading' => $loc === 'id' ? 'Program Doktor (S3)' : 'Doctoral programmes (S3)',
        'programsHint' => $loc === 'id' ? 'Pilih program di daftar (atau menu di ponsel) untuk membaca deskripsi lengkap, tautan resmi, dan brosur.' : 'Choose a programme from the list (or menu on mobile) for the full description, official link, and brochure.',
        'tablistAriaLabel' => $loc === 'id' ? 'Daftar program Doktor (S3)' : 'Doctoral programmes (S3)',
        'invalidUrlMessage' => $loc === 'id' ? 'Program pada URL tidak ditemukan; menampilkan program pertama.' : 'The programme in the URL was not found; showing the first programme.',
        'emptyMessage' => $loc === 'id' ? 'Belum ada program S3 yang dipublikasikan.' : 'No doctoral (S3) programmes are published yet.',
    ])
@endsection

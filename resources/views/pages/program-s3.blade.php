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
        'programsHeading' => $loc === 'zh' ? '博士课程 (S3)' : ($loc === 'en' ? 'Doctoral programmes (S3)' : 'Program Doktor (S3)'),
        'programsHint' => $loc === 'zh' ? '从列表（或手机菜单）中选择一个专业，以阅读完整介绍、官方网站链接及招生简章。' : ($loc === 'en' ? 'Choose a programme from the list (or menu on mobile) for the full description, official link, and brochure.' : 'Pilih program di daftar (atau menu di ponsel) untuk membaca deskripsi lengkap, tautan resmi, dan brosur.'),
        'tablistAriaLabel' => $loc === 'zh' ? '博士专业列表 (S3)' : ($loc === 'en' ? 'Doctoral programmes (S3)' : 'Daftar program Doktor (S3)'),
        'invalidUrlMessage' => $loc === 'zh' ? '网址中的专业未找到；正在显示第一个专业。' : ($loc === 'en' ? 'The programme in the URL was not found; showing the first programme.' : 'Program pada URL tidak ditemukan; menampilkan program pertama.'),
        'emptyMessage' => $loc === 'zh' ? '尚未发布任何博士 (S3) 专业。' : ($loc === 'en' ? 'No doctoral (S3) programmes are published yet.' : 'Belum ada program S3 yang dipublikasikan.'),
    ])
@endsection

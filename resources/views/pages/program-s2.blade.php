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
        'programsHeading' => $loc === 'zh' ? '硕士课程 (S2)' : ($loc === 'en' ? "Master's programmes (S2)" : 'Program Magister (S2)'),
        'programsHint' => $loc === 'zh' ? '从列表（或手机菜单）中选择一个专业，以阅读完整介绍、官方网站链接及招生简章。' : ($loc === 'en' ? 'Choose a programme from the list (or menu on mobile) for the full description, official link, and brochure.' : 'Pilih program di daftar (atau menu di ponsel) untuk membaca deskripsi lengkap, tautan resmi, dan brosur.'),
        'tablistAriaLabel' => $loc === 'zh' ? '硕士专业列表 (S2)' : ($loc === 'en' ? "Master's programmes (S2)" : 'Daftar program Magister (S2)'),
        'invalidUrlMessage' => $loc === 'zh' ? '网址中的专业未找到；正在显示第一个专业。' : ($loc === 'en' ? 'The programme in the URL was not found; showing the first programme.' : 'Program pada URL tidak ditemukan; menampilkan program pertama.'),
        'emptyMessage' => $loc === 'zh' ? '尚未发布任何硕士 (S2) 专业。' : ($loc === 'en' ? 'No master’s (S2) programmes are published yet.' : 'Belum ada program S2 yang dipublikasikan.'),
    ])
@endsection

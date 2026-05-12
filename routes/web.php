<?php

use App\Http\Controllers\Admin\AcademicGuideController;
use App\Http\Controllers\Admin\AcademicPortalSettingController;
use App\Http\Controllers\Admin\AccreditationDocumentController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AgendaItemController;
use App\Http\Controllers\Admin\AlumniActivityController;
use App\Http\Controllers\Admin\AnnouncementItemController;
use App\Http\Controllers\Admin\CooperationPartnerController;
use App\Http\Controllers\Admin\DirectorGreetingController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\LeadershipPersonController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\NewsItemController;
use App\Http\Controllers\Admin\ProgramHeroSettingsController;
use App\Http\Controllers\Admin\S2ProgramController;
use App\Http\Controllers\Admin\S3ProgramController;
use App\Http\Controllers\Admin\StopGratifikasiBulletController;
use App\Http\Controllers\Admin\StopGratifikasiHubController;
use App\Http\Controllers\Admin\StopGratifikasiImportController;
use App\Http\Controllers\Admin\StopGratifikasiPageContentController;
use App\Http\Controllers\Admin\StopKorupsiBulletController;
use App\Http\Controllers\Admin\StopKorupsiHubController;
use App\Http\Controllers\Admin\StopKorupsiImportController;
use App\Http\Controllers\Admin\StopKorupsiPageContentController;
use App\Http\Controllers\Admin\StudentActivityController;
use App\Http\Controllers\Admin\VisionMissionController;
use App\Http\Controllers\Admin\ZiComplaintChannelController;
use App\Http\Controllers\Admin\ZiGalleryItemController;
use App\Http\Controllers\Admin\ZiHubController;
use App\Http\Controllers\Admin\ZiImportController;
use App\Http\Controllers\Admin\ZiPageIntroController;
use App\Http\Controllers\Admin\ZiPillarController;
use App\Http\Controllers\Admin\ZiUpdateItemController;
use App\Http\Controllers\PpsPageController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:8,1')
        ->name('login.attempt');
});

Route::redirect('/admin/login', '/login')->name('admin.login.redirect');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
    Route::post('notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('notifications/{notification}', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AdminProfileController::class, 'edit'])->middleware('permission:profile.manage')->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->middleware('permission:profile.manage')->name('profile.update');
    Route::get('profile/password', [AdminProfileController::class, 'editPassword'])->middleware('permission:profile.manage')->name('profile.password.edit');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->middleware('permission:profile.manage')->name('profile.password.update');
    Route::middleware('permission:users.manage')->group(function (): void {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role.update');
        Route::get('roles/{role}/permissions', [AdminUserController::class, 'editRolePermissions'])->name('users.role.permissions.edit');
        Route::patch('roles/{role}/permissions', [AdminUserController::class, 'updateRolePermissions'])->name('users.role.permissions.update');
        Route::get('users/{user}/password', [AdminUserController::class, 'editPassword'])->name('users.password.edit');
        Route::patch('users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password.update');
    });
    Route::get('visi-misi', [VisionMissionController::class, 'edit'])->middleware('permission:visi-misi.manage')->name('visi-misi.edit');
    Route::patch('visi-misi', [VisionMissionController::class, 'update'])->middleware('permission:visi-misi.manage')->name('visi-misi.update');
    Route::get('tautan-portal-akademik', [AcademicPortalSettingController::class, 'edit'])->middleware('permission:tautan-portal-akademik.manage')->name('tautan-portal-akademik.edit');
    Route::patch('tautan-portal-akademik', [AcademicPortalSettingController::class, 'update'])->middleware('permission:tautan-portal-akademik.manage')->name('tautan-portal-akademik.update');
    Route::post('struktur-pimpinan/import-json', [LeadershipPersonController::class, 'importJson'])->middleware('permission:struktur-pimpinan.manage')->name('struktur-pimpinan.import-json');
    Route::resource('struktur-pimpinan', LeadershipPersonController::class)->except(['show'])->parameters(['struktur-pimpinan' => 'person'])->middleware('permission:struktur-pimpinan.manage');
    Route::post('kerjasama/import-json', [CooperationPartnerController::class, 'importJson'])->middleware('permission:kerjasama.manage')->name('kerjasama.import-json');
    Route::resource('kerjasama', CooperationPartnerController::class)->except(['show'])->parameters(['kerjasama' => 'partner'])->middleware('permission:kerjasama.manage');
    Route::post('panduan-akademik/import-json', [AcademicGuideController::class, 'importJson'])->middleware('permission:panduan-akademik.manage')->name('panduan-akademik.import-json');
    Route::resource('panduan-akademik', AcademicGuideController::class)->except(['show'])->parameters(['panduan-akademik' => 'guide'])->middleware('permission:panduan-akademik.manage');
    Route::resource('dokumen-akreditasi', AccreditationDocumentController::class)->except(['show'])->parameters(['dokumen-akreditasi' => 'document'])->middleware('permission:dokumen-akreditasi.manage');
    Route::post('dosen/import-json', [LecturerController::class, 'importJson'])->middleware('permission:dosen.manage')->name('dosen.import-json');
    Route::resource('dosen', LecturerController::class)->except(['show'])->parameters(['dosen' => 'lecturer'])->middleware('permission:dosen.manage');
    Route::post('pengumuman/import-json', [AnnouncementItemController::class, 'importJson'])->middleware('permission:pengumuman.manage')->name('pengumuman.import-json');
    Route::post('pengumuman/{announcement}/toggle-publish', [AnnouncementItemController::class, 'togglePublish'])->middleware('permission:pengumuman.manage')->name('pengumuman.toggle-publish');
    Route::resource('pengumuman', AnnouncementItemController::class)->except(['show'])->parameters(['pengumuman' => 'announcement'])->middleware('permission:pengumuman.manage');
    Route::post('agenda/import-json', [AgendaItemController::class, 'importJson'])->middleware('permission:agenda.manage')->name('agenda.import-json');
    Route::post('agenda/{agenda}/toggle-publish', [AgendaItemController::class, 'togglePublish'])->middleware('permission:agenda.manage')->name('agenda.toggle-publish');
    Route::resource('agenda', AgendaItemController::class)->except(['show'])->parameters(['agenda' => 'agenda'])->middleware('permission:agenda.manage');
    Route::post('slideshow/restore-built-in', [HeroSlideController::class, 'restoreBuiltIn'])->middleware('permission:slideshow.manage')->name('slideshow.restore-built-in');
    Route::resource('slideshow', HeroSlideController::class)->except(['show'])->parameters(['slideshow' => 'slide'])->middleware('permission:slideshow.manage');
    Route::get('tampilan-program-beranda', [ProgramHeroSettingsController::class, 'edit'])->middleware('permission:program-heroes.manage')->name('program-heroes.edit');
    Route::patch('tampilan-program-beranda', [ProgramHeroSettingsController::class, 'update'])->middleware('permission:program-heroes.manage')->name('program-heroes.update');
    Route::get('sambutan-direktur', [DirectorGreetingController::class, 'edit'])->middleware('permission:director-greeting.manage|slideshow.manage|program-heroes.manage')->name('director-greeting.edit');
    Route::patch('sambutan-direktur', [DirectorGreetingController::class, 'update'])->middleware('permission:director-greeting.manage|slideshow.manage|program-heroes.manage')->name('director-greeting.update');
    Route::post('news/{news}/puter-translation', [NewsItemController::class, 'applyPuterTranslation'])->middleware('permission:news.manage')->name('news.puter-translation');
    Route::post('news/{news}/translation-retry', [NewsItemController::class, 'retryTranslation'])->middleware('permission:news.manage')->name('news.translation-retry');
    Route::resource('news', NewsItemController::class)->except(['show'])->middleware('permission:news.manage');
    Route::post('prodi-s2/import-json', [S2ProgramController::class, 'importJson'])->middleware('permission:prodi-s2.manage')->name('prodi-s2.import-json');
    Route::resource('prodi-s2', S2ProgramController::class)->except(['show'])->parameters(['prodi-s2' => 'program'])->middleware('permission:prodi-s2.manage');
    Route::post('prodi-s3/import-json', [S3ProgramController::class, 'importJson'])->middleware('permission:prodi-s3.manage')->name('prodi-s3.import-json');
    Route::resource('prodi-s3', S3ProgramController::class)->except(['show'])->parameters(['prodi-s3' => 'program'])->middleware('permission:prodi-s3.manage');
    Route::post('kegiatan-mahasiswa/import-json', [StudentActivityController::class, 'importJson'])->middleware('permission:kegiatan-mahasiswa.manage')->name('kegiatan-mahasiswa.import-json');
    Route::post('kegiatan-mahasiswa/{activity}/toggle-publish', [StudentActivityController::class, 'togglePublish'])->middleware('permission:kegiatan-mahasiswa.manage')->name('kegiatan-mahasiswa.toggle-publish');
    Route::resource('kegiatan-mahasiswa', StudentActivityController::class)->except(['show'])->parameters(['kegiatan-mahasiswa' => 'activity'])->middleware('permission:kegiatan-mahasiswa.manage');
    Route::post('kegiatan-alumni/import-json', [AlumniActivityController::class, 'importJson'])->middleware('permission:kegiatan-alumni.manage')->name('kegiatan-alumni.import-json');
    Route::post('kegiatan-alumni/{alumniActivity}/toggle-publish', [AlumniActivityController::class, 'togglePublish'])->middleware('permission:kegiatan-alumni.manage')->name('kegiatan-alumni.toggle-publish');
    Route::resource('kegiatan-alumni', AlumniActivityController::class)->except(['show'])->parameters(['kegiatan-alumni' => 'alumniActivity'])->middleware('permission:kegiatan-alumni.manage');

    Route::prefix('stop-korupsi')->name('stop-korupsi.')->middleware('permission:stop-korupsi.manage')->group(function (): void {
        Route::get('/', StopKorupsiHubController::class)->name('hub');
        Route::post('import-json', StopKorupsiImportController::class)->name('import-json');
        Route::get('teks/edit', function () {
            return redirect()->route('admin.stop-korupsi.konten.edit', [], 301);
        });
        Route::get('konten', [StopKorupsiPageContentController::class, 'edit'])->name('konten.edit');
        Route::patch('konten', [StopKorupsiPageContentController::class, 'update'])->name('konten.update');
        Route::resource('poin', StopKorupsiBulletController::class)->except(['show'])->parameters(['poin' => 'bullet']);
    });

    Route::prefix('stop-gratifikasi')->name('stop-gratifikasi.')->middleware('permission:stop-gratifikasi.manage')->group(function (): void {
        Route::get('/', StopGratifikasiHubController::class)->name('hub');
        Route::post('import-json', StopGratifikasiImportController::class)->name('import-json');
        Route::get('konten', [StopGratifikasiPageContentController::class, 'edit'])->name('konten.edit');
        Route::patch('konten', [StopGratifikasiPageContentController::class, 'update'])->name('konten.update');
        Route::resource('poin', StopGratifikasiBulletController::class)->except(['show'])->parameters(['poin' => 'bullet']);
    });

    Route::prefix('instrumen-zona-integritas')->name('zi.')->middleware('permission:zi.manage')->group(function (): void {
        Route::get('/', ZiHubController::class)->name('hub');
        Route::post('import-json', ZiImportController::class)->name('import-json');
        Route::get('pengantar/edit', [ZiPageIntroController::class, 'edit'])->name('pengantar.edit');
        Route::patch('pengantar', [ZiPageIntroController::class, 'update'])->name('pengantar.update');
        Route::post('pilar/{pillar}/toggle-publish', [ZiPillarController::class, 'togglePublish'])->name('pilar.toggle-publish');
        Route::resource('pilar', ZiPillarController::class)->except(['show'])->parameters(['pilar' => 'pillar']);
        Route::post('galeri/{galleryItem}/toggle-publish', [ZiGalleryItemController::class, 'togglePublish'])->name('galeri.toggle-publish');
        Route::resource('galeri', ZiGalleryItemController::class)->except(['show'])->parameters(['galeri' => 'galleryItem']);
        Route::post('saluran/{channel}/toggle-publish', [ZiComplaintChannelController::class, 'togglePublish'])->name('saluran.toggle-publish');
        Route::resource('saluran', ZiComplaintChannelController::class)->except(['show'])->parameters(['saluran' => 'channel']);
        Route::post('pembaruan/{updateItem}/toggle-publish', [ZiUpdateItemController::class, 'togglePublish'])->name('pembaruan.toggle-publish');
        Route::resource('pembaruan', ZiUpdateItemController::class)->except(['show'])->parameters(['pembaruan' => 'updateItem']);
    });
});

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en'], true), 404);
    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

Route::get('{locale}/berita/{slug}', [PpsPageController::class, 'newsShow'])
    ->where('locale', 'id|en')
    ->where('slug', '[^/]+')
    ->name('news.show');

Route::get('/', [PpsPageController::class, 'home'])->name('home');
Route::get('/visi-misi', [PpsPageController::class, 'visiMisi'])->name('visi-misi');
Route::get('/struktur-pimpinan', [PpsPageController::class, 'strukturPimpinan'])->name('struktur-pimpinan');
Route::get('/kerjasama', [PpsPageController::class, 'kerjasama'])->name('kerjasama');
Route::get('/dosen', [PpsPageController::class, 'dosen'])->name('dosen');
Route::get('/panduan-akademik', [PpsPageController::class, 'panduanAkademik'])->name('panduan-akademik');
Route::get('/kalender-akademik', [PpsPageController::class, 'kalenderAkademik'])->name('kalender-akademik');
Route::get('/kegiatan-mahasiswa', [PpsPageController::class, 'kegiatanMahasiswa'])->name('kegiatan-mahasiswa');
Route::get('/kegiatan-alumni', [PpsPageController::class, 'kegiatanAlumni'])->name('kegiatan-alumni');
Route::get('/instrumen-zona-integritas', [PpsPageController::class, 'instrumenZonaIntegritas'])->name('instrumen-zona-integritas');
Route::get('/stop-korupsi', [PpsPageController::class, 'stopKorupsi'])->name('stop-korupsi');
Route::get('/stop-gratifikasi', [PpsPageController::class, 'stopGratifikasi'])->name('stop-gratifikasi');
Route::get('/dokumen-akreditasi', [PpsPageController::class, 'dokumenAkreditasi'])->name('dokumen-akreditasi');
Route::get('/s2', [PpsPageController::class, 'programS2'])->name('program.s2');
Route::get('/s3', [PpsPageController::class, 'programS3'])->name('program.s3');

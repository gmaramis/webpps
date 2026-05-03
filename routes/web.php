<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AgendaItemController;
use App\Http\Controllers\Admin\AnnouncementItemController;
use App\Http\Controllers\Admin\CooperationPartnerController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\LeadershipPersonController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\NewsItemController;
use App\Http\Controllers\Admin\VisionMissionController;
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
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('notifications/{notification}', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [AdminProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('visi-misi', [VisionMissionController::class, 'edit'])->name('visi-misi.edit');
    Route::patch('visi-misi', [VisionMissionController::class, 'update'])->name('visi-misi.update');
    Route::post('struktur-pimpinan/import-json', [LeadershipPersonController::class, 'importJson'])->name('struktur-pimpinan.import-json');
    Route::resource('struktur-pimpinan', LeadershipPersonController::class)->except(['show'])->parameters(['struktur-pimpinan' => 'person']);
    Route::post('kerjasama/import-json', [CooperationPartnerController::class, 'importJson'])->name('kerjasama.import-json');
    Route::resource('kerjasama', CooperationPartnerController::class)->except(['show'])->parameters(['kerjasama' => 'partner']);
    Route::post('dosen/import-json', [LecturerController::class, 'importJson'])->name('dosen.import-json');
    Route::resource('dosen', LecturerController::class)->except(['show'])->parameters(['dosen' => 'lecturer']);
    Route::post('pengumuman/import-json', [AnnouncementItemController::class, 'importJson'])->name('pengumuman.import-json');
    Route::resource('pengumuman', AnnouncementItemController::class)->except(['show'])->parameters(['pengumuman' => 'announcement']);
    Route::post('agenda/import-json', [AgendaItemController::class, 'importJson'])->name('agenda.import-json');
    Route::resource('agenda', AgendaItemController::class)->except(['show'])->parameters(['agenda' => 'agenda']);
    Route::post('slideshow/restore-built-in', [HeroSlideController::class, 'restoreBuiltIn'])->name('slideshow.restore-built-in');
    Route::resource('slideshow', HeroSlideController::class)->except(['show'])->parameters(['slideshow' => 'slide']);
    Route::post('news/{news}/puter-translation', [NewsItemController::class, 'applyPuterTranslation'])->name('news.puter-translation');
    Route::post('news/{news}/translation-retry', [NewsItemController::class, 'retryTranslation'])->name('news.translation-retry');
    Route::resource('news', NewsItemController::class)->except(['show']);
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

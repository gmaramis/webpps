<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicPortalSettingUpdateRequest;
use App\Models\AcademicPortalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AcademicPortalSettingController extends Controller
{
    public function edit(): View
    {
        $table = (new AcademicPortalSetting)->getTable();
        $settingsReady = Schema::hasTable($table);

        if (! $settingsReady) {
            return view('admin.academic-portal-settings.edit', [
                'settingsReady' => false,
                'content' => null,
            ]);
        }

        $content = AcademicPortalSetting::singleton();

        return view('admin.academic-portal-settings.edit', [
            'settingsReady' => true,
            'content' => $content,
        ]);
    }

    public function update(AcademicPortalSettingUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new AcademicPortalSetting)->getTable())) {
            return redirect()->route('admin.tautan-portal-akademik.edit')->withErrors([
                'basisdata' => 'Basis data belum lengkap — jalankan: php artisan migrate',
            ]);
        }

        AcademicPortalSetting::singleton()->update($request->validated());

        return redirect()->route('admin.tautan-portal-akademik.edit')->with('status', 'Tautan portal akademik berhasil disimpan.');
    }
}

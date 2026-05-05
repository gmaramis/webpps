<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZiPageIntroUpdateRequest;
use App\Models\ZiPageIntro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiPageIntroController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_page_intros')) {
            return redirect()->route('admin.zi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $content = ZiPageIntro::singleton();

        return view('admin.instrumen-zona-integritas.pengantar-edit', compact('content'));
    }

    public function update(ZiPageIntroUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable('zi_page_intros')) {
            return redirect()->route('admin.zi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        ZiPageIntro::singleton()->update($request->validated());

        return redirect()->route('admin.zi.pengantar.edit')->with('status', 'Teks pengantar ZI disimpan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramHeroSettingsRequest;
use App\Models\HomepageProgramDisplay;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class ProgramHeroSettingsController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        if (! Schema::hasTable((new HomepageProgramDisplay)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $display = HomepageProgramDisplay::singleton();

        return view('admin.program-heroes.edit', compact('display'));
    }

    public function update(ProgramHeroSettingsRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new HomepageProgramDisplay)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $display = HomepageProgramDisplay::singleton();
        $data = [];

        if ($request->hasFile('magister_hero')) {
            HomepageProgramDisplay::deleteStoredHero($display->magister_hero_path);
            $data['magister_hero_path'] = $request->file('magister_hero')->store('programs-heroes', 'public');
        }

        if ($request->hasFile('doktor_hero')) {
            HomepageProgramDisplay::deleteStoredHero($display->doktor_hero_path);
            $data['doktor_hero_path'] = $request->file('doktor_hero')->store('programs-heroes', 'public');
        }

        if ($data !== []) {
            $display->fill($data);
            $display->save();
        }

        return redirect()->route('admin.program-heroes.edit')->with('status', 'Gambar hero program beranda diperbarui.');
    }
}

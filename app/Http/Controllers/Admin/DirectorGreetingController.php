<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectorGreetingUpdateRequest;
use App\Models\DirectorGreeting;
use App\Support\PpsContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class DirectorGreetingController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        if (! Schema::hasTable((new DirectorGreeting)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        DirectorGreeting::singleton();

        return view('admin.director-greeting.edit');
    }

    public function update(DirectorGreetingUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new DirectorGreeting)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $row = DirectorGreeting::singleton();
        $existingPhoto = trim((string) ($row->photo_path ?? ''));

        $photoPath = $existingPhoto;
        if ($request->hasFile('photo')) {
            DirectorGreeting::deleteStoredPhoto($existingPhoto);
            $photoPath = $request->file('photo')->store('director-greeting', 'public');
        }

        $paragraphs = [];
        foreach ($request->input('paragraphs', []) as $item) {
            if (! is_array($item)) {
                continue;
            }
            $id = trim((string) ($item['id'] ?? ''));
            $en = trim((string) ($item['en'] ?? ''));
            if ($id === '' && $en === '') {
                continue;
            }
            $paragraphs[] = ['id' => $id, 'en' => $en];
        }

        $row->fill([
            'photo_path' => $photoPath !== '' ? $photoPath : null,
            'name_id' => trim((string) $request->input('name_id', '')) ?: null,
            'name_en' => trim((string) $request->input('name_en', '')) ?: null,
            'role_id' => trim((string) $request->input('role_id', '')) ?: null,
            'role_en' => trim((string) $request->input('role_en', '')) ?: null,
            'section_eyebrow_id' => (string) $request->input('section_eyebrow_id', ''),
            'section_eyebrow_en' => (string) $request->input('section_eyebrow_en', ''),
            'section_title_id' => (string) $request->input('section_title_id', ''),
            'section_title_en' => (string) $request->input('section_title_en', ''),
            'section_quote_label_id' => (string) $request->input('section_quote_label_id', ''),
            'section_quote_label_en' => (string) $request->input('section_quote_label_en', ''),
            'paragraphs' => $paragraphs,
        ]);
        $row->save();

        PpsContent::flush();

        return redirect()->route('admin.director-greeting.edit')->with('status', 'Sambutan direktur beranda disimpan ke basis data.');
    }
}

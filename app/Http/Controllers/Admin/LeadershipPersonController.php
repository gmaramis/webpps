<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadershipPersonRequest;
use App\Models\LeadershipPerson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class LeadershipPersonController extends Controller
{
    public function index(): View
    {
        $people = LeadershipPerson::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $legacyJsonExists = is_readable(resource_path('data/leaders.json'));

        return view('admin.struktur-pimpinan.index', compact('people', 'legacyJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) LeadershipPerson::query()->max('sort_order') + 1;

        return view('admin.struktur-pimpinan.create', [
            'person' => new LeadershipPerson([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(LeadershipPersonRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('leadership', 'public');
        }

        LeadershipPerson::query()->create($data);

        return redirect()->route('admin.struktur-pimpinan.index')->with('status', 'Data pimpinan ditambahkan.');
    }

    public function edit(LeadershipPerson $person): View
    {
        return view('admin.struktur-pimpinan.edit', compact('person'));
    }

    public function update(LeadershipPersonRequest $request, LeadershipPerson $person): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            LeadershipPerson::deleteStoredUpload($person->photo);
            $data['photo'] = $request->file('photo')->store('leadership', 'public');
        }

        $person->update($data);

        return redirect()->route('admin.struktur-pimpinan.index')->with('status', 'Data pimpinan diperbarui.');
    }

    public function destroy(LeadershipPerson $person): RedirectResponse
    {
        LeadershipPerson::deleteStoredUpload($person->photo);
        $person->delete();

        return redirect()->route('admin.struktur-pimpinan.index')->with('status', 'Data pimpinan dihapus.');
    }

    /**
     * Impor sekali dari resources/data/leaders.json (mengganti semua baris di basis data).
     */
    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new LeadershipPerson)->getTable())) {
            return redirect()->route('admin.struktur-pimpinan.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = LeadershipPerson::importFromLegacyJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.struktur-pimpinan.index')->withErrors([
                'json' => 'File leaders.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.struktur-pimpinan.index')->withErrors([
                'kosong' => 'Tidak ada data di leaders.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.struktur-pimpinan.index')->with('status', "Berhasil mengimpor {$n} entri dari leaders.json.");
    }
}

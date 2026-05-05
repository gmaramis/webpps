<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\S2ProgramRequest;
use App\Models\S2Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class S2ProgramController extends Controller
{
    public function index(): View
    {
        $programs = S2Program::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.s2-programs.index', compact('programs', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) S2Program::query()->max('sort_order') + 1;

        return view('admin.s2-programs.create', [
            'program' => new S2Program([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(S2ProgramRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (($data['slug'] ?? null) === null || $data['slug'] === '') {
            $data['slug'] = S2Program::uniqueSlugFrom((string) $data['name_id']);
        }

        S2Program::query()->create($data);

        return redirect()->route('admin.prodi-s2.index')->with('status', 'Program S2 ditambahkan.');
    }

    public function edit(S2Program $program): View
    {
        return view('admin.s2-programs.edit', compact('program'));
    }

    public function update(S2ProgramRequest $request, S2Program $program): RedirectResponse
    {
        $data = $request->validated();
        if (($data['slug'] ?? null) === null || $data['slug'] === '') {
            unset($data['slug']);
        }

        $program->update($data);

        return redirect()->route('admin.prodi-s2.index')->with('status', 'Program S2 diperbarui.');
    }

    public function destroy(S2Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.prodi-s2.index')->with('status', 'Program S2 dihapus.');
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new S2Program)->getTable())) {
            return redirect()->route('admin.prodi-s2.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = S2Program::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.prodi-s2.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.prodi-s2.index')->withErrors([
                'kosong' => 'Tidak ada PROGRAMS_MAGISTER di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.prodi-s2.index')->with('status', "Berhasil mengimpor {$n} program dari pps-content.json.");
    }
}

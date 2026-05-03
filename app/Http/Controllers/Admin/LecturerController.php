<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LecturerRequest;
use App\Models\Lecturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class LecturerController extends Controller
{
    public function index(): View
    {
        $lecturers = Lecturer::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.dosen.index', compact('lecturers', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) Lecturer::query()->max('sort_order') + 1;

        return view('admin.dosen.create', [
            'lecturer' => new Lecturer([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(LecturerRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('lecturer-photos', 'public');
        }

        Lecturer::query()->create($data);

        return redirect()->route('admin.dosen.index')->with('status', 'Data dosen ditambahkan.');
    }

    public function edit(Lecturer $lecturer): View
    {
        return view('admin.dosen.edit', compact('lecturer'));
    }

    public function update(LecturerRequest $request, Lecturer $lecturer): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            Lecturer::deleteStoredUpload($lecturer->photo);
            $data['photo'] = $request->file('photo')->store('lecturer-photos', 'public');
        }

        $lecturer->update($data);

        return redirect()->route('admin.dosen.index')->with('status', 'Data dosen diperbarui.');
    }

    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        Lecturer::deleteStoredUpload($lecturer->photo);
        $lecturer->delete();

        return redirect()->route('admin.dosen.index')->with('status', 'Data dosen dihapus.');
    }

    /** Impor dari key LECTURERS di pps-content.json (mengganti seluruh baris). */
    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new Lecturer)->getTable())) {
            return redirect()->route('admin.dosen.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = Lecturer::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.dosen.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.dosen.index')->withErrors([
                'kosong' => 'Tidak ada LECTURERS di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.dosen.index')->with('status', "Berhasil mengimpor {$n} dosen dari pps-content.json.");
    }
}

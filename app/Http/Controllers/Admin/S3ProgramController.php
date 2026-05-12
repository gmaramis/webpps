<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\S3ProgramRequest;
use App\Models\S3Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class S3ProgramController extends Controller
{
    public function index(): View
    {
        $programs = S3Program::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.s3-programs.index', compact('programs', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) S3Program::query()->max('sort_order') + 1;

        return view('admin.s3-programs.create', [
            'program' => new S3Program([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(S3ProgramRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['brochure_image', 'remove_brochure'])->all();
        if (($data['slug'] ?? null) === null || $data['slug'] === '') {
            $data['slug'] = S3Program::uniqueSlugFrom((string) $data['name_id']);
        }

        if ($request->hasFile('brochure_image')) {
            $data['brochure_image'] = $request->file('brochure_image')->store('program-brochures', 'public');
        }

        S3Program::query()->create($data);

        return redirect()->route('admin.prodi-s3.index')->with('status', 'Program S3 ditambahkan.');
    }

    public function edit(S3Program $program): View
    {
        return view('admin.s3-programs.edit', compact('program'));
    }

    public function update(S3ProgramRequest $request, S3Program $program): RedirectResponse
    {
        $data = collect($request->validated())->except(['brochure_image', 'remove_brochure'])->all();
        if (($data['slug'] ?? null) === null || $data['slug'] === '') {
            unset($data['slug']);
        }

        if ($request->boolean('remove_brochure')) {
            S3Program::deleteStoredBrochure($program->brochure_image);
            $data['brochure_image'] = null;
        }

        if ($request->hasFile('brochure_image')) {
            S3Program::deleteStoredBrochure($program->brochure_image);
            $data['brochure_image'] = $request->file('brochure_image')->store('program-brochures', 'public');
        }

        $program->update($data);

        return redirect()->route('admin.prodi-s3.index')->with('status', 'Program S3 diperbarui.');
    }

    public function destroy(S3Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.prodi-s3.index')->with('status', 'Program S3 dihapus.');
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new S3Program)->getTable())) {
            return redirect()->route('admin.prodi-s3.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = S3Program::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.prodi-s3.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.prodi-s3.index')->withErrors([
                'kosong' => 'Tidak ada PROGRAMS_DOKTOR di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.prodi-s3.index')->with('status', "Berhasil mengimpor {$n} program dari pps-content.json.");
    }
}

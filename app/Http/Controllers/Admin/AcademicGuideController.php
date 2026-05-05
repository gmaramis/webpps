<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicGuideRequest;
use App\Models\AcademicGuide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class AcademicGuideController extends Controller
{
    public function index(): View
    {
        $guides = AcademicGuide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.panduan-akademik.index', compact('guides', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) AcademicGuide::query()->max('sort_order') + 1;

        return view('admin.panduan-akademik.create', [
            'guide' => new AcademicGuide([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(AcademicGuideRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['pdf'])->all();
        $data['file_path'] = $request->file('pdf')->store('academic-guides', 'public');

        AcademicGuide::query()->create($data);

        return redirect()->route('admin.panduan-akademik.index')->with('status', 'Dokumen panduan ditambahkan.');
    }

    public function edit(AcademicGuide $guide): View
    {
        return view('admin.panduan-akademik.edit', compact('guide'));
    }

    public function update(AcademicGuideRequest $request, AcademicGuide $guide): RedirectResponse
    {
        $data = collect($request->validated())->except(['pdf'])->all();

        if ($request->hasFile('pdf')) {
            AcademicGuide::deleteStoredUpload($guide->file_path);
            $data['file_path'] = $request->file('pdf')->store('academic-guides', 'public');
        }

        $guide->update($data);

        return redirect()->route('admin.panduan-akademik.index')->with('status', 'Dokumen panduan diperbarui.');
    }

    public function destroy(AcademicGuide $guide): RedirectResponse
    {
        AcademicGuide::deleteStoredUpload($guide->file_path);
        $guide->delete();

        return redirect()->route('admin.panduan-akademik.index')->with('status', 'Dokumen dihapus.');
    }

    /** Impor dari key ACADEMIC_GUIDES di pps-content.json (mengganti seluruh baris). */
    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new AcademicGuide)->getTable())) {
            return redirect()->route('admin.panduan-akademik.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = AcademicGuide::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.panduan-akademik.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.panduan-akademik.index')->withErrors([
                'kosong' => 'Tidak ada ACADEMIC_GUIDES di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.panduan-akademik.index')->with('status', "Berhasil mengimpor {$n} dokumen dari pps-content.json.");
    }
}

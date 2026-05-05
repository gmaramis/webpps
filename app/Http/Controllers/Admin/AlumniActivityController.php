<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlumniActivityRequest;
use App\Models\AlumniActivity;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class AlumniActivityController extends Controller
{
    public function index(): View
    {
        $activities = AlumniActivity::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.kegiatan-alumni.index', compact('activities', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) AlumniActivity::query()->max('sort_order') + 1;

        return view('admin.kegiatan-alumni.create', [
            'activity' => new AlumniActivity([
                'sort_order' => $nextOrder,
                'is_published' => false,
            ]),
        ]);
    }

    public function store(AlumniActivityRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();
        $data['image'] = $request->file('photo')->store('alumni-activities', 'public');

        AlumniActivity::query()->create($data);

        return redirect()->route('admin.kegiatan-alumni.index')->with('status', 'Kegiatan alumni ditambahkan.');
    }

    public function edit(AlumniActivity $alumniActivity): View
    {
        return view('admin.kegiatan-alumni.edit', ['activity' => $alumniActivity]);
    }

    public function update(AlumniActivityRequest $request, AlumniActivity $alumniActivity): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            AlumniActivity::deleteStoredUpload($alumniActivity->image);
            $data['image'] = $request->file('photo')->store('alumni-activities', 'public');
        }

        $alumniActivity->update($data);

        return redirect()->route('admin.kegiatan-alumni.index')->with('status', 'Kegiatan alumni diperbarui.');
    }

    public function destroy(AlumniActivity $alumniActivity): RedirectResponse
    {
        AlumniActivity::deleteStoredUpload($alumniActivity->image);
        $alumniActivity->delete();

        return AdminRedirect::toIndexRoute('admin.kegiatan-alumni.index')->with('status', 'Kegiatan dihapus.');
    }

    public function togglePublish(AlumniActivity $alumniActivity): RedirectResponse
    {
        $alumniActivity->update(['is_published' => ! $alumniActivity->is_published]);

        $label = $alumniActivity->is_published ? 'ditayangkan' : 'disembunyikan dari publik';

        return AdminRedirect::toIndexRoute('admin.kegiatan-alumni.index')->with('status', "Status: kegiatan {$label}.");
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new AlumniActivity)->getTable())) {
            return redirect()->route('admin.kegiatan-alumni.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = AlumniActivity::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.kegiatan-alumni.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.kegiatan-alumni.index')->withErrors([
                'kosong' => 'Tidak ada ALUMNI_ACTIVITIES di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.kegiatan-alumni.index')->with('status', "Berhasil mengimpor {$n} kegiatan alumni dari pps-content.json.");
    }
}

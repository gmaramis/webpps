<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentActivityRequest;
use App\Models\StudentActivity;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class StudentActivityController extends Controller
{
    public function index(): View
    {
        $activities = StudentActivity::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.kegiatan-mahasiswa.index', compact('activities', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) StudentActivity::query()->max('sort_order') + 1;

        return view('admin.kegiatan-mahasiswa.create', [
            'activity' => new StudentActivity([
                'sort_order' => $nextOrder,
                'is_published' => false,
            ]),
        ]);
    }

    public function store(StudentActivityRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();
        $data['image'] = $request->file('photo')->store('student-activities', 'public');

        StudentActivity::query()->create($data);

        return redirect()->route('admin.kegiatan-mahasiswa.index')->with('status', 'Kegiatan mahasiswa ditambahkan.');
    }

    public function edit(StudentActivity $activity): View
    {
        return view('admin.kegiatan-mahasiswa.edit', compact('activity'));
    }

    public function update(StudentActivityRequest $request, StudentActivity $activity): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->hasFile('photo')) {
            StudentActivity::deleteStoredUpload($activity->image);
            $data['image'] = $request->file('photo')->store('student-activities', 'public');
        }

        $activity->update($data);

        return redirect()->route('admin.kegiatan-mahasiswa.index')->with('status', 'Kegiatan mahasiswa diperbarui.');
    }

    public function destroy(StudentActivity $activity): RedirectResponse
    {
        StudentActivity::deleteStoredUpload($activity->image);
        $activity->delete();

        return AdminRedirect::toIndexRoute('admin.kegiatan-mahasiswa.index')->with('status', 'Kegiatan dihapus.');
    }

    public function togglePublish(StudentActivity $activity): RedirectResponse
    {
        $activity->update(['is_published' => ! $activity->is_published]);

        $label = $activity->is_published ? 'ditayangkan' : 'disembunyikan dari publik';

        return AdminRedirect::toIndexRoute('admin.kegiatan-mahasiswa.index')->with('status', "Status: kegiatan {$label}.");
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new StudentActivity)->getTable())) {
            return redirect()->route('admin.kegiatan-mahasiswa.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = StudentActivity::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.kegiatan-mahasiswa.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.kegiatan-mahasiswa.index')->withErrors([
                'kosong' => 'Tidak ada STUDENT_ACTIVITIES di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.kegiatan-mahasiswa.index')->with('status', "Berhasil mengimpor {$n} kegiatan dari pps-content.json.");
    }
}

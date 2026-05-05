<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccreditationDocumentStoreRequest;
use App\Http\Requests\AccreditationDocumentUpdateRequest;
use App\Models\AccreditationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AccreditationDocumentController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable((new AccreditationDocument)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $documents = AccreditationDocument::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.dokumen-akreditasi.index', compact('documents'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable((new AccreditationDocument)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $nextOrder = (int) AccreditationDocument::query()->max('sort_order') + 1;

        return view('admin.dokumen-akreditasi.create', [
            'document' => new AccreditationDocument([
                'sort_order' => $nextOrder,
                'title_id' => '',
                'title_en' => null,
                'is_published' => true,
            ]),
        ]);
    }

    public function store(AccreditationDocumentStoreRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['pdf'])->all();
        $data['title_en'] = isset($data['title_en']) && trim((string) $data['title_en']) !== '' ? trim((string) $data['title_en']) : null;
        $data['file_path'] = $request->file('pdf')->store('accreditation-documents', 'public');

        AccreditationDocument::query()->create($data);

        return redirect()->route('admin.dokumen-akreditasi.index')->with('status', 'Dokumen akreditasi ditambahkan.');
    }

    public function edit(AccreditationDocument $document): View
    {
        return view('admin.dokumen-akreditasi.edit', compact('document'));
    }

    public function update(AccreditationDocumentUpdateRequest $request, AccreditationDocument $document): RedirectResponse
    {
        $data = collect($request->validated())->except(['pdf'])->all();
        $data['title_en'] = isset($data['title_en']) && trim((string) $data['title_en']) !== '' ? trim((string) $data['title_en']) : null;

        if ($request->hasFile('pdf')) {
            AccreditationDocument::deleteStoredUpload($document->file_path);
            $data['file_path'] = $request->file('pdf')->store('accreditation-documents', 'public');
        }

        $document->update($data);

        return redirect()->route('admin.dokumen-akreditasi.index')->with('status', 'Dokumen diperbarui.');
    }

    public function destroy(AccreditationDocument $document): RedirectResponse
    {
        AccreditationDocument::deleteStoredUpload($document->file_path);
        $document->delete();

        return redirect()->route('admin.dokumen-akreditasi.index')->with('status', 'Dokumen dihapus.');
    }
}

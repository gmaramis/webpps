<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CooperationPartnerRequest;
use App\Models\CooperationPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class CooperationPartnerController extends Controller
{
    public function index(): View
    {
        $partners = CooperationPartner::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.kerjasama.index', compact('partners', 'ppsJsonExists'));
    }

    public function create(): View
    {
        $nextOrder = (int) CooperationPartner::query()->max('sort_order') + 1;

        return view('admin.kerjasama.create', [
            'partner' => new CooperationPartner([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(CooperationPartnerRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['logo'])->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('cooperation-logos', 'public');
        }

        CooperationPartner::query()->create($data);

        return redirect()->route('admin.kerjasama.index')->with('status', 'Mitra kerjasama ditambahkan.');
    }

    public function edit(CooperationPartner $partner): View
    {
        return view('admin.kerjasama.edit', compact('partner'));
    }

    public function update(CooperationPartnerRequest $request, CooperationPartner $partner): RedirectResponse
    {
        $data = collect($request->validated())->except(['logo'])->all();

        if ($request->hasFile('logo')) {
            CooperationPartner::deleteStoredUpload($partner->logo);
            $data['logo'] = $request->file('logo')->store('cooperation-logos', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.kerjasama.index')->with('status', 'Mitra kerjasama diperbarui.');
    }

    public function destroy(CooperationPartner $partner): RedirectResponse
    {
        CooperationPartner::deleteStoredUpload($partner->logo);
        $partner->delete();

        return redirect()->route('admin.kerjasama.index')->with('status', 'Mitra dihapus.');
    }

    /** Impor dari key PARTNERS di pps-content.json (mengganti seluruh baris). */
    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new CooperationPartner)->getTable())) {
            return redirect()->route('admin.kerjasama.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = CooperationPartner::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.kerjasama.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.kerjasama.index')->withErrors([
                'kosong' => 'Tidak ada PARTNERS di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.kerjasama.index')->with('status', "Berhasil mengimpor {$n} mitra dari pps-content.json.");
    }
}

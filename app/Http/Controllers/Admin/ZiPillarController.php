<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZiPillarRequest;
use App\Models\ZiPillar;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiPillarController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_pillars')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $rows = ZiPillar::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.instrumen-zona-integritas.pilar-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_pillars')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $next = (int) ZiPillar::query()->max('sort_order') + 1;

        return view('admin.instrumen-zona-integritas.pilar-create', [
            'pillar' => new ZiPillar(['sort_order' => $next, 'is_published' => false]),
        ]);
    }

    public function store(ZiPillarRequest $request): RedirectResponse
    {
        ZiPillar::query()->create($request->validated());

        return redirect()->route('admin.zi.pilar.index')->with('status', 'Pilar ditambahkan.');
    }

    public function edit(ZiPillar $pillar): View
    {
        return view('admin.instrumen-zona-integritas.pilar-edit', ['pillar' => $pillar]);
    }

    public function update(ZiPillarRequest $request, ZiPillar $pillar): RedirectResponse
    {
        $pillar->update($request->validated());

        return redirect()->route('admin.zi.pilar.index')->with('status', 'Pilar diperbarui.');
    }

    public function destroy(ZiPillar $pillar): RedirectResponse
    {
        $pillar->delete();

        return AdminRedirect::toIndexRoute('admin.zi.pilar.index')->with('status', 'Pilar dihapus.');
    }

    public function togglePublish(ZiPillar $pillar): RedirectResponse
    {
        $pillar->update(['is_published' => ! $pillar->is_published]);

        return AdminRedirect::toIndexRoute('admin.zi.pilar.index')->with('status', $pillar->is_published ? 'Pilar ditayangkan.' : 'Pilar disembunyikan dari publik.');
    }
}

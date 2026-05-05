<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StopKorupsiBulletRequest;
use App\Models\StopKorupsiBullet;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopKorupsiBulletController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('stop_korupsi_bullets')) {
            return redirect()->route('admin.stop-korupsi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $rows = StopKorupsiBullet::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.stop-korupsi.poin-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('stop_korupsi_bullets')) {
            return redirect()->route('admin.stop-korupsi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $next = (int) StopKorupsiBullet::query()->max('sort_order') + 1;

        return view('admin.stop-korupsi.poin-create', [
            'bullet' => new StopKorupsiBullet(['sort_order' => $next, 'text_id' => '', 'text_en' => null]),
        ]);
    }

    public function store(StopKorupsiBulletRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['text_en'] = isset($data['text_en']) && trim((string) $data['text_en']) !== '' ? $data['text_en'] : null;
        StopKorupsiBullet::query()->create($data);

        return redirect()->route('admin.stop-korupsi.poin.index')->with('status', 'Poin ditambahkan.');
    }

    public function edit(StopKorupsiBullet $bullet): View
    {
        return view('admin.stop-korupsi.poin-edit', compact('bullet'));
    }

    public function update(StopKorupsiBulletRequest $request, StopKorupsiBullet $bullet): RedirectResponse
    {
        $data = $request->validated();
        $data['text_en'] = isset($data['text_en']) && trim((string) $data['text_en']) !== '' ? $data['text_en'] : null;
        $bullet->update($data);

        return AdminRedirect::toIndexRoute('admin.stop-korupsi.poin.index')->with('status', 'Poin diperbarui.');
    }

    public function destroy(StopKorupsiBullet $bullet): RedirectResponse
    {
        $bullet->delete();

        return AdminRedirect::toIndexRoute('admin.stop-korupsi.poin.index')->with('status', 'Poin dihapus.');
    }
}

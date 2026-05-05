<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StopGratifikasiBulletRequest;
use App\Models\StopGratifikasiBullet;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopGratifikasiBulletController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('stop_gratifikasi_bullets')) {
            return redirect()->route('admin.stop-gratifikasi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $rows = StopGratifikasiBullet::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.stop-gratifikasi.poin-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('stop_gratifikasi_bullets')) {
            return redirect()->route('admin.stop-gratifikasi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $next = (int) StopGratifikasiBullet::query()->max('sort_order') + 1;

        return view('admin.stop-gratifikasi.poin-create', [
            'bullet' => new StopGratifikasiBullet(['sort_order' => $next, 'text_id' => '', 'text_en' => null]),
        ]);
    }

    public function store(StopGratifikasiBulletRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['text_en'] = isset($data['text_en']) && trim((string) $data['text_en']) !== '' ? $data['text_en'] : null;
        StopGratifikasiBullet::query()->create($data);

        return redirect()->route('admin.stop-gratifikasi.poin.index')->with('status', 'Poin ditambahkan.');
    }

    public function edit(StopGratifikasiBullet $bullet): View
    {
        return view('admin.stop-gratifikasi.poin-edit', compact('bullet'));
    }

    public function update(StopGratifikasiBulletRequest $request, StopGratifikasiBullet $bullet): RedirectResponse
    {
        $data = $request->validated();
        $data['text_en'] = isset($data['text_en']) && trim((string) $data['text_en']) !== '' ? $data['text_en'] : null;
        $bullet->update($data);

        return AdminRedirect::toIndexRoute('admin.stop-gratifikasi.poin.index')->with('status', 'Poin diperbarui.');
    }

    public function destroy(StopGratifikasiBullet $bullet): RedirectResponse
    {
        $bullet->delete();

        return AdminRedirect::toIndexRoute('admin.stop-gratifikasi.poin.index')->with('status', 'Poin dihapus.');
    }
}

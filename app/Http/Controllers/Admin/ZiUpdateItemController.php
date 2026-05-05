<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZiUpdateItemRequest;
use App\Models\ZiUpdateItem;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiUpdateItemController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_update_items')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $rows = ZiUpdateItem::query()
            ->orderBy('sort_order')
            ->orderByDesc('date_iso')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.instrumen-zona-integritas.pembaruan-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_update_items')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $next = (int) ZiUpdateItem::query()->max('sort_order') + 1;

        return view('admin.instrumen-zona-integritas.pembaruan-create', [
            'item' => new ZiUpdateItem([
                'sort_order' => $next,
                'is_published' => false,
                'date_iso' => now()->toDateString(),
                'href' => '#',
            ]),
        ]);
    }

    public function store(ZiUpdateItemRequest $request): RedirectResponse
    {
        ZiUpdateItem::query()->create($request->validated());

        return redirect()->route('admin.zi.pembaruan.index')->with('status', 'Pembaruan ditambahkan.');
    }

    public function edit(ZiUpdateItem $updateItem): View
    {
        return view('admin.instrumen-zona-integritas.pembaruan-edit', ['item' => $updateItem]);
    }

    public function update(ZiUpdateItemRequest $request, ZiUpdateItem $updateItem): RedirectResponse
    {
        $updateItem->update($request->validated());

        return redirect()->route('admin.zi.pembaruan.index')->with('status', 'Pembaruan diperbarui.');
    }

    public function destroy(ZiUpdateItem $updateItem): RedirectResponse
    {
        $updateItem->delete();

        return AdminRedirect::toIndexRoute('admin.zi.pembaruan.index')->with('status', 'Pembaruan dihapus.');
    }

    public function togglePublish(ZiUpdateItem $updateItem): RedirectResponse
    {
        $updateItem->update(['is_published' => ! $updateItem->is_published]);

        return AdminRedirect::toIndexRoute('admin.zi.pembaruan.index')->with('status', $updateItem->is_published ? 'Pembaruan ditayangkan.' : 'Pembaruan disembunyikan dari publik.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZiGalleryItemRequest;
use App\Models\ZiGalleryItem;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiGalleryItemController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_gallery_items')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $rows = ZiGalleryItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.instrumen-zona-integritas.galeri-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_gallery_items')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $next = (int) ZiGalleryItem::query()->max('sort_order') + 1;

        return view('admin.instrumen-zona-integritas.galeri-create', [
            'item' => new ZiGalleryItem(['sort_order' => $next, 'is_published' => false]),
        ]);
    }

    public function store(ZiGalleryItemRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();
        $data['image'] = $request->file('photo')->store('zi-gallery', 'public');
        ZiGalleryItem::query()->create($data);

        return redirect()->route('admin.zi.galeri.index')->with('status', 'Item galeri ditambahkan.');
    }

    public function edit(ZiGalleryItem $galleryItem): View
    {
        return view('admin.instrumen-zona-integritas.galeri-edit', ['item' => $galleryItem]);
    }

    public function update(ZiGalleryItemRequest $request, ZiGalleryItem $galleryItem): RedirectResponse
    {
        $data = collect($request->validated())->except(['photo'])->all();
        if ($request->hasFile('photo')) {
            ZiGalleryItem::deleteStoredUpload($galleryItem->image);
            $data['image'] = $request->file('photo')->store('zi-gallery', 'public');
        }
        $galleryItem->update($data);

        return redirect()->route('admin.zi.galeri.index')->with('status', 'Item galeri diperbarui.');
    }

    public function destroy(ZiGalleryItem $galleryItem): RedirectResponse
    {
        ZiGalleryItem::deleteStoredUpload($galleryItem->image);
        $galleryItem->delete();

        return AdminRedirect::toIndexRoute('admin.zi.galeri.index')->with('status', 'Item dihapus.');
    }

    public function togglePublish(ZiGalleryItem $galleryItem): RedirectResponse
    {
        $galleryItem->update(['is_published' => ! $galleryItem->is_published]);

        return AdminRedirect::toIndexRoute('admin.zi.galeri.index')->with('status', $galleryItem->is_published ? 'Galeri ditayangkan.' : 'Galeri disembunyikan dari publik.');
    }
}

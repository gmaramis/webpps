<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementItemRequest;
use App\Models\AnnouncementItem;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class AnnouncementItemController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        if (! in_array($status, ['all', 'published', 'draft'], true)) {
            $status = 'all';
        }

        $query = AnnouncementItem::query()
            ->orderBy('sort_order')
            ->orderByDesc('date_iso')
            ->orderByDesc('id');

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $announcements = $query
            ->paginate(15)
            ->withQueryString();
        $statusCounts = [
            'all' => AnnouncementItem::query()->count(),
            'published' => AnnouncementItem::query()->where('is_published', true)->count(),
            'draft' => AnnouncementItem::query()->where('is_published', false)->count(),
        ];

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.pengumuman.index', compact('announcements', 'ppsJsonExists', 'status', 'statusCounts'));
    }

    public function create(): View
    {
        $nextOrder = (int) AnnouncementItem::query()->max('sort_order') + 1;

        return view('admin.pengumuman.create', [
            'announcement' => new AnnouncementItem([
                'sort_order' => $nextOrder,
                'date_iso' => now()->format('Y-m-d'),
                'href' => '#',
                'is_published' => false,
            ]),
        ]);
    }

    public function store(AnnouncementItemRequest $request): RedirectResponse
    {
        AnnouncementItem::query()->create($request->validated());

        return redirect()->route('admin.pengumuman.index')->with('status', 'Pengumuman ditambahkan.');
    }

    public function edit(AnnouncementItem $announcement): View
    {
        return view('admin.pengumuman.edit', compact('announcement'));
    }

    public function update(AnnouncementItemRequest $request, AnnouncementItem $announcement): RedirectResponse
    {
        $announcement->update($request->validated());

        return redirect()->route('admin.pengumuman.index')->with('status', 'Pengumuman diperbarui.');
    }

    public function destroy(AnnouncementItem $announcement): RedirectResponse
    {
        $announcement->delete();

        return AdminRedirect::toIndexRoute('admin.pengumuman.index')->with('status', 'Pengumuman dihapus.');
    }

    public function togglePublish(AnnouncementItem $announcement): RedirectResponse
    {
        $announcement->update(['is_published' => ! $announcement->is_published]);

        $label = $announcement->is_published ? 'ditayangkan' : 'disembunyikan dari publik';

        return AdminRedirect::toIndexRoute('admin.pengumuman.index')->with('status', "Status: pengumuman {$label}.");
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new AnnouncementItem)->getTable())) {
            return redirect()->route('admin.pengumuman.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = AnnouncementItem::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.pengumuman.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.pengumuman.index')->withErrors([
                'kosong' => 'Tidak ada ANNOUNCEMENTS di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.pengumuman.index')->with('status', "Berhasil mengimpor {$n} pengumuman dari pps-content.json.");
    }
}

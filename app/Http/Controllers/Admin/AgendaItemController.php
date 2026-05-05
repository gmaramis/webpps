<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaItemRequest;
use App\Models\AgendaItem;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class AgendaItemController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        if (! in_array($status, ['all', 'published', 'draft'], true)) {
            $status = 'all';
        }

        $query = AgendaItem::query()
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $agendaItems = $query
            ->paginate(15)
            ->withQueryString();
        $statusCounts = [
            'all' => AgendaItem::query()->count(),
            'published' => AgendaItem::query()->where('is_published', true)->count(),
            'draft' => AgendaItem::query()->where('is_published', false)->count(),
        ];

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.agenda.index', compact('agendaItems', 'ppsJsonExists', 'status', 'statusCounts'));
    }

    public function create(): View
    {
        $nextOrder = (int) AgendaItem::query()->max('sort_order') + 1;

        return view('admin.agenda.create', [
            'agenda' => new AgendaItem([
                'sort_order' => $nextOrder,
                'day' => '01',
                'month_id' => 'JAN',
                'month_en' => 'JAN',
                'href' => '#',
                'is_published' => false,
            ]),
        ]);
    }

    public function store(AgendaItemRequest $request): RedirectResponse
    {
        AgendaItem::query()->create($request->validated());

        return redirect()->route('admin.agenda.index')->with('status', 'Agenda ditambahkan.');
    }

    public function edit(AgendaItem $agenda): View
    {
        return view('admin.agenda.edit', compact('agenda'));
    }

    public function update(AgendaItemRequest $request, AgendaItem $agenda): RedirectResponse
    {
        $agenda->update($request->validated());

        return redirect()->route('admin.agenda.index')->with('status', 'Agenda diperbarui.');
    }

    public function destroy(AgendaItem $agenda): RedirectResponse
    {
        $agenda->delete();

        return AdminRedirect::toIndexRoute('admin.agenda.index')->with('status', 'Agenda dihapus.');
    }

    public function togglePublish(AgendaItem $agenda): RedirectResponse
    {
        $agenda->update(['is_published' => ! $agenda->is_published]);

        $label = $agenda->is_published ? 'ditayangkan' : 'disembunyikan dari publik';

        return AdminRedirect::toIndexRoute('admin.agenda.index')->with('status', "Status: agenda {$label}.");
    }

    public function importJson(): RedirectResponse
    {
        if (! Schema::hasTable((new AgendaItem)->getTable())) {
            return redirect()->route('admin.agenda.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        try {
            $n = AgendaItem::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.agenda.index')->withErrors([
                'json' => 'pps-content.json tidak valid: '.$e->getMessage(),
            ]);
        }

        if ($n === 0) {
            return redirect()->route('admin.agenda.index')->withErrors([
                'kosong' => 'Tidak ada AGENDA di pps-content.json atau berkas tidak ada.',
            ]);
        }

        return redirect()->route('admin.agenda.index')->with('status', "Berhasil mengimpor {$n} agenda dari pps-content.json.");
    }
}

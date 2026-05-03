<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaItemRequest;
use App\Models\AgendaItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use JsonException;

class AgendaItemController extends Controller
{
    public function index(): View
    {
        $agendaItems = AgendaItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $ppsPath = resource_path('data/pps-content.json');
        $ppsJsonExists = is_readable($ppsPath);

        return view('admin.agenda.index', compact('agendaItems', 'ppsJsonExists'));
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

        return redirect()->route('admin.agenda.index')->with('status', 'Agenda dihapus.');
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

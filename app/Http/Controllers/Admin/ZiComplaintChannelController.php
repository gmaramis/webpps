<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZiComplaintChannelRequest;
use App\Models\ZiComplaintChannel;
use App\Support\AdminRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiComplaintChannelController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_complaint_channels')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $rows = ZiComplaintChannel::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.instrumen-zona-integritas.saluran-index', compact('rows'));
    }

    public function create(): View|RedirectResponse
    {
        if (! Schema::hasTable('zi_complaint_channels')) {
            return redirect()->route('admin.zi.hub')->withErrors(['basisdata' => 'Jalankan migrasi: php artisan migrate']);
        }

        $next = (int) ZiComplaintChannel::query()->max('sort_order') + 1;

        return view('admin.instrumen-zona-integritas.saluran-create', [
            'channel' => new ZiComplaintChannel([
                'sort_order' => $next,
                'is_published' => false,
                'href' => '#',
                'external' => false,
            ]),
        ]);
    }

    public function store(ZiComplaintChannelRequest $request): RedirectResponse
    {
        ZiComplaintChannel::query()->create($request->validated());

        return redirect()->route('admin.zi.saluran.index')->with('status', 'Saluran pengaduan ditambahkan.');
    }

    public function edit(ZiComplaintChannel $channel): View
    {
        return view('admin.instrumen-zona-integritas.saluran-edit', compact('channel'));
    }

    public function update(ZiComplaintChannelRequest $request, ZiComplaintChannel $channel): RedirectResponse
    {
        $channel->update($request->validated());

        return redirect()->route('admin.zi.saluran.index')->with('status', 'Saluran diperbarui.');
    }

    public function destroy(ZiComplaintChannel $channel): RedirectResponse
    {
        $channel->delete();

        return AdminRedirect::toIndexRoute('admin.zi.saluran.index')->with('status', 'Saluran dihapus.');
    }

    public function togglePublish(ZiComplaintChannel $channel): RedirectResponse
    {
        $channel->update(['is_published' => ! $channel->is_published]);

        return AdminRedirect::toIndexRoute('admin.zi.saluran.index')->with('status', $channel->is_published ? 'Saluran ditayangkan.' : 'Saluran disembunyikan dari publik.');
    }
}

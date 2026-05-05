<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StopKorupsiSimplePageUpdateRequest;
use App\Models\StopKorupsiPageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopKorupsiPageContentController extends Controller
{
    public function edit(): View
    {
        $ready = Schema::hasTable('stop_korupsi_page_contents');

        if (! $ready) {
            return view('admin.stop-korupsi.teks-edit', [
                'ready' => false,
                'page' => null,
            ]);
        }

        $page = StopKorupsiPageContent::singleton();

        return view('admin.stop-korupsi.teks-edit', [
            'ready' => true,
            'page' => $page,
        ]);
    }

    public function update(StopKorupsiSimplePageUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new StopKorupsiPageContent)->getTable())) {
            return redirect()->route('admin.stop-korupsi.konten.edit')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $v = $request->validated();
        $page = StopKorupsiPageContent::singleton();

        $nullIfEmpty = static fn (?string $s): ?string => ($s !== null && trim($s) !== '') ? trim($s) : null;

        $page->update([
            'title_id' => trim((string) $v['title_id']),
            'title_en' => $nullIfEmpty($v['title_en'] ?? null),
            'simple_body_id' => trim((string) $v['simple_body_id']),
            'simple_body_en' => $nullIfEmpty($v['simple_body_en'] ?? null),
            'link_span_lapor_url' => $nullIfEmpty($v['link_span_lapor_url'] ?? null),
        ]);

        return redirect()->route('admin.stop-korupsi.konten.edit')->with('status', 'Halaman Stop Korupsi disimpan.');
    }
}

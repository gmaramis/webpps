<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StopGratifikasiSimplePageUpdateRequest;
use App\Models\StopGratifikasiPageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopGratifikasiPageContentController extends Controller
{
    public function edit(): View
    {
        $ready = Schema::hasTable('stop_gratifikasi_page_contents');

        if (! $ready) {
            return view('admin.stop-gratifikasi.teks-edit', [
                'ready' => false,
                'page' => null,
            ]);
        }

        $page = StopGratifikasiPageContent::singleton();

        return view('admin.stop-gratifikasi.teks-edit', [
            'ready' => true,
            'page' => $page,
        ]);
    }

    public function update(StopGratifikasiSimplePageUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new StopGratifikasiPageContent)->getTable())) {
            return redirect()->route('admin.stop-gratifikasi.konten.edit')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $v = $request->validated();
        $page = StopGratifikasiPageContent::singleton();

        $nullIfEmpty = static fn (?string $s): ?string => ($s !== null && trim($s) !== '') ? trim($s) : null;

        $page->update([
            'title_id' => trim((string) $v['title_id']),
            'title_en' => $nullIfEmpty($v['title_en'] ?? null),
            'simple_body_id' => trim((string) $v['simple_body_id']),
            'simple_body_en' => $nullIfEmpty($v['simple_body_en'] ?? null),
            'link_instrumen_zi_url' => $nullIfEmpty($v['link_instrumen_zi_url'] ?? null),
        ]);

        return redirect()->route('admin.stop-gratifikasi.konten.edit')->with('status', 'Halaman Stop Gratifikasi disimpan.');
    }
}

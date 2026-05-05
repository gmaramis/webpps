<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\StopKorupsiContentImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use JsonException;

class StopKorupsiImportController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (! Schema::hasTable('stop_korupsi_page_contents')) {
            return redirect()->route('admin.stop-korupsi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $path = resource_path('data/pps-content.json');
        if (! is_readable($path)) {
            return redirect()->route('admin.stop-korupsi.hub')->withErrors([
                'berkas' => 'pps-content.json tidak ditemukan.',
            ]);
        }

        try {
            StopKorupsiContentImporter::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.stop-korupsi.hub')->withErrors([
                'json' => 'JSON tidak valid: '.$e->getMessage(),
            ]);
        }

        return redirect()->route('admin.stop-korupsi.hub')->with('status', 'Konten Stop Korupsi diimpor ulang dari pps-content.json.');
    }
}

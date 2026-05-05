<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\StopGratifikasiContentImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use JsonException;

class StopGratifikasiImportController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (! Schema::hasTable('stop_gratifikasi_page_contents')) {
            return redirect()->route('admin.stop-gratifikasi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $path = resource_path('data/pps-content.json');
        if (! is_readable($path)) {
            return redirect()->route('admin.stop-gratifikasi.hub')->withErrors([
                'berkas' => 'pps-content.json tidak ditemukan.',
            ]);
        }

        try {
            StopGratifikasiContentImporter::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.stop-gratifikasi.hub')->withErrors([
                'json' => 'JSON tidak valid: '.$e->getMessage(),
            ]);
        }

        return redirect()->route('admin.stop-gratifikasi.hub')->with('status', 'Konten Stop Gratifikasi diimpor ulang dari pps-content.json.');
    }
}

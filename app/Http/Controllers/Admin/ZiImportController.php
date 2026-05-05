<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ZiContentImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use JsonException;

class ZiImportController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (! Schema::hasTable('zi_page_intros')) {
            return redirect()->route('admin.zi.hub')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $path = resource_path('data/pps-content.json');
        if (! is_readable($path)) {
            return redirect()->route('admin.zi.hub')->withErrors([
                'berkas' => 'pps-content.json tidak ditemukan.',
            ]);
        }

        try {
            ZiContentImporter::importFromPpsContentJson();
        } catch (JsonException $e) {
            return redirect()->route('admin.zi.hub')->withErrors([
                'json' => 'JSON tidak valid: '.$e->getMessage(),
            ]);
        }

        return redirect()->route('admin.zi.hub')->with('status', 'Konten Instrumen Zona Integritas diimpor ulang dari pps-content.json.');
    }
}

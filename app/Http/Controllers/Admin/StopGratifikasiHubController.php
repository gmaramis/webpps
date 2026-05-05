<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StopGratifikasiBullet;
use App\Models\StopGratifikasiPageContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopGratifikasiHubController extends Controller
{
    public function __invoke(): View
    {
        $ready = Schema::hasTable('stop_gratifikasi_page_contents');
        $counts = [
            'teks' => $ready && StopGratifikasiPageContent::query()->exists() ? 1 : 0,
            'poin' => $ready ? StopGratifikasiBullet::query()->count() : 0,
        ];
        $ppsJsonExists = is_readable(resource_path('data/pps-content.json'));

        return view('admin.stop-gratifikasi.hub', compact('ready', 'counts', 'ppsJsonExists'));
    }
}

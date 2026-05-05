<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StopKorupsiBullet;
use App\Models\StopKorupsiPageContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StopKorupsiHubController extends Controller
{
    public function __invoke(): View
    {
        $ready = Schema::hasTable('stop_korupsi_page_contents');
        $counts = [
            'teks' => $ready && StopKorupsiPageContent::query()->exists() ? 1 : 0,
            'poin' => $ready ? StopKorupsiBullet::query()->count() : 0,
        ];
        $ppsJsonExists = is_readable(resource_path('data/pps-content.json'));

        return view('admin.stop-korupsi.hub', compact('ready', 'counts', 'ppsJsonExists'));
    }
}

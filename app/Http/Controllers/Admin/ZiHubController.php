<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiComplaintChannel;
use App\Models\ZiGalleryItem;
use App\Models\ZiPageIntro;
use App\Models\ZiPillar;
use App\Models\ZiUpdateItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ZiHubController extends Controller
{
    public function __invoke(): View
    {
        $ready = Schema::hasTable('zi_page_intros');
        $counts = [
            'pengantar' => $ready && ZiPageIntro::query()->exists() ? 1 : 0,
            'pilar' => $ready ? ZiPillar::query()->count() : 0,
            'galeri' => $ready ? ZiGalleryItem::query()->count() : 0,
            'saluran' => $ready ? ZiComplaintChannel::query()->count() : 0,
            'pembaruan' => $ready ? ZiUpdateItem::query()->count() : 0,
        ];
        $ppsJsonExists = is_readable(resource_path('data/pps-content.json'));

        return view('admin.instrumen-zona-integritas.hub', compact('ready', 'counts', 'ppsJsonExists'));
    }
}

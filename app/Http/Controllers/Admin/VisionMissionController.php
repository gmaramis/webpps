<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisionMissionUpdateRequest;
use App\Models\VisionMissionContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class VisionMissionController extends Controller
{
    public function edit(): View
    {
        $table = (new VisionMissionContent)->getTable();
        $visionMissionReady = Schema::hasTable($table);

        if (! $visionMissionReady) {
            return view('admin.vision-mission.edit', [
                'visionMissionReady' => false,
                'content' => null,
                'mission_id_text' => '',
                'mission_en_text' => '',
                'values_id_text' => '',
                'values_en_text' => '',
            ]);
        }

        $content = VisionMissionContent::singleton();

        return view('admin.vision-mission.edit', [
            'visionMissionReady' => true,
            'content' => $content,
            'mission_id_text' => implode("\n", VisionMissionContent::normalizeLines($content->mission_id)),
            'mission_en_text' => implode("\n", VisionMissionContent::normalizeLines($content->mission_en)),
            'values_id_text' => implode("\n", VisionMissionContent::normalizeLines($content->values_id)),
            'values_en_text' => implode("\n", VisionMissionContent::normalizeLines($content->values_en)),
        ]);
    }

    public function update(VisionMissionUpdateRequest $request): RedirectResponse
    {
        if (! Schema::hasTable((new VisionMissionContent)->getTable())) {
            return redirect()->route('admin.visi-misi.edit')->withErrors([
                'basisdata' => 'Basis data belum lengkap — jalankan: php artisan migrate',
            ]);
        }

        $content = VisionMissionContent::singleton();

        $v = $request->validated();
        $visionEn = trim((string) ($v['vision_en'] ?? ''));

        $content->update([
            'vision_id' => $v['vision_id'],
            'vision_en' => $visionEn !== '' ? $visionEn : null,
            'mission_id' => VisionMissionContent::linesFromTextarea($v['mission_id']),
            'mission_en' => VisionMissionContent::linesFromTextarea((string) ($v['mission_en'] ?? '')),
            'values_id' => VisionMissionContent::linesFromTextarea($v['values_id']),
            'values_en' => VisionMissionContent::linesFromTextarea((string) ($v['values_en'] ?? '')),
        ]);

        return redirect()->route('admin.visi-misi.edit')->with('status', 'Konten Visi & Misi berhasil disimpan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GraduateSchoolHistoryUpdateRequest;
use App\Models\GraduateSchoolHistoryContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class GraduateSchoolHistoryController extends Controller
{
    public function edit(): View
    {
        $table = (new GraduateSchoolHistoryContent)->getTable();
        $ready = Schema::hasTable($table) && Schema::hasColumn($table, 'paragraph_id');

        if (! $ready) {
            return view('admin.graduate-school-history.edit', [
                'ready' => false,
                'content' => null,
                'imageColumnReady' => false,
            ]);
        }

        $content = GraduateSchoolHistoryContent::singleton();

        return view('admin.graduate-school-history.edit', [
            'ready' => true,
            'content' => $content,
            'imageColumnReady' => Schema::hasColumn($table, 'image_path'),
        ]);
    }

    public function update(GraduateSchoolHistoryUpdateRequest $request): RedirectResponse
    {
        $table = (new GraduateSchoolHistoryContent)->getTable();
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'paragraph_id')) {
            return redirect()->route('admin.beranda-sejarah.edit')->withErrors([
                'basisdata' => 'Basis data belum lengkap — jalankan: php artisan migrate',
            ]);
        }

        $content = GraduateSchoolHistoryContent::singleton();
        $v = $request->validated();

        $nullIfEmpty = static fn (?string $s): ?string => ($s !== null && trim($s) !== '') ? trim($s) : null;

        $payload = [
            'eyebrow_id' => trim((string) $v['eyebrow_id']),
            'eyebrow_en' => $nullIfEmpty($v['eyebrow_en'] ?? null),
            'title_id' => trim((string) $v['title_id']),
            'title_en' => $nullIfEmpty($v['title_en'] ?? null),
            'paragraph_id' => trim((string) $v['paragraph_id']),
            'paragraph_en' => $nullIfEmpty($v['paragraph_en'] ?? null),
        ];

        if (Schema::hasColumn($table, 'image_path')) {
            $existing = trim((string) ($content->image_path ?? ''));
            $imagePath = $existing;

            if ($request->boolean('remove_image')) {
                GraduateSchoolHistoryContent::deleteStoredImage($existing !== '' ? $existing : null);
                $imagePath = '';
            }
            if ($request->hasFile('image')) {
                GraduateSchoolHistoryContent::deleteStoredImage($imagePath !== '' ? $imagePath : null);
                $imagePath = $request->file('image')->store('graduate-school-history', 'public');
            }

            $payload['image_path'] = $imagePath !== '' ? $imagePath : null;
        }

        $content->update($payload);

        return redirect()->route('admin.beranda-sejarah.edit')->with('status', 'Konten sejarah beranda berhasil disimpan.');
    }
}

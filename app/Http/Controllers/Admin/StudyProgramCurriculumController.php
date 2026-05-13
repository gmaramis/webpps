<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyProgramCurriculumPdfRequest;
use App\Models\S2Program;
use App\Models\S3Program;
use App\Models\StudyProgramCurriculum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudyProgramCurriculumController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Schema::hasTable((new StudyProgramCurriculum)->getTable())) {
            return redirect()->route('admin.dashboard')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        $s2Programs = S2Program::query()
            ->with('studyProgramCurriculum')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $s3Programs = S3Program::query()
            ->with('studyProgramCurriculum')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.kurikulum.index', compact('s2Programs', 's3Programs'));
    }

    public function updateMagister(StudyProgramCurriculumPdfRequest $request, S2Program $s2Program): RedirectResponse
    {
        $path = $request->file('pdf')->store('curriculum-pdfs', 'public');

        $row = StudyProgramCurriculum::query()->firstOrNew(['s2_program_id' => $s2Program->getKey()]);
        if ($row->exists && $row->pdf_path) {
            StudyProgramCurriculum::deleteStoredPdf($row->pdf_path);
        }
        $row->s2_program_id = $s2Program->getKey();
        $row->s3_program_id = null;
        $row->pdf_path = $path;
        $row->save();

        return redirect()->route('admin.kurikulum.index')->with('status', 'Kurikulum magister diperbarui.');
    }

    public function updateDoktor(StudyProgramCurriculumPdfRequest $request, S3Program $s3Program): RedirectResponse
    {
        $path = $request->file('pdf')->store('curriculum-pdfs', 'public');

        $row = StudyProgramCurriculum::query()->firstOrNew(['s3_program_id' => $s3Program->getKey()]);
        if ($row->exists && $row->pdf_path) {
            StudyProgramCurriculum::deleteStoredPdf($row->pdf_path);
        }
        $row->s3_program_id = $s3Program->getKey();
        $row->s2_program_id = null;
        $row->pdf_path = $path;
        $row->save();

        return redirect()->route('admin.kurikulum.index')->with('status', 'Kurikulum doktor diperbarui.');
    }

    public function destroyMagister(S2Program $s2Program): RedirectResponse
    {
        $row = StudyProgramCurriculum::query()->where('s2_program_id', $s2Program->getKey())->first();
        if ($row !== null) {
            $row->delete();
        }

        return redirect()->route('admin.kurikulum.index')->with('status', 'Berkas kurikulum magister dihapus.');
    }

    public function destroyDoktor(S3Program $s3Program): RedirectResponse
    {
        $row = StudyProgramCurriculum::query()->where('s3_program_id', $s3Program->getKey())->first();
        if ($row !== null) {
            $row->delete();
        }

        return redirect()->route('admin.kurikulum.index')->with('status', 'Berkas kurikulum doktor dihapus.');
    }
}

<?php

use App\Models\AcademicGuide;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_guides', function (Blueprint $table): void {
            $table->boolean('show_on_academic_calendar')->default(false)->after('file_path');
        });

        if (! Schema::hasTable('academic_guides')) {
            return;
        }

        foreach (DB::table('academic_guides')->get(['id', 'file_path', 'name_id', 'name_en']) as $row) {
            $payload = [
                'file' => (string) $row->file_path,
                'name' => [
                    'id' => (string) $row->name_id,
                    'en' => (string) ($row->name_en ?? ''),
                ],
            ];
            if (AcademicGuide::inferCalendarFromLegacyGuideRow($payload)) {
                DB::table('academic_guides')->where('id', $row->id)->update(['show_on_academic_calendar' => true]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('academic_guides', function (Blueprint $table): void {
            $table->dropColumn('show_on_academic_calendar');
        });
    }
};

<?php

use App\Models\GraduateSchoolHistoryContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = 'graduate_school_history_contents';
        if (! Schema::hasTable($table)) {
            return;
        }
        if (Schema::hasColumn($table, 'paragraph_id')) {
            return;
        }
        if (! Schema::hasColumn($table, 'lead_id')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->text('paragraph_id')->nullable();
            $blueprint->text('paragraph_en')->nullable();
        });

        foreach (DB::table($table)->get() as $row) {
            $pid = $this->legacyParagraph($row, 'id');
            $pen = $this->legacyParagraph($row, 'en');
            DB::table($table)->where('id', $row->id)->update([
                'paragraph_id' => $pid !== '' ? $pid : GraduateSchoolHistoryContent::defaultPayload()['paragraph_id'],
                'paragraph_en' => $pen !== '' ? $pen : null,
                'updated_at' => now(),
            ]);
        }

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->dropColumn([
                'lead_id',
                'lead_en',
                'p1_id',
                'p1_en',
                'p2_id',
                'p2_en',
                'milestones_title_id',
                'milestones_title_en',
                'milestone1_id',
                'milestone1_en',
                'milestone2_id',
                'milestone2_en',
                'milestone3_id',
                'milestone3_en',
                'milestone4_id',
                'milestone4_en',
            ]);
        });
    }

    /**
     * @param  object{lead_id?: string|null, lead_en?: string|null, p1_id?: string|null, p1_en?: string|null, p2_id?: string|null, p2_en?: string|null, milestones_title_id?: string|null, milestones_title_en?: string|null, milestone1_id?: string|null, milestone1_en?: string|null, milestone2_id?: string|null, milestone2_en?: string|null, milestone3_id?: string|null, milestone3_en?: string|null, milestone4_id?: string|null, milestone4_en?: string|null}  $row
     */
    private function legacyParagraph(object $row, string $locale): string
    {
        $s = $locale === 'en' ? '_en' : '_id';
        $g = static function (string $base) use ($row, $s): string {
            $k = $base.$s;

            return trim((string) ($row->{$k} ?? ''));
        };

        $parts = array_filter([
            $g('lead'),
            $g('p1'),
            $g('p2'),
        ]);

        $mTitle = $g('milestones_title');
        $milestones = array_filter([
            $g('milestone1'),
            $g('milestone2'),
            $g('milestone3'),
            $g('milestone4'),
        ]);

        if ($mTitle !== '' || $milestones !== []) {
            $lines = [];
            if ($mTitle !== '') {
                $lines[] = $mTitle;
            }
            foreach ($milestones as $m) {
                $lines[] = '• '.$m;
            }
            if ($lines !== []) {
                $parts[] = implode("\n", $lines);
            }
        }

        return implode("\n\n", $parts);
    }

    public function down(): void
    {
        //
    }
};

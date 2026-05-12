<?php

use App\Models\GraduateSchoolHistoryContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduate_school_history_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('eyebrow_id', 255);
            $table->string('eyebrow_en', 255)->nullable();
            $table->string('title_id', 500);
            $table->string('title_en', 500)->nullable();
            $table->text('paragraph_id');
            $table->text('paragraph_en')->nullable();
            $table->timestamps();
        });

        $now = now();
        /** @var array<string, string|null> $row */
        $row = GraduateSchoolHistoryContent::defaultPayload();

        $path = resource_path('data/pps-content.json');
        if (File::exists($path)) {
            try {
                $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
                if (is_array($data)) {
                    $sid = is_array($data['STRINGS']['id'] ?? null) ? $data['STRINGS']['id'] : [];
                    $sen = is_array($data['STRINGS']['en'] ?? null) ? $data['STRINGS']['en'] : [];
                    foreach ([
                        'eyebrow_id' => 'historyEyebrow',
                        'eyebrow_en' => 'historyEyebrow',
                        'title_id' => 'historyTitle',
                        'title_en' => 'historyTitle',
                    ] as $col => $strKey) {
                        $isEn = str_ends_with($col, '_en');
                        $src = $isEn ? $sen : $sid;
                        if (! isset($src[$strKey]) || ! is_string($src[$strKey])) {
                            continue;
                        }
                        $v = trim($src[$strKey]);
                        if ($v !== '') {
                            $row[$col] = $v;
                        }
                    }

                    $pid = isset($sid['historyParagraph']) && is_string($sid['historyParagraph']) && trim($sid['historyParagraph']) !== ''
                        ? trim($sid['historyParagraph'])
                        : $this->paragraphFromLegacyStrings($sid);
                    if ($pid !== '') {
                        $row['paragraph_id'] = $pid;
                    }

                    $pen = isset($sen['historyParagraph']) && is_string($sen['historyParagraph']) && trim($sen['historyParagraph']) !== ''
                        ? trim($sen['historyParagraph'])
                        : $this->paragraphFromLegacyStrings($sen);
                    $row['paragraph_en'] = $pen !== '' ? $pen : null;
                }
            } catch (JsonException|Throwable) {
                //
            }
        }

        $nullIfEmpty = static fn (?string $s): ?string => ($s !== null && trim($s) !== '') ? trim($s) : null;

        DB::table('graduate_school_history_contents')->insert([
            'id' => 1,
            'eyebrow_id' => (string) $row['eyebrow_id'],
            'eyebrow_en' => $nullIfEmpty($row['eyebrow_en'] ?? null),
            'title_id' => (string) $row['title_id'],
            'title_en' => $nullIfEmpty($row['title_en'] ?? null),
            'paragraph_id' => (string) $row['paragraph_id'],
            'paragraph_en' => $nullIfEmpty($row['paragraph_en'] ?? null),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * @param  array<string, mixed>  $strings
     */
    private function paragraphFromLegacyStrings(array $strings): string
    {
        $parts = array_filter([
            trim((string) ($strings['historyLead'] ?? '')),
            trim((string) ($strings['historyP1'] ?? '')),
            trim((string) ($strings['historyP2'] ?? '')),
        ]);
        $mTitle = trim((string) ($strings['historyMilestonesTitle'] ?? ''));
        $milestones = array_filter([
            trim((string) ($strings['historyMilestone1'] ?? '')),
            trim((string) ($strings['historyMilestone2'] ?? '')),
            trim((string) ($strings['historyMilestone3'] ?? '')),
            trim((string) ($strings['historyMilestone4'] ?? '')),
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
        Schema::dropIfExists('graduate_school_history_contents');
    }
};

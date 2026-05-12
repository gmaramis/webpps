<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('director_greetings', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path')->nullable();
            $table->string('name_id')->nullable();
            $table->string('name_en')->nullable();
            $table->string('role_id')->nullable();
            $table->string('role_en')->nullable();
            $table->string('section_eyebrow_id')->nullable();
            $table->string('section_eyebrow_en')->nullable();
            $table->string('section_title_id')->nullable();
            $table->string('section_title_en')->nullable();
            $table->string('section_quote_label_id')->nullable();
            $table->string('section_quote_label_en')->nullable();
            $table->json('paragraphs')->nullable();
            $table->timestamps();
        });

        $now = now();
        $row = [
            'id' => 1,
            'photo_path' => null,
            'name_id' => null,
            'name_en' => null,
            'role_id' => null,
            'role_en' => null,
            'section_eyebrow_id' => null,
            'section_eyebrow_en' => null,
            'section_title_id' => null,
            'section_title_en' => null,
            'section_quote_label_id' => null,
            'section_quote_label_en' => null,
            'paragraphs' => json_encode([], JSON_THROW_ON_ERROR),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $path = resource_path('data/pps-content.json');
        if (File::exists($path)) {
            try {
                $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
                if (is_array($data)) {
                    $dg = $data['DIRECTOR_GREETING'] ?? [];
                    $sid = is_array($data['STRINGS']['id'] ?? null) ? $data['STRINGS']['id'] : [];
                    $sen = is_array($data['STRINGS']['en'] ?? null) ? $data['STRINGS']['en'] : [];
                    $name = is_array($dg['name'] ?? null) ? $dg['name'] : [];
                    $role = is_array($dg['role'] ?? null) ? $dg['role'] : [];
                    $paras = $dg['paragraphs'] ?? [];
                    if (! is_array($paras)) {
                        $paras = [];
                    }
                    $photo = isset($dg['photo']) && is_string($dg['photo']) ? trim($dg['photo']) : null;
                    $row['photo_path'] = $photo !== '' ? $photo : null;
                    $row['name_id'] = isset($name['id']) && is_string($name['id']) ? $name['id'] : null;
                    $row['name_en'] = isset($name['en']) && is_string($name['en']) ? $name['en'] : null;
                    $row['role_id'] = isset($role['id']) && is_string($role['id']) ? $role['id'] : null;
                    $row['role_en'] = isset($role['en']) && is_string($role['en']) ? $role['en'] : null;
                    $row['section_eyebrow_id'] = isset($sid['directorGreetingEyebrow']) ? (string) $sid['directorGreetingEyebrow'] : null;
                    $row['section_eyebrow_en'] = isset($sen['directorGreetingEyebrow']) ? (string) $sen['directorGreetingEyebrow'] : null;
                    $row['section_title_id'] = isset($sid['directorGreetingTitle']) ? (string) $sid['directorGreetingTitle'] : null;
                    $row['section_title_en'] = isset($sen['directorGreetingTitle']) ? (string) $sen['directorGreetingTitle'] : null;
                    $row['section_quote_label_id'] = isset($sid['directorGreetingQuoteLabel']) ? (string) $sid['directorGreetingQuoteLabel'] : null;
                    $row['section_quote_label_en'] = isset($sen['directorGreetingQuoteLabel']) ? (string) $sen['directorGreetingQuoteLabel'] : null;
                    $row['paragraphs'] = json_encode($paras, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            } catch (\JsonException|\Throwable) {
                // tetap pakai baris default kosong
            }
        }

        DB::table('director_greetings')->insert($row);
    }

    public function down(): void
    {
        Schema::dropIfExists('director_greetings');
    }
};

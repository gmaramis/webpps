<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_portal_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('portal_url', 2048);
            $table->string('lms_url', 2048);
            $table->string('spada_url', 2048);
            $table->timestamps();
        });

        DB::table('academic_portal_settings')->insert([
            'portal_url' => 'https://si.unima.ac.id/gtakademik_portal/',
            'lms_url' => 'https://lms.unima.ac.id/',
            'spada_url' => 'https://spada.kemdiktisaintek.go.id',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_portal_settings');
    }
};

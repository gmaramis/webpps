<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_program_displays', function (Blueprint $table): void {
            $table->id();
            $table->string('magister_hero_path', 512)->nullable();
            $table->string('doktor_hero_path', 512)->nullable();
            $table->timestamps();
        });

        DB::table('homepage_program_displays')->insert([
            'id' => 1,
            'magister_hero_path' => null,
            'doktor_hero_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_program_displays');
    }
};

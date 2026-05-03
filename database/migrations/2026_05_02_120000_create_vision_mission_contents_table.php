<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vision_mission_contents', function (Blueprint $table): void {
            $table->id();
            $table->text('vision_id');
            $table->text('vision_en')->nullable();
            $table->json('mission_id');
            $table->json('mission_en')->nullable();
            $table->json('values_id');
            $table->json('values_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vision_mission_contents');
    }
};

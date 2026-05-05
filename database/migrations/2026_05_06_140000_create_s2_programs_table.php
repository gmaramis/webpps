<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('s2_programs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('slug')->unique();
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->text('blurb_id');
            $table->text('blurb_en')->nullable();
            $table->string('official_url', 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('s2_programs');
    }
};

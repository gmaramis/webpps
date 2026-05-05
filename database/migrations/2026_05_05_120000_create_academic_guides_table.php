<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_guides', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_guides');
    }
};

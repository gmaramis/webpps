<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_program_curricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('s2_program_id')->nullable()->unique()->constrained('s2_programs')->cascadeOnDelete();
            $table->foreignId('s3_program_id')->nullable()->unique()->constrained('s3_programs')->cascadeOnDelete();
            $table->string('pdf_path', 512)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_program_curricula');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->string('nidn', 32)->nullable();
            $table->string('nip', 128)->nullable();
            $table->string('study_program_id', 255);
            $table->string('study_program_en', 255)->nullable();
            $table->string('functional_role_id', 191);
            $table->string('functional_role_en', 191)->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};

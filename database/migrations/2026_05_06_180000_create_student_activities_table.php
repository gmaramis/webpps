<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_activities', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('image');
            $table->string('image_alt_id');
            $table->string('image_alt_en')->nullable();
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->text('excerpt_id');
            $table->text('excerpt_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_activities');
    }
};

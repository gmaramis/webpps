<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_items', function (Blueprint $table) {
            $table->id();
            $table->date('published_at');
            $table->string('title_id');
            $table->string('title_en');
            $table->text('excerpt_id')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->string('href', 512)->default('#');
            $table->string('image_path', 512)->nullable();
            $table->string('category_id', 120)->nullable();
            $table->string('category_en', 120)->nullable();
            $table->string('location_id', 255)->nullable();
            $table->string('location_en', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_items');
    }
};

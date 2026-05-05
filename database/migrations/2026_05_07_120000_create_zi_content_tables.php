<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zi_page_intros', function (Blueprint $table): void {
            $table->id();
            $table->string('intro_heading_id');
            $table->string('intro_heading_en')->nullable();
            $table->text('intro_p1_id');
            $table->text('intro_p1_en')->nullable();
            $table->text('intro_p2_id');
            $table->text('intro_p2_en')->nullable();
            $table->timestamps();
        });

        Schema::create('zi_pillars', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->text('desc_id');
            $table->text('desc_en')->nullable();
            $table->timestamps();
        });

        Schema::create('zi_gallery_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->string('image');
            $table->string('image_alt_id');
            $table->string('image_alt_en')->nullable();
            $table->string('caption_id');
            $table->string('caption_en')->nullable();
            $table->timestamps();
        });

        Schema::create('zi_complaint_channels', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->text('summary_id');
            $table->text('summary_en')->nullable();
            $table->string('href', 2048);
            $table->boolean('external')->default(false);
            $table->timestamps();
        });

        Schema::create('zi_update_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->date('date_iso');
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->string('href', 2048);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zi_update_items');
        Schema::dropIfExists('zi_complaint_channels');
        Schema::dropIfExists('zi_gallery_items');
        Schema::dropIfExists('zi_pillars');
        Schema::dropIfExists('zi_page_intros');
    }
};

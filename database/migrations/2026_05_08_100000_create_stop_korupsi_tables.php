<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stop_korupsi_page_contents', function (Blueprint $table): void {
            $table->id();
            $table->text('eyebrow_id')->nullable();
            $table->text('eyebrow_en')->nullable();
            $table->text('title_id')->nullable();
            $table->text('title_en')->nullable();
            $table->text('lead_id')->nullable();
            $table->text('lead_en')->nullable();
            $table->text('p1_id')->nullable();
            $table->text('p1_en')->nullable();
            $table->text('p2_id')->nullable();
            $table->text('p2_en')->nullable();
            $table->text('bullets_title_id')->nullable();
            $table->text('bullets_title_en')->nullable();
            $table->text('cta_title_id')->nullable();
            $table->text('cta_title_en')->nullable();
            $table->text('cta_p_id')->nullable();
            $table->text('cta_p_en')->nullable();
            $table->text('link_instrumen_zi_label_id')->nullable();
            $table->text('link_instrumen_zi_label_en')->nullable();
            $table->text('link_span_lapor_label_id')->nullable();
            $table->text('link_span_lapor_label_en')->nullable();
            $table->string('link_span_lapor_url', 2048)->nullable();
            $table->longText('simple_body_id')->nullable();
            $table->longText('simple_body_en')->nullable();
            $table->timestamps();
        });

        Schema::create('stop_korupsi_bullets', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('text_id');
            $table->text('text_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stop_korupsi_bullets');
        Schema::dropIfExists('stop_korupsi_page_contents');
    }
};

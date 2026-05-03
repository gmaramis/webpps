<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('day', 4);
            $table->string('month_id', 20);
            $table->string('month_en', 20)->nullable();
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->string('href', 500)->default('#');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_items');
    }
};


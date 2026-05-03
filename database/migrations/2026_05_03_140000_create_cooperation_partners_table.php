<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperation_partners', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->string('cooperation_id');
            $table->string('cooperation_en')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperation_partners');
    }
};

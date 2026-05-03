<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadership_people', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name');
            $table->string('nip', 128)->nullable();
            $table->string('photo')->nullable();
            $table->string('role_id');
            $table->string('role_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_people');
    }
};

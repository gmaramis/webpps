<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lecturers', function (Blueprint $table): void {
            $table->string('email', 255)->nullable()->after('phone');
            $table->string('google_scholar_url', 512)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table): void {
            $table->dropColumn(['email', 'google_scholar_url']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('s2_programs', function (Blueprint $table): void {
            $table->text('excerpt_id')->nullable()->after('blurb_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
        });

        Schema::table('s3_programs', function (Blueprint $table): void {
            $table->text('excerpt_id')->nullable()->after('blurb_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
        });
    }

    public function down(): void
    {
        Schema::table('s2_programs', function (Blueprint $table): void {
            $table->dropColumn(['excerpt_id', 'excerpt_en']);
        });

        Schema::table('s3_programs', function (Blueprint $table): void {
            $table->dropColumn(['excerpt_id', 'excerpt_en']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('s2_programs', function (Blueprint $table): void {
            $table->string('brochure_image')->nullable()->after('official_url');
        });

        Schema::table('s3_programs', function (Blueprint $table): void {
            $table->string('brochure_image')->nullable()->after('official_url');
        });
    }

    public function down(): void
    {
        Schema::table('s2_programs', function (Blueprint $table): void {
            $table->dropColumn('brochure_image');
        });

        Schema::table('s3_programs', function (Blueprint $table): void {
            $table->dropColumn('brochure_image');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = 'graduate_school_history_contents';
        if (! Schema::hasTable($table)) {
            return;
        }
        if (Schema::hasColumn($table, 'image_path')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->string('image_path')->nullable()->after('paragraph_en');
        });
    }

    public function down(): void
    {
        $table = 'graduate_school_history_contents';
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'image_path')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->dropColumn('image_path');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcement_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('announcement_items', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('sort_order');
            }
        });
        DB::table('announcement_items')->update(['is_published' => true]);

        Schema::table('agenda_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('agenda_items', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('sort_order');
            }
        });
        DB::table('agenda_items')->update(['is_published' => true]);
    }

    public function down(): void
    {
        Schema::table('announcement_items', function (Blueprint $table): void {
            if (Schema::hasColumn('announcement_items', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });

        Schema::table('agenda_items', function (Blueprint $table): void {
            if (Schema::hasColumn('agenda_items', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};

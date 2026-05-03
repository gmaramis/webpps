<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('is_published');
        });

        if (Schema::hasColumn('news_items', 'published_at')) {
            DB::table('news_items')->where('is_published', true)->update([
                'published_at' => DB::raw('created_at'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};

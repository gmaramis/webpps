<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('id');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->date('published_at')->nullable()->after('id');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }
};

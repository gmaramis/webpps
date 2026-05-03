<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('author', 191)->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
};

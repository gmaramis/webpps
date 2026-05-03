<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('translation_status', 32)->default('idle')->after('meta_description');
            $table->text('translation_error')->nullable()->after('translation_status');
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn(['translation_status', 'translation_error']);
        });
    }
};

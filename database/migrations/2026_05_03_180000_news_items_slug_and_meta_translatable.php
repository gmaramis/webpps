<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->json('slug')->nullable()->after('location');
            $table->json('meta_title')->nullable()->after('slug');
            $table->json('meta_description')->nullable()->after('meta_title');
        });

        foreach (DB::table('news_items')->cursor() as $row) {
            $title = json_decode($row->title ?? '{}', true) ?: [];
            $baseId = Str::slug(Str::limit((string) ($title['id'] ?? 'berita'), 80, '')) ?: 'berita';
            $baseEn = Str::slug(Str::limit((string) ($title['en'] ?? 'news'), 80, '')) ?: 'news';
            DB::table('news_items')->where('id', $row->id)->update([
                'slug' => json_encode([
                    'id' => $baseId.'-'.$row->id,
                    'en' => $baseEn.'-'.$row->id,
                ], JSON_THROW_ON_ERROR),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });
    }
};

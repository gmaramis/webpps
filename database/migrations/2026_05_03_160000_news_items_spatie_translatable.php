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
            $table->json('title')->nullable()->after('published_at');
            $table->json('excerpt')->nullable()->after('title');
            $table->json('body')->nullable()->after('excerpt');
            $table->json('category')->nullable()->after('body');
            $table->json('location')->nullable()->after('category');
        });

        foreach (DB::table('news_items')->cursor() as $row) {
            DB::table('news_items')->where('id', $row->id)->update([
                'title' => json_encode([
                    'id' => $row->title_id ?? '',
                    'en' => $row->title_en ?? '',
                ], JSON_THROW_ON_ERROR),
                'excerpt' => json_encode([
                    'id' => $row->excerpt_id ?? '',
                    'en' => $row->excerpt_en ?? '',
                ], JSON_THROW_ON_ERROR),
                'body' => json_encode([
                    'id' => $row->body_id ?? '',
                    'en' => $row->body_en ?? '',
                ], JSON_THROW_ON_ERROR),
                'category' => json_encode([
                    'id' => $row->category_id ?? '',
                    'en' => $row->category_en ?? '',
                ], JSON_THROW_ON_ERROR),
                'location' => json_encode([
                    'id' => $row->location_id ?? '',
                    'en' => $row->location_en ?? '',
                ], JSON_THROW_ON_ERROR),
            ]);
        }

        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn([
                'title_id',
                'title_en',
                'excerpt_id',
                'excerpt_en',
                'body_id',
                'body_en',
                'category_id',
                'category_en',
                'location_id',
                'location_en',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('title_id')->nullable()->after('published_at');
            $table->string('title_en')->nullable();
            $table->text('excerpt_id')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('body_id')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('category_id', 120)->nullable();
            $table->string('category_en', 120)->nullable();
            $table->string('location_id', 255)->nullable();
            $table->string('location_en', 255)->nullable();
        });

        foreach (DB::table('news_items')->cursor() as $row) {
            $title = json_decode($row->title ?? '{}', true) ?: [];
            $excerpt = json_decode($row->excerpt ?? '{}', true) ?: [];
            $body = json_decode($row->body ?? '{}', true) ?: [];
            $category = json_decode($row->category ?? '{}', true) ?: [];
            $location = json_decode($row->location ?? '{}', true) ?: [];

            DB::table('news_items')->where('id', $row->id)->update([
                'title_id' => $title['id'] ?? null,
                'title_en' => $title['en'] ?? null,
                'excerpt_id' => $excerpt['id'] ?? null,
                'excerpt_en' => $excerpt['en'] ?? null,
                'body_id' => $body['id'] ?? null,
                'body_en' => $body['en'] ?? null,
                'category_id' => $category['id'] ?? null,
                'category_en' => $category['en'] ?? null,
                'location_id' => $location['id'] ?? null,
                'location_en' => $location['en'] ?? null,
            ]);
        }

        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'body', 'category', 'location']);
        });
    }
};

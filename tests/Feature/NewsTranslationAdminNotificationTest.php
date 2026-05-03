<?php

namespace Tests\Feature;

use App\Models\NewsItem;
use App\Models\User;
use App\Support\AdminNewsTranslationNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTranslationAdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifier_creates_one_database_notification_per_user(): void
    {
        User::factory()->count(2)->create();

        $news = NewsItem::query()->create([
            'is_published' => false,
            'href' => '#',
            'title' => ['id' => 'Judul uji', 'en' => ''],
            'excerpt' => ['id' => 'Ringkasan', 'en' => ''],
            'body' => ['id' => '<p>Isi</p>', 'en' => ''],
            'category' => ['id' => '', 'en' => ''],
            'location' => ['id' => '', 'en' => ''],
            'translation_status' => 'idle',
            'translation_error' => null,
        ]);

        AdminNewsTranslationNotifier::notify($news, 'ready_for_review');

        $this->assertDatabaseCount('notifications', 2);
    }

    public function test_exclude_user_skips_their_notification(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $news = NewsItem::query()->create([
            'is_published' => false,
            'href' => '#',
            'title' => ['id' => 'Judul', 'en' => ''],
            'excerpt' => ['id' => 'X', 'en' => ''],
            'body' => ['id' => '<p>Y</p>', 'en' => ''],
            'category' => ['id' => '', 'en' => ''],
            'location' => ['id' => '', 'en' => ''],
            'translation_status' => 'idle',
            'translation_error' => null,
        ]);

        AdminNewsTranslationNotifier::notify($news, 'ready_for_review', excludeUserId: $alice->id);

        $this->assertSame(0, $alice->notifications()->count());
        $this->assertSame(1, $bob->notifications()->count());
    }

    public function test_mark_read_redirects_to_news_edit(): void
    {
        $user = User::factory()->create();

        $news = NewsItem::query()->create([
            'is_published' => false,
            'href' => '#',
            'title' => ['id' => 'Judul', 'en' => ''],
            'excerpt' => ['id' => 'X', 'en' => ''],
            'body' => ['id' => '<p>Y</p>', 'en' => ''],
            'category' => ['id' => '', 'en' => ''],
            'location' => ['id' => '', 'en' => ''],
            'translation_status' => 'idle',
            'translation_error' => null,
        ]);

        AdminNewsTranslationNotifier::notify($news, 'ready_for_review');

        $note = $user->notifications()->firstOrFail();

        $response = $this->actingAs($user)->post(route('admin.notifications.read', $note->id));

        $response->assertRedirect(route('admin.news.edit', $news));
        $this->assertNotNull($note->fresh()->read_at);
    }
}

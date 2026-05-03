<?php

namespace App\Support;

use App\Models\NewsItem;
use App\Models\User;
use App\Notifications\NewsTranslationAdminNotification;
use Illuminate\Support\Str;

final class AdminNewsTranslationNotifier
{
    /**
     * @param  'ready_for_review'|'failed'  $kind
     */
    public static function notify(NewsItem $news, string $kind, ?string $errorMessage = null, ?int $excludeUserId = null): void
    {
        $title = Str::limit((string) $news->getTranslationWithoutFallback('title', 'id'), 100, '…');

        User::query()
            ->when($excludeUserId !== null, fn ($q) => $q->where('id', '!=', $excludeUserId))
            ->cursor()
            ->each(function (User $user) use ($news, $kind, $title, $errorMessage): void {
                $user->notify(new NewsTranslationAdminNotification(
                    $news->getKey(),
                    $kind,
                    $title,
                    $errorMessage,
                ));
            });
    }
}

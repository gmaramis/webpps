<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\NewsTranslationAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class AdminNotificationController extends Controller
{
    public function markRead(Request $request, string $notification): RedirectResponse
    {
        /** @var DatabaseNotification $row */
        $row = $request->user()->notifications()
            ->whereKey($notification)
            ->where('type', NewsTranslationAdminNotification::class)
            ->firstOrFail();

        if ($row->read_at === null) {
            $row->markAsRead();
        }

        $newsId = $row->data['news_id'] ?? null;
        if (is_int($newsId) || (is_string($newsId) && ctype_digit($newsId))) {
            return redirect()->route('admin.news.edit', (int) $newsId);
        }

        return redirect()->route('admin.dashboard');
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()
            ->where('type', NewsTranslationAdminNotification::class)
            ->get()
            ->each->markAsRead();

        return redirect()->back();
    }
}

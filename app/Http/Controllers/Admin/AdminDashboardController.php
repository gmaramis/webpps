<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use App\Notifications\NewsTranslationAdminNotification;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $translationType = NewsTranslationAdminNotification::class;
        $translationNotifyUnread = $user->unreadNotifications()->where('type', $translationType)->count();
        $translationNotifyRecent = $user->notifications()->where('type', $translationType)->latest()->take(8)->get();

        $newsStats = [
            'total' => NewsItem::query()->count(),
            'published' => NewsItem::query()->where('is_published', true)->count(),
            'draft' => NewsItem::query()->where('is_published', false)->count(),
        ];

        $recentNews = NewsItem::query()
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'translationNotifyUnread',
            'translationNotifyRecent',
            'newsStats',
            'recentNews'
        ));
    }
}

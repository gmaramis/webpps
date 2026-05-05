<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaItem;
use App\Models\AnnouncementItem;
use App\Models\NewsItem;
use App\Models\User;
use App\Notifications\NewsTranslationAdminNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user === null) {
            abort(403);
        }
        $translationType = NewsTranslationAdminNotification::class;
        $translationNotifyUnread = $user->unreadNotifications()->where('type', $translationType)->count();
        $translationNotifyRecent = $user->notifications()->where('type', $translationType)->latest()->take(8)->get();

        $newsStats = [
            'total' => NewsItem::query()->count(),
            'published' => NewsItem::query()->where('is_published', true)->count(),
            'draft' => NewsItem::query()->where('is_published', false)->count(),
        ];
        $now = Carbon::now();
        $monthIdMap = [1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGS', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'];
        $currentMonthId = $monthIdMap[$now->month] ?? 'JAN';

        $announcementMonthlyPublished = AnnouncementItem::query()
            ->where('is_published', true)
            ->whereYear('date_iso', $now->year)
            ->whereMonth('date_iso', $now->month)
            ->count();

        $agendaMonthlyPublished = AgendaItem::query()
            ->where('is_published', true)
            ->where('month_id', $currentMonthId)
            ->count();

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
            'recentNews',
            'announcementMonthlyPublished',
            'agendaMonthlyPublished'
        ));
    }
}

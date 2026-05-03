<?php

namespace App\Providers;

use App\Models\NewsItem;
use App\Support\PpsContent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('*', function ($view) {
            $data = PpsContent::all();
            $locale = app()->getLocale();
            $t = $data['STRINGS'][$locale] ?? $data['STRINGS']['id'] ?? [];
            $view->with('ppsData', $data)->with('t', $t);
        });

        View::composer('admin.layouts.app', function ($view): void {
            $view->with('adminSidebarNewsCounts', [
                'total' => NewsItem::query()->count(),
                'published' => NewsItem::query()->where('is_published', true)->count(),
                'draft' => NewsItem::query()->where('is_published', false)->count(),
            ]);
        });
    }
}

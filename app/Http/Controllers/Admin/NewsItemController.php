<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsItemRequest;
use App\Jobs\TranslateNewsArticleJob;
use App\Models\NewsItem;
use App\Support\AdminNewsTranslationNotifier;
use App\Support\NewsHeroImageProcessor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewsItemController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        if (! in_array($status, ['all', 'published', 'draft'], true)) {
            $status = 'all';
        }

        $search = trim((string) $request->query('q', ''));

        $query = NewsItem::query()
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('id');

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        if ($search !== '') {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function (Builder $sub) use ($like): void {
                $sub->where('title->id', 'LIKE', $like)
                    ->orWhere('title->en', 'LIKE', $like)
                    ->orWhere('author', 'LIKE', $like);
            });
        }

        $items = $query->paginate(10)->withQueryString();

        return view('admin.news.index', compact('items', 'status', 'search'));
    }

    public function create(): View
    {
        return view('admin.news.create', ['item' => new NewsItem]);
    }

    public function store(NewsItemRequest $request): RedirectResponse
    {
        $payload = collect($request->validated())
            ->except(['image', 'remove_image'])
            ->all();

        $data = array_merge([
            'href' => '#',
            'image_path' => null,
            'translation_status' => 'idle',
            'translation_error' => null,
        ], $payload);

        if ($request->hasFile('image')) {
            /** @var UploadedFile $file */
            $file = $request->file('image');
            try {
                $data['image_path'] = NewsHeroImageProcessor::storeProcessed($file);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'image' => $e->getMessage(),
                ]);
            }
        }

        $news = NewsItem::query()->create($data);

        $this->queueOrHintClientTranslation($news);

        return $this->redirectAfterSave($news, 'Berita berhasil ditambahkan.');
    }

    public function edit(NewsItem $news): View
    {
        return view('admin.news.edit', ['item' => $news]);
    }

    public function update(NewsItemRequest $request, NewsItem $news): RedirectResponse
    {
        $payload = collect($request->validated())
            ->except(['image', 'remove_image'])
            ->all();

        if ($request->boolean('remove_image')) {
            NewsItem::deleteStoredUpload($news->image_path);
            $payload['image_path'] = null;
        } elseif ($request->hasFile('image')) {
            NewsItem::deleteStoredUpload($news->image_path);
            /** @var UploadedFile $file */
            $file = $request->file('image');
            try {
                $payload['image_path'] = NewsHeroImageProcessor::storeProcessed($file);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'image' => $e->getMessage(),
                ]);
            }
        }

        $news->update($payload);
        $news->refresh();

        $this->queueOrHintClientTranslation($news);

        return $this->redirectAfterSave($news, 'Berita berhasil diperbarui.');
    }

    public function destroy(NewsItem $news): RedirectResponse
    {
        NewsItem::deleteStoredUpload($news->image_path);
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'Berita dihapus.');
    }

    /**
     * Simpan hasil terjemahan dari Puter.js (browser) ke field bahasa Inggris.
     */
    public function applyPuterTranslation(Request $request, NewsItem $news): JsonResponse
    {
        $data = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'excerpt_en' => ['required', 'string', 'max:5000'],
            'body_en' => ['required', 'string', 'max:100000'],
        ]);

        if ($news->is_published) {
            return response()->json(['message' => 'Berita sudah dipublikasikan.'], 422);
        }

        $news->setTranslation('title', 'en', $data['title_en']);
        $news->setTranslation('excerpt', 'en', $data['excerpt_en']);
        $news->setTranslation('body', 'en', $data['body_en']);
        $news->syncSlugsFromTitles();
        $news->translation_status = 'ready_for_review';
        $news->translation_error = null;
        $news->save();

        AdminNewsTranslationNotifier::notify($news, 'ready_for_review');

        return response()->json(['ok' => true]);
    }

    /** Jalankan ulang job webhook (jika dikonfigurasi). */
    public function retryTranslation(NewsItem $news): RedirectResponse
    {
        if ($news->is_published) {
            return redirect()->back()->with('status', 'Berita sudah dipublikasikan.');
        }

        if (! $news->needsEnglishAutofill()) {
            return redirect()->back()->with('status', 'Bahasa Inggris sudah terisi.');
        }

        if (blank(config('puter.translate_webhook_url'))) {
            return redirect()->route('admin.news.edit', $news)->with('status', 'Webhook belum diatur — terjemahan lewat Puter di browser.')->with('run_puter_translate', true);
        }

        TranslateNewsArticleJob::dispatch($news->id);

        return redirect()->back()->with('status', 'Terjemahan diantrekan. Muat ulang halaman setelah beberapa saat.');
    }

    protected function queueOrHintClientTranslation(NewsItem $news): void
    {
        if (! $news->needsEnglishAutofill()) {
            return;
        }

        if (blank(config('puter.translate_webhook_url'))) {
            return;
        }

        TranslateNewsArticleJob::dispatch($news->id);
    }

    protected function redirectAfterSave(NewsItem $news, string $message): RedirectResponse
    {
        if ($news->needsEnglishAutofill() && blank(config('puter.translate_webhook_url'))) {
            return redirect()
                ->route('admin.news.edit', $news)
                ->with('status', $message.' Terjemahan bahasa Inggris akan dijalankan lewat Puter di browser.')
                ->with('run_puter_translate', true);
        }

        return redirect()->route('admin.news.index')->with('status', $message);
    }
}

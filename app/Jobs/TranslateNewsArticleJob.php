<?php

namespace App\Jobs;

use App\Models\NewsItem;
use App\Support\AdminNewsTranslationNotifier;
use App\Support\PpsContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslateNewsArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $newsItemId) {}

    public function handle(): void
    {
        $url = config('puter.translate_webhook_url');
        if ($url === null || $url === '') {
            return;
        }

        $news = NewsItem::query()->find($this->newsItemId);
        if ($news === null || $news->is_published) {
            return;
        }

        $news->translation_status = 'processing';
        $news->translation_error = null;
        $news->saveQuietly();

        $token = config('puter.translate_webhook_token');
        $timeout = max(15, (int) config('puter.translate_timeout', 120));

        $payload = [
            'id' => $news->getKey(),
            'title_id' => (string) $news->getTranslationWithoutFallback('title', 'id'),
            'excerpt_id' => (string) $news->getTranslationWithoutFallback('excerpt', 'id'),
            'body_id' => (string) $news->getTranslationWithoutFallback('body', 'id'),
        ];

        try {
            $request = Http::timeout($timeout)->acceptJson()->asJson();
            if ($token !== null && $token !== '') {
                $request = $request->withToken($token);
            }

            $response = $request->post($url, $payload);

            if (! $response->successful()) {
                throw new \RuntimeException('Webhook HTTP '.$response->status().': '.$response->body());
            }

            $data = $response->json();
            if (! is_array($data)) {
                throw new \RuntimeException('Respons webhook bukan JSON objek.');
            }

            $titleEn = $data['title_en'] ?? null;
            $excerptEn = $data['excerpt_en'] ?? null;
            $bodyEn = $data['body_en'] ?? null;
            if (! is_string($titleEn) || ! is_string($excerptEn) || ! is_string($bodyEn)) {
                throw new \RuntimeException('JSON harus berisi string title_en, excerpt_en, body_en.');
            }

            $news->setTranslation('title', 'en', $titleEn);
            $news->setTranslation('excerpt', 'en', $excerptEn);
            $news->setTranslation('body', 'en', $bodyEn);
            $news->syncSlugsFromTitles();
            $news->translation_status = 'ready_for_review';
            $news->translation_error = null;
            $news->save();

            AdminNewsTranslationNotifier::notify($news, 'ready_for_review');
        } catch (\Throwable $e) {
            Log::warning('TranslateNewsArticleJob failed', ['id' => $this->newsItemId, 'error' => $e->getMessage()]);
            $news->translation_status = 'failed';
            $news->translation_error = $e->getMessage();
            $news->saveQuietly();

            AdminNewsTranslationNotifier::notify($news, 'failed', $e->getMessage());
        }

        PpsContent::flush();
    }
}

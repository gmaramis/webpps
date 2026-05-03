<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewsTranslationAdminNotification extends Notification
{
    /**
     * @param  'ready_for_review'|'failed'  $kind
     */
    public function __construct(
        public int $newsItemId,
        public string $kind,
        public string $titlePreview,
        public ?string $errorMessage = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{news_id: int, kind: string, title_preview: string, error_message: ?string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'news_id' => $this->newsItemId,
            'kind' => $this->kind,
            'title_preview' => $this->titlePreview,
            'error_message' => $this->errorMessage,
        ];
    }
}

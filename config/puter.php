<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Terjemahan artikel (Puter / webhook)
    |--------------------------------------------------------------------------
    |
    | Setelah draf disimpan, Laravel dapat memanggil URL webhook Anda (mis. fungsi
    | serverless Node yang memakai Puter) untuk mengisi field bahasa Inggris.
    |
    | Payload POST (JSON): lihat App\Jobs\TranslateNewsArticleJob.
    | Respons harus JSON: { "title_en", "excerpt_en", "body_en" } (string).
    |
    | Jika URL kosong, admin akan diminta terjemahan lewat Puter.js di browser
    | (setelah simpan, redirect ke halaman edit dengan proses otomatis).
    |
    */
    'translate_webhook_url' => env('PUTER_TRANSLATE_WEBHOOK_URL'),

    'translate_webhook_token' => env('PUTER_TRANSLATE_WEBHOOK_TOKEN'),

    'translate_timeout' => (int) env('PUTER_TRANSLATE_TIMEOUT', 120),

];

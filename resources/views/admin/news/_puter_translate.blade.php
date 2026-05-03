{{-- Terjemahan ID→EN lewat Puter.js (browser), lalu simpan ke server untuk ditinjau admin. --}}
@php
    /** @var \App\Models\NewsItem $item */
    $titleId = (string) $item->getTranslationWithoutFallback('title', 'id');
    $excerptId = (string) $item->getTranslationWithoutFallback('excerpt', 'id');
    $bodyId = (string) $item->getTranslationWithoutFallback('body', 'id');
@endphp
<script src="https://js.puter.com/v2/"></script>
<script>
(function () {
    var applyUrl = @json(route('admin.news.puter-translation', $item, true));
    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var titleId = @json($titleId);
    var excerptId = @json($excerptId);
    var bodyId = @json($bodyId);

    /** Puter v2: puter.ai.chat() mengembalikan ChatResponse { message: { content } }, bukan string mentah. */
    function chatResponseToText(raw) {
        if (raw == null) {
            return '';
        }
        if (typeof raw === 'string') {
            return raw;
        }
        if (typeof raw !== 'object') {
            try {
                return String(raw);
            } catch (e) {
                return '';
            }
        }
        if (raw.message != null && typeof raw.message.content === 'string') {
            return raw.message.content;
        }
        if (raw.message != null && Array.isArray(raw.message.content)) {
            var out = [];
            for (var i = 0; i < raw.message.content.length; i++) {
                var c = raw.message.content[i];
                if (c == null) {
                    continue;
                }
                if (typeof c === 'string') {
                    out.push(c);
                } else if (c.type === 'text' && typeof c.text === 'string') {
                    out.push(c.text);
                }
            }
            return out.join('\n');
        }
        if (typeof raw.content === 'string') {
            return raw.content;
        }
        if (typeof raw.text === 'string') {
            return raw.text;
        }
        try {
            return JSON.stringify(raw);
        } catch (e) {
            return '';
        }
    }

    function coerceAiString(val, label) {
        if (typeof val === 'string') {
            return val;
        }
        if (typeof val === 'number' || typeof val === 'boolean') {
            return String(val);
        }
        throw new Error(label + ' bukan teks (periksa format JSON dari AI).');
    }

    function parseJsonFromAi(text) {
        var s = String(text || '').trim();
        try { return JSON.parse(s); } catch (e1) {}
        var fence = s.match(/```(?:json)?\s*([\s\S]*?)```/i);
        if (fence) { try { return JSON.parse(fence[1].trim()); } catch (e2) {} }
        var brace = s.match(/\{[\s\S]*\}/);
        if (brace) { try { return JSON.parse(brace[0]); } catch (e3) {} }
        throw new Error('Tidak bisa memparse JSON dari jawaban AI.');
    }

    function show(msg, isErr) {
        var el = document.getElementById('puter-translate-banner');
        if (!el) return;
        el.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-900', 'border-rose-200', 'bg-rose-50', 'text-rose-900');
        if (isErr) {
            el.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-900');
        } else {
            el.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-900');
        }
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    async function run() {
        if (typeof puter === 'undefined' || !puter.ai || !puter.ai.chat) {
            show('Puter.js belum termuat. Periksa koneksi internet atau coba muat ulang halaman.', true);
            return;
        }
        show('Menerjemahkan lewat Puter… mohon tunggu (bisa satu menit).', false);
        var prompt = [
            'You are a professional translator. Translate the following Indonesian news into natural English.',
            'Reply with ONLY a single JSON object, no markdown, with exactly these string keys: title_en, excerpt_en, body_en.',
            'Escape any double quotes inside strings properly. Do not include HTML unless it was in the source.',
            '',
            'Indonesian title:', titleId,
            '',
            'Indonesian excerpt:', excerptId,
            '',
            'Indonesian body (HTML or plain text allowed):', bodyId
        ].join('\n');

        try {
            var raw = await puter.ai.chat(prompt);
            var text = chatResponseToText(raw);
            if (!text || !String(text).trim()) {
                throw new Error('Puter tidak mengembalikan teks (respons kosong).');
            }
            var data = parseJsonFromAi(text);
            var titleEn = coerceAiString(data.title_en, 'title_en');
            var excerptEn = coerceAiString(data.excerpt_en, 'excerpt_en');
            var bodyEn = coerceAiString(data.body_en, 'body_en');
            if (!titleEn.trim() || !excerptEn.trim() || !bodyEn.trim()) {
                throw new Error('JSON tidak berisi title_en, excerpt_en, body_en yang terisi.');
            }
            var res = await fetch(applyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    title_en: titleEn,
                    excerpt_en: excerptEn,
                    body_en: bodyEn
                })
            });
            if (!res.ok) {
                var errText = await res.text();
                throw new Error('Server: ' + res.status + ' ' + errText);
            }
            show('Terjemahan disimpan. Anda bisa mempublikasikan dari daftar berita jika sudah siap.', false);
            window.location.reload();
        } catch (e) {
            var msg = 'Kesalahan tidak diketahui';
            try {
                msg = (e && typeof e.message === 'string' && e.message) ? e.message : (typeof e === 'string' ? e : String(e));
            } catch (e2) {
                msg = 'Gagal memproses respons Puter.';
            }
            show('Gagal: ' + msg, true);
        }
    }

    run();
})();
</script>

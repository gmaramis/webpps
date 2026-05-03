<?php

namespace Database\Seeders;

use App\Models\NewsItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Mengisi body berita yang judulnya berkaitan dengan pelatihan CEH bagi dosen (paragraf dummy).
 * Jalankan: php artisan db:seed --class=NewsPelatihanCehDummyBodySeeder
 */
class NewsPelatihanCehDummyBodySeeder extends Seeder
{
    public function run(): void
    {
        $item = NewsItem::query()
            ->orderByDesc('published_at')
            ->get()
            ->first(fn (NewsItem $n): bool => self::matchesCehDosenTitle($n));

        if ($item === null) {
            $this->command?->warn('Tidak menemukan berita dengan judul mengandung Pelatihan CEH / CEH Dosen. Lewati pengisian body.');

            return;
        }

        $bodyId = <<<'HTML'
<p>Pascasarjana UNIMA menyelenggarakan pelatihan <strong>Certified Ethical Hacker (CEH)</strong> bagi dosen sebagai bagian dari penguatan kapasitas keamanan siber dan perlindungan infrastruktur informasi di lingkungan kampus. Kegiatan ini bertujuan memberikan pemahaman menyeluruh tentang pola serangan umum, teknik pengujian penetrasi yang etis, serta kerangka berpikir pertahanan berlapis terhadap ancaman digital.</p>
<p>Selama pelatihan, peserta dibimbing untuk mengenali kerentanan pada sistem dan layanan jaringan, memahami metodologi pengumpulan informasi (reconnaissance), serta mempraktikkan pendekatan mitigasi yang selaras dengan standar keamanan industri. Materi disampaikan secara bertahap agar peserta dapat menghubungkan konsep teoretis dengan skenario nyata di laboratorium.</p>
<p>Fasilitator menekankan prinsip <em>ethical hacking</em>: setiap aktivitas pengujian harus mengikuti izin tertulis, ruang lingkup yang disepakati, dan dokumentasi temuan untuk perbaikan berkelanjutan. Dengan demikian, dosen tidak hanya memahami cara kerja penyerang, tetapi juga mampu mengajarkan etika dan kepatuhan kepada mahasiswa.</p>
<p>Diskusi kelompok dan studi kasus menjadi bagian penting sesi. Peserta menganalisis jejak serangan pada lingkungan yang dikontrol, menyusun rekomendasi perbaikan konfigurasi, serta berlatih menyampaikan laporan teknis kepada pemangku kebijakan tanpa jargon yang berlebihan.</p>
<p>Pada tahap akhir, diharapkan dosen dapat mengintegrasikan elemen keamanan siber pada mata kuliah terkait teknologi informasi dan penelitian, serta mendukung kebijakan keamanan data di tingkat program studi. Pelatihan ini menjadi fondasi bagi pengembangan modul perkuliahan dan kolaborasi lintas disiplin ilmu.</p>
<p><strong>Catatan:</strong> paragraf di atas bersifat dummy untuk pengujian tampilan halaman berita (float gambar, sidebar, dan struktur kartu). Sesuaikan isi melalui panel admin sesuai pelaksanaan aktual kegiatan.</p>
HTML;

        $bodyEn = <<<'HTML'
<p>The graduate school organised <strong>Certified Ethical Hacker (CEH)</strong> training for faculty to strengthen cybersecurity awareness and responsible assessment practices on campus networks and systems.</p>
<p>Sessions covered common threat patterns, ethical penetration-testing methodology, and layered defence concepts. Participants linked theory to guided lab scenarios.</p>
<p>Trainers stressed proper scope, written authorisation, and clear reporting—so lecturers can teach both technical insight and professional ethics.</p>
<p>Group work and case studies helped analyse controlled incidents and draft actionable recommendations for administrators.</p>
<p><strong>Note:</strong> these paragraphs are placeholder copy for layout testing; replace them in the admin panel with your official programme narrative.</p>
HTML;

        $item->setTranslation('body', 'id', $bodyId);

        $currentExcerptId = trim((string) $item->getTranslationWithoutFallback('excerpt', 'id'));
        if ($currentExcerptId === '') {
            $item->setTranslation('excerpt', 'id', 'Ringkasan dummy: pelatihan CEH bagi dosen membahas ethical hacking, pengujian keamanan berizin, dan integrasi materi ke perkuliahan.');
        }

        if ($item->hasTranslation('title', 'en')) {
            $item->setTranslation('body', 'en', $bodyEn);
            $currentExcerptEn = trim((string) $item->getTranslationWithoutFallback('excerpt', 'en'));
            if ($currentExcerptEn === '') {
                $item->setTranslation('excerpt', 'en', 'Dummy excerpt: CEH training for lecturers covers ethical hacking basics, authorised assessments, and classroom integration.');
            }
        }

        if (trim((string) ($item->author ?? '')) === '') {
            $item->author = 'Humas Pascasarjana UNIMA';
        }

        $item->save();

        $titlePreview = Str::limit((string) $item->getTranslationWithoutFallback('title', 'id'), 72);

        $this->command?->info('Body dummy diperbarui untuk: '.$titlePreview.' (ID '.$item->getKey().').');
    }

    private static function matchesCehDosenTitle(NewsItem $n): bool
    {
        $id = Str::lower((string) $n->getTranslationWithoutFallback('title', 'id'));
        $en = Str::lower((string) $n->getTranslationWithoutFallback('title', 'en'));

        $hay = $id.' '.$en;

        return Str::contains($hay, 'ceh')
            && (Str::contains($hay, 'dosen') || Str::contains($hay, 'lecturer') || Str::contains($hay, 'faculty'))
            || (Str::contains($hay, 'pelatihan') && Str::contains($hay, 'ceh'));
    }
}

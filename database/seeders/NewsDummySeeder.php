<?php

namespace Database\Seeders;

use App\Models\NewsItem;
use Illuminate\Database\Seeder;

class NewsDummySeeder extends Seeder
{
    /** Prefix href untuk menghapus ulang dummy tanpa menyentuh berita lain. */
    private const DUMMY_HREF_PREFIX = '#dummy-news-seed:';

    public function run(): void
    {
        NewsItem::query()->where('href', 'like', self::DUMMY_HREF_PREFIX.'%')->delete();

        $items = [
            [
                'id' => 'Pembukaan Semester Genap 2026',
                'en' => 'Opening of Even Semester 2026',
                'excerpt_id' => 'Kegiatan pembukaan semester genap tahun akademik 2026/2027 di lingkungan Pascasarjana.',
                'excerpt_en' => 'Opening activities for the 2026/2027 even semester at the graduate school.',
            ],
            [
                'id' => 'Workshop Penulisan Artikel Ilmiah',
                'en' => 'Scientific Article Writing Workshop',
                'excerpt_id' => 'Pelatihan intensif penulisan untuk jurnal nasional dan internasional bagi mahasiswa pascasarjana.',
                'excerpt_en' => 'Intensive training on writing for national and international journals for graduate students.',
            ],
            [
                'id' => 'Kerjasama dengan Universitas Mitra di Asia Tenggara',
                'en' => 'Partnership with Partner Universities in Southeast Asia',
                'excerpt_id' => 'Penandatanganan nota kesepahaman untuk pertukaran dosen dan penelitian bersama.',
                'excerpt_en' => 'MoU signing for faculty exchange and joint research.',
            ],
            [
                'id' => 'Seleksi Beasiswa Unggulan Tahap II',
                'en' => 'Outstanding Scholarship Selection Phase II',
                'excerpt_id' => 'Pengumuman tahapan seleksi dan dokumen yang harus dilengkapi peserta.',
                'excerpt_en' => 'Announcement of selection stages and required documents for applicants.',
            ],
            [
                'id' => 'Seminar Nasional Kebijakan Pendidikan Tinggi',
                'en' => 'National Seminar on Higher Education Policy',
                'excerpt_id' => 'Diskusi kebijakan mutu dan akreditasi program studi pascasarjana.',
                'excerpt_en' => 'Discussion on quality policy and accreditation of graduate programmes.',
            ],
            [
                'id' => 'Peluncuran Portal Repositori Tesis Digital',
                'en' => 'Launch of Digital Thesis Repository Portal',
                'excerpt_id' => 'Akses dokumen tesis dan disertasi untuk civitas akademika sesuai ketentuan akses terbuka.',
                'excerpt_en' => 'Access to thesis and dissertation documents for the academic community under open-access rules.',
            ],
            [
                'id' => 'Orientasi Mahasiswa Baru Program Magister',
                'en' => 'New Student Orientation for Master’s Programme',
                'excerpt_id' => 'Pengenalan kurikulum, bimbingan akademik, dan layanan kemahasiswaan.',
                'excerpt_en' => 'Introduction to curriculum, academic advising, and student services.',
            ],
            [
                'id' => 'Monitoring Evaluasi Pembimbingan Disertasi',
                'en' => 'Monitoring and Evaluation of Dissertation Supervision',
                'excerpt_id' => 'Hasil survei kepuasan mahasiswa doktor terhadap proses bimbingan semester lalu.',
                'excerpt_en' => 'Survey results on doctoral students’ satisfaction with last semester’s supervision.',
            ],
            [
                'id' => 'Pelatihan Etika Penelitian dan Anti-plagiarisme',
                'en' => 'Research Ethics and Anti-plagiarism Training',
                'excerpt_id' => 'Wajib bagi mahasiswa yang akan mengajukan proposal penelitian atau publikasi.',
                'excerpt_en' => 'Mandatory for students submitting research proposals or publications.',
            ],
            [
                'id' => 'Kunjungan Studi Banding ke Lembaga Akreditasi',
                'en' => 'Benchmarking Visit to Accreditation Agency',
                'excerpt_id' => 'Tim mutu membahas peningkatan standar penyelenggaraan program doktor.',
                'excerpt_en' => 'Quality team discusses raising standards for doctoral programme delivery.',
            ],
        ];

        foreach ($items as $index => $row) {
            $n = $index + 1;
            $bodyId = '<p>'.e($row['excerpt_id']).'</p><p>Ini adalah isi berita dummy nomor '.$n.' untuk pengujian tampilan halaman dan sidebar. Informasi lengkap dapat ditambahkan melalui panel admin.</p>';
            $bodyEn = '<p>'.e($row['excerpt_en']).'</p><p>This is dummy news body number '.$n.' for layout testing. Full content can be added via the admin panel.</p>';

            $item = new NewsItem;
            $item->is_published = true;
            $item->published_at = now()->subDays(30 - ($n * 2))->startOfDay();
            $item->href = self::DUMMY_HREF_PREFIX.$n;
            $item->image_path = null;
            $item->translation_status = 'idle';
            $item->translation_error = null;
            $item->title = ['id' => $row['id'], 'en' => $row['en']];
            $item->excerpt = ['id' => $row['excerpt_id'], 'en' => $row['excerpt_en']];
            $item->body = ['id' => $bodyId, 'en' => $bodyEn];
            $item->category = ['id' => 'Kampus', 'en' => 'Campus'];
            $item->location = ['id' => 'Manado', 'en' => 'Manado'];
            $item->author = 'Tim Redaksi Dummy';
            $item->save();
        }
    }
}

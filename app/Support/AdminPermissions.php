<?php

namespace App\Support;

final class AdminPermissions
{
    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            'dashboard.view',
            'profile.manage',
            'users.manage',
            'news.manage',
            'slideshow.manage',
            'program-heroes.manage',
            'pengumuman.manage',
            'agenda.manage',
            'visi-misi.manage',
            'struktur-pimpinan.manage',
            'kerjasama.manage',
            'dosen.manage',
            'panduan-akademik.manage',
            'dokumen-akreditasi.manage',
            'prodi-s2.manage',
            'prodi-s3.manage',
            'kegiatan-mahasiswa.manage',
            'kegiatan-alumni.manage',
            'tautan-portal-akademik.manage',
            'stop-korupsi.manage',
            'stop-gratifikasi.manage',
            'zi.manage',
        ];
    }

    /**
     * @return list<string>
     */
    public static function adminDefault(): array
    {
        return array_values(array_filter(
            self::all(),
            static fn (string $permission): bool => $permission !== 'users.manage'
        ));
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'dashboard.view' => 'Lihat dasbor admin',
            'profile.manage' => 'Kelola profil dan kata sandi sendiri',
            'users.manage' => 'Kelola user, role, dan permission',
            'news.manage' => 'Kelola berita',
            'slideshow.manage' => 'Kelola slideshow beranda',
            'program-heroes.manage' => 'Kelola gambar hero Magister & Doktor di beranda',
            'pengumuman.manage' => 'Kelola pengumuman',
            'agenda.manage' => 'Kelola agenda',
            'visi-misi.manage' => 'Kelola halaman visi misi',
            'struktur-pimpinan.manage' => 'Kelola struktur pimpinan',
            'kerjasama.manage' => 'Kelola data kerjasama',
            'dosen.manage' => 'Kelola data dosen',
            'panduan-akademik.manage' => 'Kelola panduan akademik',
            'dokumen-akreditasi.manage' => 'Kelola dokumen akreditasi',
            'prodi-s2.manage' => 'Kelola program studi S2',
            'prodi-s3.manage' => 'Kelola program studi S3',
            'kegiatan-mahasiswa.manage' => 'Kelola kegiatan mahasiswa',
            'kegiatan-alumni.manage' => 'Kelola kegiatan alumni',
            'tautan-portal-akademik.manage' => 'Kelola tautan portal akademik',
            'stop-korupsi.manage' => 'Kelola konten stop korupsi',
            'stop-gratifikasi.manage' => 'Kelola konten stop gratifikasi',
            'zi.manage' => 'Kelola instrumen zona integritas',
        ];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function grouped(): array
    {
        return [
            'Akun & akses' => [
                'dashboard.view',
                'profile.manage',
                'users.manage',
            ],
            'Konten utama' => [
                'news.manage',
                'slideshow.manage',
                'program-heroes.manage',
                'pengumuman.manage',
                'agenda.manage',
            ],
            'Profil lembaga' => [
                'visi-misi.manage',
                'struktur-pimpinan.manage',
                'kerjasama.manage',
                'dosen.manage',
                'panduan-akademik.manage',
                'dokumen-akreditasi.manage',
            ],
            'Program studi & aktivitas' => [
                'prodi-s2.manage',
                'prodi-s3.manage',
                'kegiatan-mahasiswa.manage',
                'kegiatan-alumni.manage',
            ],
            'Kepatuhan & integritas' => [
                'tautan-portal-akademik.manage',
                'stop-korupsi.manage',
                'stop-gratifikasi.manage',
                'zi.manage',
            ],
        ];
    }
}

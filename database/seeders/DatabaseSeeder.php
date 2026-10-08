<?php

namespace Database\Seeders;

use App\Models\NavMenu;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──
        User::updateOrCreate(
            ['email' => 'admin@satudata.me'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // ── Default Nav Menus ──
        $menus = [
            ['title' => 'Beranda', 'type' => 'route', 'route_name' => 'home', 'order' => 0],
            ['title' => 'Dataset', 'type' => 'route', 'route_name' => 'datasets.search', 'order' => 1],
            ['title' => 'Instansi', 'type' => 'route', 'route_name' => 'organizations', 'order' => 2],
            ['title' => 'Publikasi', 'type' => 'route', 'route_name' => 'publikasi', 'order' => 3],
            ['title' => 'Tentang', 'type' => 'route', 'route_name' => 'tentang', 'order' => 4],
        ];

        foreach ($menus as $menu) {
            NavMenu::updateOrCreate(
                ['title' => $menu['title'], 'parent_id' => null],
                $menu
            );
        }

        // ── Sample Publications ──
        $publications = [
            [
                'title' => 'Sosialisasi Portal Satu Data Kabupaten Muara Enim kepada OPD',
                'type' => 'berita',
                'excerpt' => 'Dinas Kominfo menyelenggarakan sosialisasi penggunaan portal Satu Data kepada seluruh Organisasi Perangkat Daerah di lingkungan Pemerintah Kabupaten Muara Enim.',
                'body' => '<p>Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Muara Enim menyelenggarakan sosialisasi penggunaan portal Satu Data kepada seluruh Organisasi Perangkat Daerah.</p><p>Kegiatan ini bertujuan untuk meningkatkan pemahaman dan kemampuan OPD dalam mengelola data sektoral melalui portal Satu Data Kabupaten Muara Enim.</p><h2>Tujuan Kegiatan</h2><p>Sosialisasi ini diharapkan dapat mendorong budaya data di lingkungan pemerintah daerah, sehingga pengambilan keputusan dapat berbasis data yang akurat dan terkini.</p>',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Update Data Kependudukan Tahun 2026 Telah Tersedia',
                'type' => 'berita',
                'excerpt' => 'Data kependudukan terbaru tahun 2026 telah diperbarui dan dapat diakses melalui portal Satu Data Muara Enim.',
                'body' => '<p>Data kependudukan Kabupaten Muara Enim tahun 2026 telah selesai diverifikasi dan kini tersedia di portal Satu Data.</p><p>Dataset ini mencakup data jumlah penduduk per kecamatan, distribusi usia, dan rasio jenis kelamin.</p>',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Pelatihan Pengelolaan Data untuk Operator OPD',
                'type' => 'berita',
                'excerpt' => 'Pelatihan pengelolaan dan upload data bagi operator OPD untuk meningkatkan kualitas dan kuantitas data yang tersedia di portal.',
                'body' => '<p>Dalam rangka meningkatkan kualitas data yang tersedia di portal Satu Data, Dinas Kominfo menyelenggarakan pelatihan bagi operator data di masing-masing OPD.</p><p>Pelatihan mencakup teknik pengumpulan data, standarisasi format, dan cara upload ke portal CKAN.</p>',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Jumlah Penduduk Kabupaten Muara Enim 2026',
                'type' => 'infografis',
                'excerpt' => 'Infografis data kependudukan Kabupaten Muara Enim tahun 2026.',
                'body' => '<p>Infografis ini menyajikan data jumlah penduduk Kabupaten Muara Enim berdasarkan kecamatan pada tahun 2026.</p>',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Statistik Kesehatan Masyarakat',
                'type' => 'infografis',
                'excerpt' => 'Data statistik kesehatan masyarakat Kabupaten Muara Enim.',
                'body' => '<p>Infografis statistik kesehatan masyarakat mencakup data fasilitas kesehatan, tenaga medis, dan indikator kesehatan utama.</p>',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Angka Partisipasi Sekolah',
                'type' => 'infografis',
                'excerpt' => 'Infografis angka partisipasi sekolah di Kabupaten Muara Enim.',
                'body' => '<p>Data angka partisipasi sekolah pada jenjang SD, SMP, dan SMA di Kabupaten Muara Enim.</p>',
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($publications as $pub) {
            Publication::updateOrCreate(
                ['title' => $pub['title']],
                $pub
            );
        }
    }
}

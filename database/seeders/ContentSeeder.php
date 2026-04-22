<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\ProfilSekolah;
use App\Models\SettingSekolah;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Setting Sekolah
        SettingSekolah::firstOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SD Negeri 1 Passo',
                'alamat' => 'Jl. Raya Passo, Kec. Baguala, Kota Ambon, Maluku',
                'no_telepon' => '0911-123456',
                'email' => 'info@sdn1passo.sch.id',
                'website' => 'www.sdn1passo.sch.id',
                'logo' => null,
            ]
        );

        // Profil Sekolah
        $profils = [
            [
                'jenis' => 'sejarah',
                'judul' => 'Sejarah Sekolah',
                'isi' => '<p>SD Negeri 1 Passo didirikan pada tahun 1970 dengan visi mencerdaskan anak bangsa di wilayah timur Indonesia. Sekolah ini telah mencetak ribuan alumni yang berprestasi di tingkat nasional maupun internasional.</p>'
            ],
            [
                'jenis' => 'visi',
                'judul' => 'Visi Sekolah',
                'isi' => '<p>Menjadi sekolah unggul yang berkarakter, inovatif, dan berwawasan lingkungan menuju Generasi Emas 2045.</p>'
            ],
            [
                'jenis' => 'misi',
                'judul' => 'Misi Sekolah',
                'isi' => '<ul><li>Meningkatkan kualitas pembelajaran yang aktif, inovatif, kreatif, efektif, dan menyenangkan (PAIKEM).</li><li>Membangun karakter siswa yang berbudi pekerti luhur dan bertakwa kepada Tuhan YME.</li><li>Mengembangkan potensi bakat dan minat siswa melalui kegiatan ekstrakurikuler.</li></ul>'
            ]
        ];

        foreach ($profils as $p) {
            ProfilSekolah::firstOrCreate(['jenis' => $p['jenis']], $p);
        }

        // Berita
        for ($i = 1; $i <= 6; $i++) {
            $judul = $faker->sentence(6);
            Berita::firstOrCreate(
                ['slug' => Str::slug($judul)],
                [
                    'judul' => $judul,
                    'isi' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                    'user_id' => 1, // Super Admin
                    'status' => 'publish',
                    'published_at' => now()->subDays($i * 2),
                ]
            );
        }

        // Galeri
        for ($i = 1; $i <= 8; $i++) {
            Galeri::firstOrCreate(
                ['judul' => 'Dokumentasi ' . $faker->randomElement(['Upacara Bendera', 'Lomba 17 Agustus', 'Pramuka', 'Senam Bersama', 'Kunjungan Edukasi', 'Pentas Seni'])],
                [
                    'file_path' => 'galeri/dummy' . $i . '.jpg',
                    'keterangan' => 'Koleksi foto kegiatan siswa dan guru yang dilaksanakan pada bulan ini.',
                    'jenis' => 'foto',
                    'user_id' => 1,
                ]
            );
        }
    }
}

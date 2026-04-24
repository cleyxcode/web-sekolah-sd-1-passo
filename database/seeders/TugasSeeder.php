<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\KomentarTugas;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua kelas yang memiliki wali kelas
        $kelas = Kelas::whereNotNull('wali_kelas_id')->get();

        foreach ($kelas as $k) {
            $guruId = $k->wali_kelas_id;

            // Tugas 1: Aktif, besok deadline
            $tugas1 = Tugas::create([
                'judul'          => 'Latihan Soal Tematik ' . $k->nama_kelas,
                'deskripsi'      => 'Kerjakan soal di buku tematik halaman 45-50. Kerjakan dengan teliti dan kumpulkan tepat waktu. Jika ada kesulitan, silakan tanyakan pada wali kelas.',
                'mata_pelajaran' => 'Tematik',
                'kelas_id'       => $k->id,
                'guru_id'        => $guruId,
                'deadline'       => Carbon::now()->addDays(2),
                'status'         => 'aktif',
            ]);

            // Tambahkan komentar guru pada Tugas 1
            KomentarTugas::create([
                'tugas_id' => $tugas1->id,
                'guru_id'  => $guruId,
                'komentar' => 'Anak-anak, jangan lupa kerjakan nomor 1 sampai 10 saja ya. Nomor sisanya untuk PR minggu depan.',
                'created_at' => Carbon::now()->subHours(2),
            ]);

            KomentarTugas::create([
                'tugas_id' => $tugas1->id,
                'guru_id'  => $guruId,
                'komentar' => 'Bagi yang sudah selesai duluan, tolong baca kembali materi bab 2.',
                'created_at' => Carbon::now()->subMinutes(30),
            ]);

            // Tugas 2: Aktif, deadline hari ini
            $tugas2 = Tugas::create([
                'judul'          => 'Hafalan Surat Pendek',
                'deskripsi'      => 'Mohon bantuan Bapak/Ibu untuk merekam anak menghafal surat Al-Ikhlas dan An-Nas.',
                'mata_pelajaran' => 'Pendidikan Agama Islam',
                'kelas_id'       => $k->id,
                'guru_id'        => $guruId,
                'deadline'       => Carbon::now()->addHours(5),
                'status'         => 'aktif',
            ]);

            KomentarTugas::create([
                'tugas_id' => $tugas2->id,
                'guru_id'  => $guruId,
                'komentar' => 'Batas pengumpulan video hafalan maksimal nanti sore ya.',
                'created_at' => Carbon::now()->subHours(1),
            ]);

            // Tugas 3: Sudah selesai (lewat deadline)
            Tugas::create([
                'judul'          => 'Menggambar Bebas Tema Lingkungan',
                'deskripsi'      => 'Silakan menggambar bebas di buku gambar A4 dengan tema lingkungan sekitar rumah.',
                'mata_pelajaran' => 'Seni Budaya',
                'kelas_id'       => $k->id,
                'guru_id'        => $guruId,
                'deadline'       => Carbon::now()->subDays(3),
                'status'         => 'selesai',
            ]);
        }

        $this->command->info('Berhasil men-seed data Tugas dan Komentar!');
    }
}

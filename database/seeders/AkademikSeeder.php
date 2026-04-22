<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\Presensi;
use App\Models\CatatanPerkembangan;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ============================================================
        // 1. TAHUN AJARAN AKTIF
        // ============================================================
        $tahunAjaran = TahunAjaran::firstOrCreate(
            ['nama' => '2025/2026', 'semester' => '1'],
            ['is_active' => true]
        );

        // ============================================================
        // 2. MATA PELAJARAN
        // ============================================================
        $mapels = [
            ['nama' => 'Matematika',           'kode' => 'MAT01'],
            ['nama' => 'Pendidikan Agama',      'kode' => 'PA01'],
            ['nama' => 'Bahasa Indonesia',      'kode' => 'BIN01'],
            ['nama' => 'Ilmu Pengetahuan Alam', 'kode' => 'IPA01'],
            ['nama' => 'Pendidikan Pancasila',  'kode' => 'PPKN01'],
        ];
        $mapelIds = [];
        foreach ($mapels as $m) {
            $obj = MataPelajaran::firstOrCreate(['nama' => $m['nama']], ['kode' => $m['kode'], 'tingkat_kelas' => 1]);
            $mapelIds[] = $obj->id;
        }

        // ============================================================
        // 3. BUAT 12 GURU (2 per tingkat, 6 tingkat)
        // ============================================================
        $guruData = [
            ['email' => 'guru1@sekolah.com',  'nama' => 'Budi Santoso, S.Pd',     'nip' => '198001012010011001', 'jk' => 'L'],
            ['email' => 'guru2@sekolah.com',  'nama' => 'Siti Rahmawati, S.Pd',   'nip' => '198202022010022002', 'jk' => 'P'],
            ['email' => 'guru3@sekolah.com',  'nama' => 'Ahmad Fauzi, S.Pd',      'nip' => '197901032009033003', 'jk' => 'L'],
            ['email' => 'guru4@sekolah.com',  'nama' => 'Dewi Kusuma, S.Pd',      'nip' => '198504042011044004', 'jk' => 'P'],
            ['email' => 'guru5@sekolah.com',  'nama' => 'Rizki Pratama, S.Pd',    'nip' => '199001052012055005', 'jk' => 'L'],
            ['email' => 'guru6@sekolah.com',  'nama' => 'Ningsih Wahyuni, S.Pd',  'nip' => '198706062013066006', 'jk' => 'P'],
            ['email' => 'guru7@sekolah.com',  'nama' => 'Eko Setiawan, S.Pd',     'nip' => '198307072014077007', 'jk' => 'L'],
            ['email' => 'guru8@sekolah.com',  'nama' => 'Fitri Handayani, S.Pd',  'nip' => '199208082015088008', 'jk' => 'P'],
            ['email' => 'guru9@sekolah.com',  'nama' => 'Hendra Wijaya, S.Pd',    'nip' => '198009092016099009', 'jk' => 'L'],
            ['email' => 'guru10@sekolah.com', 'nama' => 'Indah Permata, S.Pd',    'nip' => '199110102017100010', 'jk' => 'P'],
            ['email' => 'guru11@sekolah.com', 'nama' => 'Joko Susilo, S.Pd',      'nip' => '197811112018111011', 'jk' => 'L'],
            ['email' => 'guru12@sekolah.com', 'nama' => 'Kartini Lestari, S.Pd',  'nip' => '198612122019122012', 'jk' => 'P'],
        ];

        $gurus = [];
        foreach ($guruData as $idx => $gd) {
            $user = User::firstOrCreate(
                ['email' => $gd['email']],
                ['name' => $gd['nama'], 'password' => Hash::make('password')]
            );
            if (!$user->hasRole('Guru')) $user->assignRole('Guru');

            $guru = Guru::firstOrCreate(
                ['user_id' => $user->id],
                ['nip' => $gd['nip'], 'nama' => $gd['nama'], 'jenis_kelamin' => $gd['jk'], 'no_telepon' => '0812345678' . str_pad($idx, 2, '0', STR_PAD_LEFT)]
            );
            $gurus[] = $guru;
        }

        // ============================================================
        // 4. BUAT KELAS 1A-6B (2 kelas per tingkat)
        // ============================================================
        $kelasConfig = [
            // [nama_kelas, tingkat, guru_index]
            ['1A', 1, 0],  ['1B', 1, 1],
            ['2A', 2, 2],  ['2B', 2, 3],
            ['3A', 3, 4],  ['3B', 3, 5],
            ['4A', 4, 6],  ['4B', 4, 7],
            ['5A', 5, 8],  ['5B', 5, 9],
            ['6A', 6, 10], ['6B', 6, 11],
        ];

        $kelasList = [];
        $hariArr = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($kelasConfig as [$namaKelas, $tingkat, $guruIdx]) {
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => $namaKelas, 'tahun_ajaran_id' => $tahunAjaran->id],
                ['tingkat' => $tingkat, 'wali_kelas_id' => $gurus[$guruIdx]->id]
            );

            // Update wali kelas jika sudah ada
            $kelas->update(['tingkat' => $tingkat, 'wali_kelas_id' => $gurus[$guruIdx]->id]);

            // Jadwal Pelajaran
            foreach ($hariArr as $hIdx => $hari) {
                JadwalPelajaran::firstOrCreate([
                    'kelas_id' => $kelas->id,
                    'hari'     => $hari,
                    'jam_mulai' => '08:00:00',
                ], [
                    'mata_pelajaran_id' => $mapelIds[$hIdx % count($mapelIds)],
                    'guru_id'   => $gurus[$guruIdx]->id,
                    'jam_selesai' => '09:30:00',
                ]);
            }

            $kelasList[$namaKelas] = $kelas;
        }

        // ============================================================
        // 5. BUAT SISWA PER KELAS (8 siswa per kelas = 96 siswa total)
        // ============================================================
        $siswaPerKelas = 8;
        $nisCounter = 250001;

        // Data nama siswa laki-laki dan perempuan
        $namaLaki   = ['Ahmad', 'Budi', 'Cahyo', 'Dani', 'Eko', 'Fajar', 'Galih', 'Hendra', 'Irfan', 'Joko', 'Kevin', 'Luthfi', 'Maulana', 'Nabil', 'Omar'];
        $namaPeremp = ['Ayu', 'Bunga', 'Citra', 'Dewi', 'Ella', 'Fitri', 'Gita', 'Hani', 'Intan', 'Julia', 'Kirana', 'Laila', 'Maya', 'Nisa', 'Olivia'];
        $namaBlkg   = ['Santoso', 'Rahmawati', 'Wijaya', 'Kusuma', 'Pratama', 'Handayani', 'Setiawan', 'Permata', 'Nugroho', 'Wahyuni'];

        foreach ($kelasConfig as [$namaKelas, $tingkat, $guruIdx]) {
            $kelas = $kelasList[$namaKelas];
            $guru  = $gurus[$guruIdx];

            // Cek sudah ada siswa di kelas ini atau belum
            $existingSiswaCount = Siswa::where('kelas_id', $kelas->id)->count();
            if ($existingSiswaCount >= $siswaPerKelas) continue;

            $toCreate = $siswaPerKelas - $existingSiswaCount;

            for ($i = 0; $i < $toCreate; $i++) {
                $jk   = ($i % 2 === 0) ? 'L' : 'P';
                $pool = ($jk === 'L') ? $namaLaki : $namaPeremp;
                $nm1  = $pool[array_rand($pool)];
                $nm2  = $namaBlkg[array_rand($namaBlkg)];
                $nama = $nm1 . ' ' . $nm2;
                $nis  = (string)$nisCounter++;

                // Hindari duplikasi NIS
                while (Siswa::where('nis', $nis)->exists()) {
                    $nis = (string)$nisCounter++;
                }

                $tahunLahir = date('Y') - (6 + $tingkat);
                $tglLahir   = Carbon::create($tahunLahir, rand(1, 12), rand(1, 28));

                $siswa = Siswa::create([
                    'nis'            => $nis,
                    'nama'           => $nama,
                    'jenis_kelamin'  => $jk,
                    'tanggal_lahir'  => $tglLahir,
                    'alamat'         => 'Jl. ' . $faker->streetName . ' No. ' . rand(1, 99) . ', Ambon',
                    'kelas_id'       => $kelas->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'status'         => 'aktif',
                ]);

                // Buat akun orang tua
                $namaOrtu = 'Wali dari ' . $nama;
                $emailOrtu = 'ortu.' . $nis . '@sekolah.com';

                $ortu = OrangTua::firstOrCreate(
                    ['email' => $emailOrtu],
                    [
                        'nama'      => $namaOrtu,
                        'email'     => $emailOrtu,
                        'password'  => Hash::make($nis),
                        'hubungan'  => (rand(0,1) ? 'Ayah' : 'Ibu'),
                        'no_telepon' => '0812' . rand(10000000, 99999999),
                    ]
                );
                $ortu->siswas()->syncWithoutDetaching([$siswa->id]);

                // Nilai
                foreach ($mapelIds as $mapelId) {
                    $nilaiAngka = rand(65, 98);
                    Nilai::firstOrCreate([
                        'siswa_id'         => $siswa->id,
                        'mata_pelajaran_id' => $mapelId,
                        'jenis_ujian'      => 'UTS',
                        'tahun_ajaran_id'  => $tahunAjaran->id,
                    ], [
                        'nilai_angka'  => $nilaiAngka,
                        'guru_id'      => $guru->id,
                        'kelas_id'     => $kelas->id,
                    ]);
                }

                // Presensi (5 hari terakhir)
                $statusPresensi = ['hadir', 'hadir', 'hadir', 'sakit', 'izin'];
                for ($h = 0; $h < 5; $h++) {
                    $tanggal = Carbon::today()->subDays($h + 1)->toDateString();
                    Presensi::firstOrCreate([
                        'siswa_id'  => $siswa->id,
                        'tanggal'   => $tanggal,
                        'kelas_id'  => $kelas->id,
                    ], [
                        'status'         => $statusPresensi[$h],
                        'guru_id'        => $guru->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'keterangan'     => ($statusPresensi[$h] !== 'hadir') ? 'Keterangan ' . $statusPresensi[$h] : null,
                    ]);
                }

                // Catatan Perkembangan
                $predikatArr = ['Sangat Baik', 'Baik', 'Cukup'];
                $catatanArr  = [
                    'Siswa menunjukkan perkembangan yang sangat positif dalam kegiatan belajar.',
                    'Partisipasi aktif dalam kelas dan menyelesaikan tugas dengan baik.',
                    'Perlu peningkatan dalam disiplin waktu dan konsentrasi belajar.',
                ];
                CatatanPerkembangan::firstOrCreate([
                    'siswa_id' => $siswa->id,
                    'guru_id'  => $guru->id,
                ], [
                    'predikat' => $predikatArr[array_rand($predikatArr)],
                    'catatan'  => $catatanArr[array_rand($catatanArr)],
                ]);
            }
        }

        // ============================================================
        // 6. SATU KELUARGA: Satu orang tua dengan 2 anak di kelas berbeda
        // ============================================================
        $sibling1 = Siswa::where('kelas_id', $kelasList['2A']->id)->first();
        $sibling2 = Siswa::where('kelas_id', $kelasList['4B']->id)->first();

        if ($sibling1 && $sibling2) {
            $ortuBersama = OrangTua::firstOrCreate(
                ['email' => 'ortu.bersama@sekolah.com'],
                [
                    'nama'       => 'Keluarga ' . explode(' ', $sibling1->nama)[1],
                    'password'   => Hash::make('password'),
                    'hubungan'   => 'Ayah',
                    'no_telepon' => '08100000001',
                ]
            );
            $ortuBersama->siswas()->syncWithoutDetaching([$sibling1->id, $sibling2->id]);
        }

        $this->command->info('✅ AkademikSeeder selesai: 12 kelas (1A-6B), ' . Siswa::count() . ' siswa total.');
    }
}

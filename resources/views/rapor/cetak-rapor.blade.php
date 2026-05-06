<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Belajar - {{ $siswa->nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 20px;
            font-size: 12pt;
        }
        .page {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .kop {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .kop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 5px 0 0;
            font-size: 12pt;
        }
        
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        
        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.nilai th, table.nilai td {
            border: 1px solid #000;
            padding: 8px;
        }
        table.nilai th {
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        
        .footer-ttd {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            margin-top: 40px;
            text-align: center;
        }
        .ttd-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
        }
        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="kop">
        <div class="kop-logo">
            @if($sekolah && $sekolah->logo)
                <img src="{{ public_path('storage/' . $sekolah->logo) }}" alt="Logo">
            @endif
        </div>
        <div class="kop-text">
            <h1>{{ $sekolah?->nama_sekolah ?? 'SD Negeri 1 Passo' }}</h1>
            <p>{{ $sekolah?->alamat ?? 'Jl. Raya Passo, Kec. Baguala, Kota Ambon, Maluku' }}</p>
        </div>
    </div>

    <div class="title">
        <h2>LAPORAN HASIL BELAJAR PESERTA DIDIK</h2>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 140px;">Nama Peserta Didik</td>
            <td style="width: 10px;">:</td>
            <td><b>{{ $siswa->nama }}</b></td>
            <td style="width: 100px;">Kelas</td>
            <td style="width: 10px;">:</td>
            <td>{{ $kelas?->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Induk / NISN</td>
            <td>:</td>
            <td>{{ $siswa->nis }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $semester == '1' ? 'I (Satu)' : 'II (Dua)' }}</td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td>{{ $sekolah?->nama_sekolah ?? 'SD Negeri 1 Passo' }}</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $tahunAjaran?->nama ?? '-' }}</td>
        </tr>
    </table>

    <table class="nilai">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Mata Pelajaran</th>
                <th style="width: 80px;">Nilai</th>
                <th style="width: 80px;">Predikat</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $index => $nilai)
                @php
                    $angka = $nilai->nilai_angka;
                    $predikat = $angka >= 90 ? 'A' : ($angka >= 75 ? 'B' : ($angka >= 60 ? 'C' : 'D'));
                    $keterangan = $angka >= 90 ? 'Sangat Baik' : ($angka >= 75 ? 'Baik' : ($angka >= 60 ? 'Cukup' : 'Perlu Bimbingan'));
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $nilai->mataPelajaran?->nama ?? '-' }}</td>
                    <td class="text-center">{{ number_format($angka, 0) }}</td>
                    <td class="text-center">{{ $predikat }}</td>
                    <td>{{ $keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data nilai.</td>
                </tr>
            @endforelse
        </tbody>
        @if($nilais->count() > 0)
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Rata-rata Keseluruhan</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($nilais->avg('nilai_angka'), 1) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div style="margin-bottom: 20px;">
        <b>Keterangan Predikat:</b><br>
        A : 90 - 100 (Sangat Baik)<br>
        B : 75 - 89 (Baik)<br>
        C : 60 - 74 (Cukup)<br>
        D : &lt; 60 (Perlu Bimbingan)
    </div>

    <div class="footer-ttd">
        <div class="ttd-box">
            <div>Mengetahui,<br>Orang Tua / Wali</div>
            <div style="margin-top: 60px;">( ...................................... )</div>
        </div>
        <div class="ttd-box">
            <div>Mengetahui,<br>Kepala Sekolah</div>
            <div style="margin-top: 60px;">
                <span class="ttd-name">{{ $sekolah?->kepala_sekolah ?? '......................................' }}</span>
                <br>NIP. {{ $sekolah?->nip_kepala_sekolah ?? '-' }}
            </div>
        </div>
        <div class="ttd-box">
            <div>{{ $sekolah?->kota ?? 'Ambon' }}, {{ now()->translatedFormat('d F Y') }}<br>Wali Kelas</div>
            <div style="margin-top: 60px;">
                <span class="ttd-name">{{ $kelas?->waliKelas?->nama ?? '......................................' }}</span>
                <br>NIP. {{ $kelas?->waliKelas?->nip ?? '-' }}
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
</body>
</html>

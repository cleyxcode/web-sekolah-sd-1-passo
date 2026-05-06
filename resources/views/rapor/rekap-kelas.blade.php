<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Nilai Kelas {{ $kelas?->nama_kelas }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 20px;
            font-size: 12pt;
        }
        .page {
            max-width: 1000px;
            margin: 0 auto;
        }
        /* Kop Surat */
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
        .title p {
            margin: 5px 0 0;
            font-size: 12pt;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 2px 5px;
        }

        table.rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }
        table.rekap th, table.rekap td {
            border: 1px solid #000;
            padding: 6px;
        }
        table.rekap th {
            text-align: center;
            background-color: #fff;
        }
        table.rekap th.th-mapel {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            height: 120px;
            vertical-align: bottom;
            padding: 10px 5px;
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
        <h2>REKAPITULASI NILAI KELAS</h2>
        <p>Semester {{ $semester == '1' ? 'I (Satu)' : 'II (Dua)' }} - {{ $jenisUjian }} - Tahun Pelajaran {{ $tahunAjaran?->nama ?? '-' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 100px;">Kelas</td>
            <td style="width: 10px;">:</td>
            <td>{{ $kelas?->nama_kelas ?? '-' }}</td>
            <td style="width: 100px;">Wali Kelas</td>
            <td style="width: 10px;">:</td>
            <td>{{ $kelas?->waliKelas?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jumlah Siswa</td>
            <td>:</td>
            <td>{{ $siswas->count() }} Orang</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="rekap">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Siswa</th>
                <th style="width:80px;">NIS</th>
                @foreach($mataPelajarans as $mp)
                    <th class="th-mapel">{{ $mp->nama }}</th>
                @endforeach
                <th style="width:80px;">Rata-rata</th>
                <th style="width:80px;">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $idx => $siswa)
                @php
                    $nilaiSiswa = $nilaisGrouped->get($siswa->id, collect());
                    $rata = $nilaiSiswa->avg('nilai_angka');
                    $predikat = $rata >= 90 ? 'A' : ($rata >= 75 ? 'B' : ($rata >= 60 ? 'C' : 'D'));
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td class="text-center">{{ $siswa->nis }}</td>
                    @foreach($mataPelajarans as $mp)
                        @php
                            $nilaiItem = $nilaiSiswa->firstWhere('mata_pelajaran_id', $mp->id);
                            $n = $nilaiItem?->nilai_angka;
                        @endphp
                        <td class="text-center">
                            {{ $n ? number_format($n, 0) : '-' }}
                        </td>
                    @endforeach
                    <td class="text-center">
                        {{ $rata ? number_format($rata, 1) : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $rata ? $predikat : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        @if($siswas->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right; font-weight: bold;">Rata-rata Kelas</td>
                @foreach($mataPelajarans as $mp)
                    @php $avgMp = $nilaisGrouped->flatMap(fn($n) => $n)->where('mata_pelajaran_id', $mp->id)->avg('nilai_angka'); @endphp
                    <td class="text-center" style="font-weight: bold;">
                        {{ $avgMp ? number_format($avgMp, 1) : '-' }}
                    </td>
                @endforeach
                <td class="text-center" style="font-weight: bold;">
                    {{ number_format($nilaisGrouped->flatMap(fn($n) => $n)->avg('nilai_angka'), 1) }}
                </td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

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

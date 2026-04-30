<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Nilai Kelas {{ $kelas?->nama_kelas }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f172a;
            --navy-mid: #1e293b;
            --navy-light: #334155;
            --accent: #2563eb;
            --green: #059669;
            --yellow: #d97706;
            --red: #dc2626;
            --border: #e2e8f0;
            --bg: #f8fafc;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background: #f1f5f9;
            color: var(--navy);
            padding: 30px;
            font-size: 11pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            max-width: 1050px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        /* ===== HEADER KOP ===== */
        .kop {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            padding: 32px 40px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .kop .logo-box {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.12);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            padding: 10px; flex-shrink: 0;
            border: 2px solid rgba(255,255,255,0.15);
        }

        .kop .logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .kop .school-text h1 {
            font-size: 22px; font-weight: 900; color: white;
            text-transform: uppercase; letter-spacing: 1px; line-height: 1.2;
        }

        .kop .school-text p {
            font-size: 12px; color: rgba(255,255,255,0.65);
            font-weight: 500; margin-top: 5px;
        }

        .kop .badge {
            margin-left: auto;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 12px 20px;
            text-align: center;
            min-width: 140px;
        }

        .kop .badge .badge-label {
            font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;
        }

        .kop .badge .badge-val {
            font-size: 14px; font-weight: 800; color: white;
        }

        /* ===== TITLE BAND ===== */
        .title-band {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 20px 40px;
            text-align: center;
        }

        .title-band h2 {
            font-size: 18px; font-weight: 800; color: white;
            text-transform: uppercase; letter-spacing: 2px;
        }

        .title-band p {
            font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.75); margin-top: 4px;
        }

        /* ===== BODY ===== */
        .body-wrap { padding: 30px 40px; }

        /* Class Info Cards */
        .info-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 16px; margin-bottom: 28px;
        }

        .info-card {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex; align-items: center; gap: 14px;
        }

        .info-card .icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .info-card .icon-blue { background: #dbeafe; }
        .info-card .icon-green { background: #dcfce7; }
        .info-card .icon-purple { background: #f3e8ff; }

        .info-card .ic-label {
            font-size: 10px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.06em;
        }

        .info-card .ic-value {
            font-size: 14px; font-weight: 800; color: var(--navy); margin-top: 2px;
        }

        /* ===== TABLE ===== */
        .table-wrap {
            border-radius: 14px;
            overflow: hidden;
            border: 1.5px solid var(--border);
            margin-bottom: 28px;
        }

        table.rekap {
            width: 100%; border-collapse: collapse; font-size: 10pt;
        }

        table.rekap thead tr {
            background: linear-gradient(135deg, var(--navy-mid), var(--navy-light));
        }

        table.rekap thead th {
            padding: 12px 10px;
            text-align: center;
            color: rgba(255,255,255,0.9);
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-right: 1px solid rgba(255,255,255,0.08);
        }

        table.rekap thead th.th-name {
            text-align: left; min-width: 140px;
        }

        table.rekap thead th.th-mapel {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            height: 90px;
            vertical-align: bottom;
            padding: 8px 6px;
            min-width: 52px;
        }

        table.rekap tbody tr { transition: background 0.15s; }
        table.rekap tbody tr:nth-child(even) { background: #f8fafc; }
        table.rekap tbody tr:nth-child(odd) { background: white; }
        table.rekap tbody tr:last-child td { border-bottom: none; }

        table.rekap td {
            padding: 10px 10px;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            color: var(--navy);
        }

        table.rekap td.td-no {
            text-align: center; color: #94a3b8; font-weight: 600; font-size: 10px; width: 36px;
        }

        table.rekap td.td-name { font-weight: 600; }

        table.rekap td.td-nis {
            text-align: center; color: #64748b; font-size: 9.5px; font-weight: 600;
        }

        table.rekap td.td-nilai { text-align: center; }

        .badge-nilai {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 99px;
            font-weight: 700;
            font-size: 10px;
        }

        .bv-A { background: #dcfce7; color: #15803d; }
        .bv-B { background: #dbeafe; color: #1d4ed8; }
        .bv-C { background: #fef9c3; color: #854d0e; }
        .bv-D { background: #fee2e2; color: #991b1b; }
        .bv-empty { color: #cbd5e1; }

        .badge-predikat {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 30px; height: 30px;
            border-radius: 50%;
            font-weight: 900;
            font-size: 12px;
            margin: auto;
        }

        table.rekap tfoot tr {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        }

        table.rekap tfoot td {
            padding: 11px 10px;
            border-top: 2px solid #bae6fd;
            font-weight: 800;
            color: #0369a1;
            font-size: 9.5px;
        }

        /* ===== FOOTER TTD ===== */
        .footer-ttd {
            display: grid; grid-template-columns: 1fr 1fr 1fr;
            gap: 24px; margin-top: 30px;
            text-align: center; font-size: 10.5pt;
        }

        .ttd-box .ttd-title { font-weight: 700; color: #475569; margin-bottom: 3px; }
        .ttd-box .ttd-city { font-size: 10px; color: #64748b; margin-bottom: 12px; }

        .ttd-box .ttd-line {
            width: 80%; margin: 52px auto 8px auto;
            height: 1.5px; background: linear-gradient(90deg, transparent, #94a3b8, transparent);
        }

        .ttd-box .ttd-name { font-weight: 800; color: var(--navy); }
        .ttd-box .ttd-nip { font-size: 9px; color: #94a3b8; margin-top: 2px; }

        /* ===== KETERANGAN ===== */
        .keterangan {
            display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
            margin-top: 24px; padding: 14px 20px;
            background: #f8fafc; border-radius: 12px;
            border: 1px solid var(--border);
        }

        .keterangan .ket-title {
            font-size: 10px; font-weight: 800; color: #475569;
            text-transform: uppercase; letter-spacing: 0.06em; margin-right: 4px;
        }

        .ket-chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 12px; border-radius: 99px; font-size: 10px; font-weight: 700;
        }

        @media print {
            body { padding: 0; background: white; }
            .page { border-radius: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- KOP SEKOLAH --}}
    <div class="kop">
        @if($sekolah && $sekolah->logo)
        <div class="logo-box">
            <img src="{{ public_path('storage/' . $sekolah->logo) }}" alt="Logo Sekolah">
        </div>
        @else
        <div class="logo-box" style="font-size:28px;font-weight:900;color:white;font-style:italic;">SD</div>
        @endif

        <div class="school-text">
            <h1>{{ $sekolah?->nama_sekolah ?? 'SD Negeri 1 Passo' }}</h1>
            <p>{{ $sekolah?->alamat ?? 'Jl. Raya Passo, Kec. Baguala, Kota Ambon, Maluku' }}
                @if($sekolah?->no_telepon) &nbsp;|&nbsp; Telp: {{ $sekolah->no_telepon }} @endif
            </p>
        </div>

        <div class="badge">
            <div class="badge-label">Tahun Pelajaran</div>
            <div class="badge-val">{{ $tahunAjaran?->nama ?? '-' }}</div>
        </div>
    </div>

    {{-- TITLE BAND --}}
    <div class="title-band">
        <h2>Rekap Nilai Kelas</h2>
        <p>
            Semester {{ $semester == '1' ? 'I (Satu)' : 'II (Dua)' }}
            &nbsp;&mdash;&nbsp; {{ $jenisUjian }}
            &nbsp;&mdash;&nbsp; Tahun Pelajaran {{ $tahunAjaran?->nama ?? '-' }}
        </p>
    </div>

    <div class="body-wrap">

        {{-- INFO KELAS --}}
        <div class="info-row">
            <div class="info-card">
                <div class="icon icon-blue">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#2563eb"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <div class="ic-label">Kelas</div>
                    <div class="ic-value">{{ $kelas?->nama_kelas ?? '-' }}</div>
                </div>
            </div>
            <div class="info-card">
                <div class="icon icon-green">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#059669"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div class="ic-label">Wali Kelas</div>
                    <div class="ic-value">{{ $kelas?->waliKelas?->nama ?? '-' }}</div>
                </div>
            </div>
            <div class="info-card">
                <div class="icon icon-purple">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="ic-label">Jumlah Siswa</div>
                    <div class="ic-value">{{ $siswas->count() }} Siswa</div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table class="rekap">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th class="th-name" style="text-align:left;">Nama Siswa</th>
                        <th style="width:72px;">NIS</th>
                        @foreach($mataPelajarans as $mp)
                            <th class="th-mapel">{{ $mp->nama }}</th>
                        @endforeach
                        <th style="width:68px;color:#a5f3fc;">Rata-rata</th>
                        <th style="width:60px;color:#bbf7d0;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $idx => $siswa)
                        @php
                            $nilaiSiswa = $nilaisGrouped->get($siswa->id, collect());
                            $rata = $nilaiSiswa->avg('nilai_angka');
                            $predikat = $rata >= 90 ? 'A' : ($rata >= 75 ? 'B' : ($rata >= 60 ? 'C' : 'D'));
                            $bvClass = $rata ? 'bv-'.$predikat : '';
                        @endphp
                        <tr>
                            <td class="td-no">{{ $idx + 1 }}</td>
                            <td class="td-name">{{ $siswa->nama }}</td>
                            <td class="td-nis">{{ $siswa->nis }}</td>
                            @foreach($mataPelajarans as $mp)
                                @php
                                    $nilaiItem = $nilaiSiswa->firstWhere('mata_pelajaran_id', $mp->id);
                                    $n = $nilaiItem?->nilai_angka;
                                    $nClass = $n ? ($n >= 90 ? 'bv-A' : ($n >= 75 ? 'bv-B' : ($n >= 60 ? 'bv-C' : 'bv-D'))) : 'bv-empty';
                                @endphp
                                <td class="td-nilai">
                                    @if($n)
                                        <span class="badge-nilai {{ $nClass }}">{{ number_format($n, 0) }}</span>
                                    @else
                                        <span class="bv-empty">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="td-nilai">
                                @if($rata)
                                    <span class="badge-nilai {{ $bvClass }}">{{ number_format($rata, 1) }}</span>
                                @else
                                    <span class="bv-empty">—</span>
                                @endif
                            </td>
                            <td class="td-nilai">
                                @if($rata)
                                    <span class="badge-predikat {{ $bvClass }}">{{ $predikat }}</span>
                                @else
                                    <span class="bv-empty">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if($siswas->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;">Rata-rata Kelas</td>
                        @foreach($mataPelajarans as $mp)
                            @php $avgMp = $nilaisGrouped->flatMap(fn($n) => $n)->where('mata_pelajaran_id', $mp->id)->avg('nilai_angka'); @endphp
                            <td style="text-align:center;">
                                <span class="badge-nilai bv-B">{{ $avgMp ? number_format($avgMp, 1) : '—' }}</span>
                            </td>
                        @endforeach
                        <td style="text-align:center;">
                            <span class="badge-nilai bv-B">{{ number_format($nilaisGrouped->flatMap(fn($n) => $n)->avg('nilai_angka'), 1) }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- TANDA TANGAN --}}
        <div class="footer-ttd">
            <div class="ttd-box">
                <div class="ttd-title">Orang Tua / Wali</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">( ................................................ )</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-city">{{ $sekolah?->kota ?? 'Ambon' }}, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="ttd-title">Wali Kelas</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $kelas?->waliKelas?->nama ?? '...........................................' }}</div>
                <div class="ttd-nip">NIP. {{ $kelas?->waliKelas?->nip ?? '-' }}</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-title">Kepala Sekolah</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $sekolah?->kepala_sekolah ?? '...........................................' }}</div>
                <div class="ttd-nip">NIP. {{ $sekolah?->nip_kepala_sekolah ?? '-' }}</div>
            </div>
        </div>

        {{-- KETERANGAN --}}
        <div class="keterangan">
            <span class="ket-title">Keterangan:</span>
            <span class="ket-chip bv-A">A &ge; 90 = Sangat Baik</span>
            <span class="ket-chip bv-B">B 75–89 = Baik</span>
            <span class="ket-chip bv-C">C 60–74 = Cukup</span>
            <span class="ket-chip bv-D">D &lt; 60 = Perlu Bimbingan</span>
            <span style="font-size:10px;color:#94a3b8;">&nbsp;( — ) = Belum ada nilai</span>
        </div>

    </div>{{-- end body-wrap --}}
</div>{{-- end page --}}
</body>
</html>

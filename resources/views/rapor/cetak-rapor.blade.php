<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Rapor — {{ $siswa->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f172a;
            --navy-mid: #1e293b;
            --navy-light: #334155;
            --accent: #2563eb;
            --green: #059669;
            --bg: #f8fafc;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background: #f1f5f9;
            color: var(--navy);
            padding: 30px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            max-width: 820px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        /* ===== KOP ===== */
        .kop {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            padding: 32px 40px;
            display: flex; align-items: center; gap: 24px;
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
            font-size: 21px; font-weight: 900; color: white;
            text-transform: uppercase; letter-spacing: 1px; line-height: 1.2;
        }

        .kop .school-text p { font-size: 12px; color: rgba(255,255,255,0.6); font-weight: 500; margin-top: 5px; }

        /* ===== TITLE BAND ===== */
        .title-band {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 18px 40px; text-align: center;
        }

        .title-band h2 {
            font-size: 17px; font-weight: 800; color: white;
            text-transform: uppercase; letter-spacing: 2px;
        }

        .title-band p { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.75); margin-top: 4px; }

        /* ===== BODY ===== */
        .body-wrap { padding: 30px 40px; }

        /* ===== STUDENT HERO ===== */
        .student-hero {
            display: flex; align-items: center; gap: 24px;
            background: linear-gradient(135deg, #f8fafc, #f0f9ff);
            border: 1.5px solid #e0f2fe;
            border-radius: 18px;
            padding: 24px 28px;
            margin-bottom: 28px;
        }

        .stu-avatar {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 900; color: white;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        }

        .stu-info { flex: 1; }
        .stu-name { font-size: 20px; font-weight: 900; color: var(--navy); margin-bottom: 8px; }

        .stu-chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: white; border: 1.5px solid var(--border);
            border-radius: 99px; padding: 4px 12px;
            font-size: 11px; font-weight: 700; color: var(--navy-light);
        }

        .stu-status {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            background: white; border: 1.5px solid #86efac;
            border-radius: 14px; padding: 12px 20px;
            min-width: 110px;
        }

        .stu-status .st-label { font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
        .stu-status .st-val { font-size: 15px; font-weight: 900; color: #15803d; }

        /* ===== IDENTITY GRID ===== */
        .identity-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 1px; background: var(--border);
            border-radius: 14px; overflow: hidden;
            border: 1.5px solid var(--border);
            margin-bottom: 28px;
        }

        .id-cell {
            background: #f8fafc; padding: 12px 18px;
            display: flex; flex-direction: column; gap: 2px;
        }

        .id-cell.dark-cell { background: white; }
        .id-cell .id-label { font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
        .id-cell .id-val { font-size: 13px; font-weight: 700; color: var(--navy); }

        /* ===== NILAI TABLE ===== */
        .section-tag {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 14px;
        }

        .section-tag .tag-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .section-tag h3 { font-size: 13px; font-weight: 800; color: var(--navy); text-transform: uppercase; letter-spacing: 0.06em; }

        .table-wrap { border-radius: 14px; overflow: hidden; border: 1.5px solid var(--border); margin-bottom: 28px; }

        table.nilai-tbl {
            width: 100%; border-collapse: collapse; font-size: 11pt;
        }

        table.nilai-tbl thead tr {
            background: linear-gradient(135deg, var(--navy-mid), var(--navy-light));
        }

        table.nilai-tbl thead th {
            padding: 12px 16px; color: rgba(255,255,255,0.85);
            font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; text-align: left;
        }

        table.nilai-tbl thead th.th-center { text-align: center; }

        table.nilai-tbl tbody tr { transition: background 0.15s; }
        table.nilai-tbl tbody tr:nth-child(even) { background: #f8fafc; }
        table.nilai-tbl tbody tr:nth-child(odd) { background: white; }
        table.nilai-tbl tbody tr:last-child td { border-bottom: none; }

        table.nilai-tbl td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        table.nilai-tbl td.td-center { text-align: center; }
        table.nilai-tbl td.td-mapel { font-weight: 600; color: var(--navy); }

        .badge-nilai {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 3px 14px; border-radius: 99px;
            font-weight: 800; font-size: 13px; min-width: 50px;
        }

        .bv-A { background: #dcfce7; color: #15803d; }
        .bv-B { background: #dbeafe; color: #1d4ed8; }
        .bv-C { background: #fef9c3; color: #854d0e; }
        .bv-D { background: #fee2e2; color: #991b1b; }

        .chip-sm {
            display: inline-block; padding: 2px 10px; border-radius: 99px;
            font-size: 10px; font-weight: 700;
        }

        .chip-uts { background: #f3e8ff; color: #7c3aed; }
        .chip-uas { background: #fff7ed; color: #c2410c; }
        .chip-uh { background: #f0fdf4; color: #166534; }

        table.nilai-tbl tfoot tr {
            background: linear-gradient(135deg, #0f172a, #1e293b);
        }

        table.nilai-tbl tfoot td {
            padding: 12px 16px; font-weight: 800; color: white; border: none;
        }

        /* ===== SUMMARY ===== */
        .summary-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 16px; margin-bottom: 28px;
        }

        .sum-card {
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 14px; padding: 20px;
        }

        .sum-card h4 {
            font-size: 11px; font-weight: 800; color: #475569;
            text-transform: uppercase; letter-spacing: 0.07em;
            margin-bottom: 14px; padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .sum-row {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 8px;
        }

        .sum-row span:first-child { font-size: 12px; color: #64748b; font-weight: 500; }
        .sum-row span:last-child { font-size: 13px; font-weight: 800; color: var(--navy); }

        /* ===== TTD ===== */
        .footer-ttd {
            display: grid; grid-template-columns: 1fr 1fr 1fr;
            gap: 20px; margin-top: 28px; text-align: center; font-size: 10.5pt;
        }

        .ttd-box .ttd-title { font-weight: 700; color: #475569; font-size: 12px; }
        .ttd-box .ttd-city { font-size: 10px; color: #64748b; margin-bottom: 4px; }

        .ttd-box .ttd-line {
            width: 80%; margin: 52px auto 8px auto;
            height: 1.5px; background: linear-gradient(90deg, transparent, #94a3b8, transparent);
        }

        .ttd-box .ttd-name { font-weight: 800; color: var(--navy); font-size: 12px; }
        .ttd-box .ttd-nip { font-size: 9px; color: #94a3b8; margin-top: 2px; }

        /* ===== LEGEND ===== */
        .keterangan {
            display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
            margin-top: 24px; padding: 14px 20px;
            background: #f8fafc; border-radius: 12px; border: 1px solid var(--border);
        }

        .keterangan .ket-title {
            font-size: 10px; font-weight: 800; color: #475569;
            text-transform: uppercase; letter-spacing: 0.06em;
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
        <div class="logo-box" style="font-size:24px;font-weight:900;color:white;font-style:italic;">SD</div>
        @endif
        <div class="school-text">
            <h1>{{ $sekolah?->nama_sekolah ?? 'SD Negeri 1 Passo' }}</h1>
            <p>{{ $sekolah?->alamat ?? 'Jl. Raya Passo, Kec. Baguala, Kota Ambon, Maluku' }}
                @if($sekolah?->no_telepon) &nbsp;|&nbsp; Telp: {{ $sekolah->no_telepon }} @endif
            </p>
        </div>
    </div>

    {{-- TITLE BAND --}}
    <div class="title-band">
        <h2>Laporan Hasil Belajar Siswa (E-Rapor)</h2>
        <p>Semester {{ $semester == '1' ? 'I (Satu)' : 'II (Dua)' }} &mdash; {{ $jenisUjian }} &mdash; Tahun Pelajaran {{ $tahunAjaran?->nama ?? '-' }}</p>
    </div>

    <div class="body-wrap">

        {{-- STUDENT HERO --}}
        <div class="student-hero">
            <div class="stu-avatar">{{ substr($siswa->nama, 0, 1) }}</div>
            <div class="stu-info">
                <div class="stu-name">{{ $siswa->nama }}</div>
                <div class="stu-chips">
                    <span class="chip">NIS: {{ $siswa->nis }}</span>
                    <span class="chip">Kelas {{ $kelas?->nama_kelas ?? '-' }}</span>
                    <span class="chip">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    <span class="chip">Wali Kelas: {{ $kelas?->waliKelas?->nama ?? '-' }}</span>
                </div>
            </div>
            <div class="stu-status">
                <div class="st-label">Status</div>
                <div class="st-val">{{ strtoupper($siswa->status) }}</div>
            </div>
        </div>

        {{-- NILAI TABLE --}}
        <div class="section-tag">
            <div class="tag-dot"></div>
            <h3>A. Nilai Akademik</h3>
        </div>

        <div class="table-wrap">
            <table class="nilai-tbl">
                <thead>
                    <tr>
                        <th style="width:36px;" class="th-center">No</th>
                        <th>Mata Pelajaran</th>
                        <th class="th-center" style="width:80px;">Nilai</th>
                        <th class="th-center" style="width:70px;">Predikat</th>
                        <th style="width:150px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $index => $nilai)
                        @php
                            $angka     = $nilai->nilai_angka;
                            $predikat  = $angka >= 90 ? 'A' : ($angka >= 75 ? 'B' : ($angka >= 60 ? 'C' : 'D'));
                            $keterangan = $angka >= 90 ? 'Sangat Baik' : ($angka >= 75 ? 'Baik' : ($angka >= 60 ? 'Cukup' : 'Perlu Bimbingan'));
                            $bvClass   = 'bv-'.$predikat;
                        @endphp
                        <tr>
                            <td class="td-center" style="color:#94a3b8;font-weight:600;">{{ $index + 1 }}</td>
                            <td class="td-mapel">{{ $nilai->mataPelajaran?->nama ?? '-' }}</td>
                            <td class="td-center">
                                <span class="badge-nilai {{ $bvClass }}">{{ number_format($angka, 0) }}</span>
                            </td>
                            <td class="td-center">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-weight:900;font-size:13px;" class="{{ $bvClass }}">{{ $predikat }}</span>
                            </td>
                            <td style="font-size:11px;font-weight:600;color:#64748b;">{{ $keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;font-style:italic;font-size:12px;">
                                Belum ada data nilai untuk siswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($nilais->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:right;font-size:11px;">Rata-rata Keseluruhan</td>
                        <td class="td-center">
                            <span class="badge-nilai bv-B" style="background:rgba(255,255,255,0.15);color:white;">{{ number_format($nilais->avg('nilai_angka'), 1) }}</span>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- SUMMARY --}}
        @if($nilais->count() > 0)
        <div class="summary-grid">
            <div class="sum-card">
                <h4>📊 Ringkasan Nilai</h4>
                <div class="sum-row">
                    <span>Nilai Tertinggi</span>
                    <span>{{ number_format($nilais->max('nilai_angka'), 0) }}</span>
                </div>
                <div class="sum-row">
                    <span>Nilai Terendah</span>
                    <span>{{ number_format($nilais->min('nilai_angka'), 0) }}</span>
                </div>
                <div class="sum-row">
                    <span>Rata-rata</span>
                    <span>{{ number_format($nilais->avg('nilai_angka'), 1) }}</span>
                </div>
                <div class="sum-row">
                    <span>Jumlah Mata Pelajaran</span>
                    <span>{{ $nilais->count() }}</span>
                </div>
            </div>
            <div class="sum-card">
                <h4>🏆 Keterangan Predikat</h4>
                <div class="sum-row"><span>90 – 100</span><span class="badge-nilai bv-A" style="font-size:11px;padding:2px 10px;">A — Sangat Baik</span></div>
                <div class="sum-row"><span>75 – 89</span><span class="badge-nilai bv-B" style="font-size:11px;padding:2px 10px;">B — Baik</span></div>
                <div class="sum-row"><span>60 – 74</span><span class="badge-nilai bv-C" style="font-size:11px;padding:2px 10px;">C — Cukup</span></div>
                <div class="sum-row"><span>0 – 59</span><span class="badge-nilai bv-D" style="font-size:11px;padding:2px 10px;">D — Perlu Bimbingan</span></div>
            </div>
        </div>
        @endif

        {{-- TTD --}}
        <div class="footer-ttd">
            <div class="ttd-box">
                <div class="ttd-title">Orang Tua / Wali</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">( ..................................... )</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-city">{{ $sekolah?->kota ?? 'Ambon' }}, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="ttd-title">Wali Kelas</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $kelas?->waliKelas?->nama ?? '....................................' }}</div>
                <div class="ttd-nip">NIP. {{ $kelas?->waliKelas?->nip ?? '-' }}</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-title">Kepala Sekolah</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $sekolah?->kepala_sekolah ?? '....................................' }}</div>
                <div class="ttd-nip">NIP. {{ $sekolah?->nip_kepala_sekolah ?? '-' }}</div>
            </div>
        </div>

        {{-- LEGEND --}}
        <div class="keterangan">
            <span class="ket-title">Keterangan:</span>
            <span class="ket-chip bv-A">A &ge; 90 = Sangat Baik</span>
            <span class="ket-chip bv-B">B 75–89 = Baik</span>
            <span class="ket-chip bv-C">C 60–74 = Cukup</span>
            <span class="ket-chip bv-D">D &lt; 60 = Perlu Bimbingan</span>
        </div>

    </div>{{-- end body-wrap --}}
</div>{{-- end page --}}
</body>
</html>

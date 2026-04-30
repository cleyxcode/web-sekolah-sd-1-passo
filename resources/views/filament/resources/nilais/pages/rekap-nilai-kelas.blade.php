<x-filament-panels::page>

    {{-- Filter Form --}}
    <x-filament::section>
        <x-slot name="heading">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                Filter Rekap Nilai
            </div>
        </x-slot>
        <x-slot name="description">Pilih kelas, semester, jenis ujian, dan tahun ajaran untuk menampilkan rekap nilai kelas.</x-slot>
        {{ $this->form }}
    </x-filament::section>

    @php
        $previewData     = $this->getPreviewData();
        $kelas           = $previewData['kelas'] ?? null;
        $semester        = $previewData['semester'] ?? null;
        $jenisUjian      = $previewData['jenisUjian'] ?? null;
        $tahunAjaran     = $previewData['tahunAjaran'] ?? null;
        $siswas          = $previewData['siswas'] ?? collect();
        $nilaisGrouped   = $previewData['nilaisGrouped'] ?? collect();
        $mataPelajarans  = $previewData['mataPelajarans'] ?? collect();
    @endphp

    @if($kelas)
    <x-filament::section>
        <x-slot name="heading">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#059669,#047857);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                Rekap Nilai — Kelas {{ $kelas->nama_kelas }}
            </div>
        </x-slot>
        <x-slot name="description">
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                <span style="display:inline-flex;align-items:center;gap:5px;font-weight:600;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Semester {{ $semester == '1' ? 'I' : 'II' }}
                </span>
                <span style="display:inline-flex;align-items:center;gap:5px;font-weight:600;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ $jenisUjian }}
                </span>
                <span style="display:inline-flex;align-items:center;gap:5px;font-weight:600;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    TA {{ $tahunAjaran?->nama ?? '-' }}
                </span>
                <span style="display:inline-flex;align-items:center;gap:5px;font-weight:600;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Wali: {{ $kelas->waliKelas?->nama ?? '-' }}
                </span>
            </div>
        </x-slot>

        @if($siswas->count() > 0 && $mataPelajarans->count() > 0)

        {{-- Summary Stats --}}
        @php
            $allNilaiFlat = $nilaisGrouped->flatten();
            $totalRata = $allNilaiFlat->avg('nilai_angka');
            $siswaA = $siswas->filter(fn($s) => ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) >= 90)->count();
            $siswaB = $siswas->filter(fn($s) => ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) >= 75 && ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) < 90)->count();
            $siswaC = $siswas->filter(fn($s) => ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) >= 60 && ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) < 75)->count();
            $siswaD = $siswas->filter(fn($s) => ($nilaisGrouped->get($s->id, collect())->avg('nilai_angka') ?? 0) < 60 && $nilaisGrouped->get($s->id, collect())->count() > 0)->count();
        @endphp

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
            <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;border-radius:16px;padding:18px 20px;">
                <div style="font-size:2rem;font-weight:900;color:#15803d;line-height:1;">{{ $siswas->count() }}</div>
                <div style="font-size:0.78rem;font-weight:700;color:#16a34a;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Total Siswa</div>
            </div>
            <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #93c5fd;border-radius:16px;padding:18px 20px;">
                <div style="font-size:2rem;font-weight:900;color:#1d4ed8;line-height:1;">{{ $mataPelajarans->count() }}</div>
                <div style="font-size:0.78rem;font-weight:700;color:#2563eb;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Mata Pelajaran</div>
            </div>
            <div style="background:linear-gradient(135deg,#fdf4ff,#f3e8ff);border:1.5px solid #d8b4fe;border-radius:16px;padding:18px 20px;">
                <div style="font-size:2rem;font-weight:900;color:#7e22ce;line-height:1;">{{ $totalRata ? number_format($totalRata, 1) : '-' }}</div>
                <div style="font-size:0.78rem;font-weight:700;color:#9333ea;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Rata-rata Kelas</div>
            </div>
            <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1.5px solid #fda4af;border-radius:16px;padding:18px 20px;">
                <div style="font-size:2rem;font-weight:900;color:#b45309;line-height:1;">{{ $totalRata >= 90 ? 'A' : ($totalRata >= 75 ? 'B' : ($totalRata >= 60 ? 'C' : 'D')) }}</div>
                <div style="font-size:0.78rem;font-weight:700;color:#d97706;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Predikat Kelas</div>
            </div>
        </div>

        {{-- Predikat Distribution --}}
        <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;">
            @foreach(['A' => ['label'=>'Sangat Baik','bg'=>'#dcfce7','text'=>'#15803d','border'=>'#86efac'], 'B' => ['label'=>'Baik','bg'=>'#dbeafe','text'=>'#1d4ed8','border'=>'#93c5fd'], 'C' => ['label'=>'Cukup','bg'=>'#fef9c3','text'=>'#854d0e','border'=>'#fde047'], 'D' => ['label'=>'Perlu Bimbingan','bg'=>'#fee2e2','text'=>'#991b1b','border'=>'#fca5a5']] as $grade => $style)
            @php $count = ${'siswa'.$grade}; @endphp
            <div style="display:flex;align-items:center;gap:8px;background:{{ $style['bg'] }};border:1.5px solid {{ $style['border'] }};border-radius:99px;padding:6px 14px;">
                <span style="font-weight:900;font-size:1rem;color:{{ $style['text'] }};">{{ $count }}</span>
                <span style="font-size:0.75rem;font-weight:700;color:{{ $style['text'] }};">siswa predikat {{ $grade }} ({{ $style['label'] }})</span>
            </div>
            @endforeach
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto;border-radius:14px;border:1.5px solid var(--gray-200, #e5e7eb);box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
                <thead>
                    <tr style="background:linear-gradient(135deg,#1e293b,#334155);">
                        <th style="padding:14px 16px;text-align:center;color:white;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;border-right:1px solid rgba(255,255,255,0.1);width:44px;">#</th>
                        <th style="padding:14px 16px;text-align:left;color:white;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;border-right:1px solid rgba(255,255,255,0.1);min-width:160px;">Nama Siswa</th>
                        <th style="padding:14px 12px;text-align:center;color:rgba(255,255,255,0.8);font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;border-right:1px solid rgba(255,255,255,0.1);width:80px;">NIS</th>
                        @foreach($mataPelajarans as $mp)
                            <th style="padding:14px 8px;text-align:center;color:rgba(255,255,255,0.85);font-weight:700;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.04em;border-right:1px solid rgba(255,255,255,0.1);min-width:64px;max-width:80px;">
                                {{ $mp->nama }}
                            </th>
                        @endforeach
                        <th style="padding:14px 12px;text-align:center;color:#a5f3fc;font-weight:800;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;border-right:1px solid rgba(255,255,255,0.1);width:72px;">Rata-rata</th>
                        <th style="padding:14px 12px;text-align:center;color:#bbf7d0;font-weight:800;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;width:68px;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $idx => $siswa)
                        @php
                            $nilaiSiswa = $nilaisGrouped->get($siswa->id, collect());
                            $rata = $nilaiSiswa->avg('nilai_angka');
                            $predikat = $rata >= 90 ? 'A' : ($rata >= 75 ? 'B' : ($rata >= 60 ? 'C' : 'D'));
                            $predikatBg    = $rata >= 90 ? '#dcfce7' : ($rata >= 75 ? '#dbeafe' : ($rata >= 60 ? '#fef9c3' : '#fee2e2'));
                            $predikatText  = $rata >= 90 ? '#15803d' : ($rata >= 75 ? '#1d4ed8' : ($rata >= 60 ? '#854d0e' : '#991b1b'));
                            $rowBg = $idx % 2 === 0 ? 'var(--white, #ffffff)' : 'var(--gray-50, #f8fafc)';
                        @endphp
                        <tr style="background:{{ $rowBg }};transition:background 0.15s;" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='{{ $rowBg }}'">
                            <td style="padding:12px 16px;text-align:center;color:var(--gray-400,#9ca3af);font-weight:600;border-bottom:1px solid var(--gray-100,#f1f5f9);border-right:1px solid var(--gray-100,#f1f5f9);">{{ $idx + 1 }}</td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--gray-900,#0f172a);border-bottom:1px solid var(--gray-100,#f1f5f9);border-right:1px solid var(--gray-100,#f1f5f9);">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:800;flex-shrink:0;">{{ substr($siswa->nama, 0, 1) }}</div>
                                    {{ $siswa->nama }}
                                </div>
                            </td>
                            <td style="padding:12px 12px;text-align:center;color:var(--gray-500,#6b7280);font-size:0.75rem;font-weight:600;border-bottom:1px solid var(--gray-100,#f1f5f9);border-right:1px solid var(--gray-100,#f1f5f9);">{{ $siswa->nis }}</td>
                            @foreach($mataPelajarans as $mp)
                                @php
                                    $nilaiItem = $nilaiSiswa->firstWhere('mata_pelajaran_id', $mp->id);
                                    $n = $nilaiItem?->nilai_angka;
                                    $bg = $n ? ($n >= 90 ? '#dcfce7' : ($n >= 75 ? '#dbeafe' : ($n >= 60 ? '#fef9c3' : '#fee2e2'))) : 'transparent';
                                    $tc = $n ? ($n >= 90 ? '#15803d' : ($n >= 75 ? '#1d4ed8' : ($n >= 60 ? '#854d0e' : '#991b1b'))) : '#9ca3af';
                                @endphp
                                <td style="padding:10px 8px;text-align:center;border-bottom:1px solid var(--gray-100,#f1f5f9);border-right:1px solid var(--gray-100,#f1f5f9);">
                                    @if($n)
                                        <span style="display:inline-block;background:{{ $bg }};color:{{ $tc }};font-weight:700;font-size:0.78rem;padding:3px 10px;border-radius:99px;">{{ number_format($n, 0) }}</span>
                                    @else
                                        <span style="color:#cbd5e1;font-weight:600;">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td style="padding:10px 12px;text-align:center;border-bottom:1px solid var(--gray-100,#f1f5f9);border-right:1px solid var(--gray-100,#f1f5f9);">
                                @if($rata)
                                    <span style="display:inline-block;background:{{ $predikatBg }};color:{{ $predikatText }};font-weight:800;font-size:0.85rem;padding:4px 12px;border-radius:99px;">{{ number_format($rata, 1) }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td style="padding:10px 12px;text-align:center;border-bottom:1px solid var(--gray-100,#f1f5f9);">
                                @if($rata)
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:{{ $predikatBg }};color:{{ $predikatText }};font-weight:900;font-size:1rem;margin:auto;">{{ $predikat }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- Footer: Rata-rata per Mapel --}}
                @if($siswas->count() > 0)
                <tfoot>
                    <tr style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);">
                        <td colspan="3" style="padding:12px 16px;text-align:right;font-weight:800;color:#1e293b;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-top:2px solid #e2e8f0;border-right:1px solid #e2e8f0;">Rata-rata Kelas</td>
                        @foreach($mataPelajarans as $mp)
                            @php $avgMp = $nilaisGrouped->flatMap(fn($n) => $n)->where('mata_pelajaran_id', $mp->id)->avg('nilai_angka'); @endphp
                            <td style="padding:12px 8px;text-align:center;border-top:2px solid #e2e8f0;border-right:1px solid #e2e8f0;">
                                @if($avgMp)
                                    <span style="display:inline-block;background:#e0f2fe;color:#0369a1;font-weight:800;font-size:0.78rem;padding:3px 10px;border-radius:99px;">{{ number_format($avgMp, 1) }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                        @endforeach
                        <td style="padding:12px;text-align:center;border-top:2px solid #e2e8f0;border-right:1px solid #e2e8f0;">
                            <span style="display:inline-block;background:#e0f2fe;color:#0369a1;font-weight:900;font-size:0.9rem;padding:4px 12px;border-radius:99px;">{{ number_format($totalRata, 1) }}</span>
                        </td>
                        <td style="border-top:2px solid #e2e8f0;"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Legend --}}
        <div style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap;padding:16px 20px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
            <span style="font-size:0.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-right:4px;">Keterangan:</span>
            @foreach(['A' => ['range'=>'≥ 90','label'=>'Sangat Baik','bg'=>'#dcfce7','text'=>'#15803d'], 'B' => ['range'=>'75–89','label'=>'Baik','bg'=>'#dbeafe','text'=>'#1d4ed8'], 'C' => ['range'=>'60–74','label'=>'Cukup','bg'=>'#fef9c3','text'=>'#854d0e'], 'D' => ['range'=>'< 60','label'=>'Perlu Bimbingan','bg'=>'#fee2e2','text'=>'#991b1b']] as $g => $s)
            <span style="display:inline-flex;align-items:center;gap:6px;background:{{ $s['bg'] }};color:{{ $s['text'] }};padding:4px 12px;border-radius:99px;font-size:0.72rem;font-weight:700;">
                <strong style="font-size:0.85rem;">{{ $g }}</strong> ({{ $s['range'] }}) = {{ $s['label'] }}
            </span>
            @endforeach
        </div>

        @elseif($siswas->count() === 0)
            <div style="text-align:center;padding:60px 20px;">
                <div style="width:64px;height:64px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p style="font-weight:700;color:#475569;font-size:1rem;">Tidak ada siswa aktif di kelas ini.</p>
            </div>
        @else
            <div style="text-align:center;padding:60px 20px;">
                <div style="width:64px;height:64px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="#3b82f6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p style="font-weight:700;color:#475569;font-size:1rem;">Belum ada data nilai untuk filter yang dipilih.</p>
                <p style="color:#94a3b8;font-size:0.875rem;margin-top:6px;">Silakan input nilai siswa terlebih dahulu di menu <strong>Nilai</strong>.</p>
            </div>
        @endif

    </x-filament::section>
    @else
        <x-filament::section>
            <div style="text-align:center;padding:60px 20px;">
                <div style="width:72px;height:72px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border:2px solid #86efac;">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#16a34a"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <p style="font-weight:800;color:#1e293b;font-size:1.2rem;">Pilih Kelas untuk Memulai</p>
                <p style="color:#64748b;font-size:0.875rem;margin-top:6px;max-width:360px;margin-left:auto;margin-right:auto;line-height:1.6;">Gunakan form filter di atas untuk memilih kelas, semester, jenis ujian, dan tahun ajaran.</p>
            </div>
        </x-filament::section>
    @endif

</x-filament-panels::page>

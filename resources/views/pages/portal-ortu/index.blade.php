@extends('layouts.portal-ortu')

@section('title', 'Dashboard Wali Murid')

@section('content')
<div class="page-wrapper fade-up">

    @if($anak_anak->isEmpty())
        <div class="empty-warning">
            <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" style="margin:0 auto 16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <h3 style="font-size:1.4rem;font-weight:800;color:#1e293b;margin:0 0 10px;">Belum Ada Data Anak</h3>
            <p style="color:#64748b;font-size:0.9rem;line-height:1.7;margin:0;">Akun Anda belum dihubungkan dengan profil siswa mana pun. Silakan hubungi Tata Usaha sekolah untuk melakukan sinkronisasi data.</p>
        </div>
    @else

        {{-- TABS (jika anak > 1) --}}
        @if($anak_anak->count() > 1)
        <div class="tab-strip">
            @foreach($anak_anak as $idx => $anak)
            <button id="tab-btn-{{ $idx }}" onclick="switchTab({{ $idx }})"
                class="tab-btn-item {{ $idx === 0 ? 'active' : 'inactive' }}">
                <span class="tab-avatar">{{ substr($anak->nama, 0, 1) }}</span>
                {{ explode(' ', trim($anak->nama))[0] }}
            </button>
            @endforeach
        </div>
        @endif

        @foreach($anak_anak as $idx => $anak)
        <div id="tab-pane-{{ $idx }}" class="tab-pane {{ $idx === 0 ? 'active' : '' }}">

            {{-- ===== ROW 1: HERO PROFIL SISWA ===== --}}
            <div class="grid-3-1 mb-6">
                <div class="hero-card">
                    <div class="inner">
                        <div class="hero-avatar">{{ substr($anak->nama, 0, 1) }}</div>
                        <div>
                            <div class="hero-badge">Profil Siswa</div>
                            <h2 class="hero-name">{{ $anak->nama }}</h2>
                            <div class="hero-meta">
                                <span class="hero-chip">NIS: {{ $anak->nis }}</span>
                                <span class="hero-chip">Kelas: {{ $anak->kelas->nama_kelas ?? '-' }}</span>
                                <span class="hero-chip">{{ ucfirst($anak->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card {{ $anak->status === 'aktif' ? 'status-card-aktif' : 'status-card-other' }}"
                    style="display:flex;align-items:center;justify-content:center;text-align:center;padding:1.75rem;">
                    <div>
                        <div class="status-icon {{ $anak->status === 'aktif' ? 'status-icon-aktif' : 'status-icon-other' }}">
                            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="status-label">Status Akademik</div>
                        <div class="{{ $anak->status === 'aktif' ? 'status-value-aktif' : 'status-value-other' }}">{{ strtoupper($anak->status) }}</div>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 2: STATISTIK KEHADIRAN ===== --}}
            <div class="mb-6">
                <div class="card">
                    <div class="card-body">
                        <div class="section-header" style="margin-bottom:1.25rem;">
                            <div class="section-icon icon-green">
                                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            </div>
                            <div>
                                <div class="section-title">Statistik Kehadiran</div>
                                <div class="section-sub">{{ $anak->stat_total }} hari tercatat &bull; Tingkat kehadiran: <strong style="color:#16a34a;">{{ $anak->pct_hadir }}%</strong></div>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div style="height:10px;background:#f1f5f9;border-radius:99px;margin-bottom:1.25rem;overflow:hidden;">
                            <div style="height:100%;width:{{ $anak->pct_hadir }}%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:99px;transition:width 1s ease;"></div>
                        </div>

                        <div class="stats-grid">
                            <div class="stat-pill stat-hadir">
                                <div class="stat-num">{{ $anak->stat_hadir }}</div>
                                <div class="stat-lbl">Hadir</div>
                            </div>
                            <div class="stat-pill stat-sakit">
                                <div class="stat-num">{{ $anak->stat_sakit }}</div>
                                <div class="stat-lbl">Sakit</div>
                            </div>
                            <div class="stat-pill stat-izin">
                                <div class="stat-num">{{ $anak->stat_izin }}</div>
                                <div class="stat-lbl">Izin</div>
                            </div>
                            <div class="stat-pill stat-alpha">
                                <div class="stat-num">{{ $anak->stat_alpha }}</div>
                                <div class="stat-lbl">Alpha</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 3: JADWAL + RIWAYAT KEHADIRAN ===== --}}
            <div class="grid-2 mb-6">

                {{-- Jadwal Pelajaran --}}
                <div class="card">
                    <div class="card-body fixed-height">
                        <div class="section-header">
                            <div class="section-icon icon-blue">
                                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <div class="section-title">Jadwal Pelajaran</div>
                                <div class="section-sub">Mingguan</div>
                            </div>
                        </div>
                        <div class="scroll-box">
                            @if($anak->kelas && $anak->kelas->jadwalPelajarans->count() > 0)
                                @php $jadwalPerHari = $anak->kelas->jadwalPelajarans->groupBy('hari');
                                     $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; @endphp
                                @foreach($urutanHari as $hari)
                                    @if(isset($jadwalPerHari[$hari]))
                                    <div class="hari-group">
                                        <div class="hari-label"><span class="hari-dot"></span>{{ $hari }}</div>
                                        @foreach($jadwalPerHari[$hari]->sortBy('jam_mulai') as $jadwal)
                                        <div class="jadwal-item">
                                            <div class="jadwal-time">{{ substr($jadwal->jam_mulai, 0, 5) }}</div>
                                            <div>
                                                <div class="jadwal-mapel">{{ $jadwal->mataPelajaran->nama ?? '-' }}</div>
                                                <div class="jadwal-guru">{{ $jadwal->guru->nama ?? '-' }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p>Jadwal belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Riwayat Kehadiran --}}
                <div class="card">
                    <div class="card-body fixed-height">
                        <div class="section-header" style="justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:14px;">
                                <div class="section-icon icon-green">
                                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <div class="section-title">Riwayat Kehadiran</div>
                                    <div class="section-sub">30 Hari Terakhir</div>
                                </div>
                            </div>
                            <span class="total-badge">{{ $anak->stat_total }} Hari</span>
                        </div>
                        <div class="scroll-box">
                            @forelse($anak->presensis as $presensi)
                            <div class="presensi-item">
                                <div>
                                    <div class="presensi-date">{{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('l, d M Y') }}</div>
                                    @if($presensi->keterangan)
                                        <div class="presensi-note">{{ $presensi->keterangan }}</div>
                                    @endif
                                    @if($presensi->foto_absen)
                                        <div style="margin-top:6px;">
                                            <a href="{{ Storage::url($presensi->foto_absen) }}" target="_blank"
                                               style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#2563eb;font-weight:700;text-decoration:none;">
                                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                Lihat Foto Absen
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <span class="badge badge-{{ $presensi->status }}">{{ strtoupper($presensi->status) }}</span>
                            </div>
                            @empty
                            <div class="empty-state">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4" /></svg>
                                <p>Belum ada data kehadiran</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 4: FOTO ABSEN GALLERY ===== --}}
            @if($anak->presensis_foto->count() > 0)
            <div class="mb-6">
                <div class="card">
                    <div class="card-body">
                        <div class="section-header" style="margin-bottom:1.25rem;">
                            <div class="section-icon icon-purple">
                                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <div class="section-title">Foto Bukti Absensi</div>
                                <div class="section-sub">{{ $anak->presensis_foto->count() }} foto dari guru</div>
                            </div>
                        </div>
                        <div class="foto-grid">
                            @foreach($anak->presensis_foto->take(8) as $pf)
                            <a href="{{ Storage::url($pf->foto_absen) }}" target="_blank" class="foto-item">
                                <img src="{{ Storage::url($pf->foto_absen) }}" alt="Foto Absen" loading="lazy">
                                <div class="foto-overlay">
                                    <div class="foto-date">{{ \Carbon\Carbon::parse($pf->tanggal)->format('d M') }}</div>
                                    <span class="badge badge-{{ $pf->status }}" style="font-size:0.6rem;padding:2px 8px;">{{ strtoupper($pf->status) }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ===== ROW 5: NILAI + CATATAN ===== --}}
            <div class="grid-3-1 mb-6">

                {{-- Transkrip Nilai --}}
                <div class="card">
                    <div class="card-body">
                        <div class="section-header">
                            <div class="section-icon icon-purple">
                                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            </div>
                            <div>
                                <div class="section-title">Transkrip Nilai</div>
                                <div class="section-sub">Evaluasi Akademik</div>
                            </div>
                        </div>
                        <div style="overflow-x:auto;border-radius:12px;border:1px solid #f1f5f9;">
                            <table class="nilai-table">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Semester</th>
                                        <th>Jenis</th>
                                        <th style="text-align:center;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anak->nilais->sortBy('mataPelajaran.nama') as $nilai)
                                    <tr>
                                        <td class="nilai-mapel">{{ $nilai->mataPelajaran->nama ?? '-' }}</td>
                                        <td><span class="nilai-jenis">Sem {{ $nilai->semester }}</span></td>
                                        <td><span class="nilai-jenis">{{ $nilai->jenis_ujian }}</span></td>
                                        <td style="text-align:center;">
                                            <span class="nilai-score {{ $nilai->nilai_angka >= 75 ? 'nilai-score-good' : 'nilai-score-bad' }}">
                                                {{ number_format($nilai->nilai_angka, 0) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" style="text-align:center;padding:2rem;color:#94a3b8;font-size:0.875rem;">Belum ada nilai yang diinput.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Catatan Guru --}}
                <div class="dark-card">
                    <div class="inner">
                        <div class="section-header mb-6">
                            <div class="section-icon icon-dark">
                                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <div>
                                <div class="section-title" style="color:white;">Catatan Guru</div>
                                <div class="section-sub">Evaluasi Karakter</div>
                            </div>
                        </div>
                        <div class="scroll-box" style="flex:1;max-height:320px;">
                            @forelse($anak->catatanPerkembangans as $catatan)
                            <div class="catatan-item">
                                <div class="catatan-header">
                                    <span class="catatan-predikat">{{ $catatan->predikat }}</span>
                                    <span class="catatan-date">{{ $catatan->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="catatan-text">"{{ $catatan->catatan }}"</p>
                                <div class="catatan-footer">
                                    <div class="catatan-guru-avatar">{{ substr($catatan->guru->nama ?? 'G', 0, 1) }}</div>
                                    <span class="catatan-guru-name">{{ $catatan->guru->nama ?? 'Guru Pengajar' }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state" style="color:rgba(255,255,255,0.3);">
                                <p>Belum ada catatan.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end tab-pane --}}
        @endforeach

    @endif

</div>

<style>
/* Statistik grid */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
@media(max-width:640px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
.stat-pill { border-radius:14px; padding:16px 12px; text-align:center; }
.stat-num { font-size:1.75rem; font-weight:900; line-height:1; }
.stat-lbl { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; margin-top:4px; }
.stat-hadir { background:#f0fdf4; } .stat-hadir .stat-num { color:#15803d; } .stat-hadir .stat-lbl { color:#16a34a; }
.stat-sakit { background:#fefce8; } .stat-sakit .stat-num { color:#a16207; } .stat-sakit .stat-lbl { color:#ca8a04; }
.stat-izin  { background:#eff6ff; } .stat-izin  .stat-num { color:#1d4ed8; } .stat-izin  .stat-lbl { color:#2563eb; }
.stat-alpha { background:#fef2f2; } .stat-alpha .stat-num { color:#b91c1c; } .stat-alpha .stat-lbl { color:#dc2626; }

/* Foto grid */
.foto-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
@media(max-width:768px) { .foto-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:480px) { .foto-grid { grid-template-columns:repeat(2,1fr); } }
.foto-item { position:relative; border-radius:12px; overflow:hidden; aspect-ratio:4/3; display:block; cursor:pointer; }
.foto-item img { width:100%; height:100%; object-fit:cover; transition:transform 0.3s; }
.foto-item:hover img { transform:scale(1.05); }
.foto-overlay { position:absolute; bottom:0; left:0; right:0; background:linear-gradient(transparent,rgba(0,0,0,0.7)); padding:8px 10px; display:flex; justify-content:space-between; align-items:flex-end; }
.foto-date { font-size:0.72rem; font-weight:800; color:white; }

/* Presensi foto link hover */
.presensi-item a:hover { color:#1d4ed8; }
</style>

<script>
function switchTab(index) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn-item').forEach(el => { el.classList.remove('active'); el.classList.add('inactive'); });
    const pane = document.getElementById('tab-pane-' + index);
    if (pane) pane.classList.add('active');
    const btn = document.getElementById('tab-btn-' + index);
    if (btn) { btn.classList.remove('inactive'); btn.classList.add('active'); }
}
</script>
@endsection
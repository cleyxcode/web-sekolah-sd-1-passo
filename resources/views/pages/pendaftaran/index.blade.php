@extends('layouts.frontend')

@section('title', 'Informasi Pendaftaran - SD Negeri 1 Passo')
@section('meta_description', 'Informasi dan formulir pendaftaran peserta didik baru SD Negeri 1 Passo.')

@push('styles')
<style>
    /* ===== HERO ===== */
    .daftar-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 60%, #4338ca 100%);
        padding: 5rem 1.5rem 8rem;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .daftar-hero::before {
        content: '';
        position: absolute; top: -120px; right: -120px;
        width: 500px; height: 500px; border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }
    .daftar-hero::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0; height: 100px;
        background: var(--bg);
        clip-path: ellipse(55% 100% at 50% 100%);
        transition: background 0.3s;
    }
    .daftar-status-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.12); color: white;
        border: 1px solid rgba(255,255,255,0.22); border-radius: 999px;
        padding: 7px 18px; font-size: 0.82rem; font-weight: 700;
        margin-bottom: 1.5rem; backdrop-filter: blur(8px);
    }
    .daftar-dot { width: 8px; height: 8px; border-radius: 50%; background: #4ade80; animation: pulse 1.5s infinite; }

    /* ===== STATS STRIP ===== */
    .daftar-stats {
        display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;
        margin-top: 2.5rem;
    }
    .daftar-stat { text-align: center; }
    .daftar-stat-num { font-size: 2rem; font-weight: 900; color: white; line-height: 1; }
    .daftar-stat-lbl { font-size: 0.78rem; color: rgba(255,255,255,0.65); font-weight: 600; margin-top: 4px; }

    /* ===== STEP CARDS ===== */
    .steps-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;
        margin-bottom: 2.5rem;
    }
    @media(max-width:900px) { .steps-row { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width:480px) { .steps-row { grid-template-columns: 1fr; } }

    .step-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 18px; padding: 1.5rem 1.25rem; text-align: center;
        box-shadow: var(--shadow-sm); transition: all 0.3s;
        position: relative; overflow: hidden;
    }
    .step-card::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, #2563eb, #4f46e5);
        transform: scaleX(0); transition: transform 0.3s;
    }
    .step-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .step-card:hover::after { transform: scaleX(1); }
    .step-num-badge {
        display: inline-flex; width: 36px; height: 36px;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        border-radius: 50%; align-items: center; justify-content: center;
        font-size: 0.9rem; font-weight: 900; color: white;
        margin-bottom: 0.85rem; box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    }
    .step-title { font-size: 0.88rem; font-weight: 800; color: var(--text); margin-bottom: 5px; }
    .step-desc { font-size: 0.78rem; color: var(--text-muted); line-height: 1.5; }

    /* ===== CONTENT GRID ===== */
    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.75rem; align-items: start; }
    @media(max-width:900px) { .content-grid { grid-template-columns: 1fr; } .action-card { position: static !important; } }

    /* ===== INFO CARD ===== */
    .info-card {
        background: var(--surface); border-radius: 20px;
        padding: 2rem; box-shadow: var(--shadow-sm);
        border: 1px solid var(--border); transition: background 0.3s, border-color 0.3s;
    }
    .info-card + .info-card { margin-top: 1.25rem; }
    .card-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; }
    .card-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .card-title { font-size: 1.1rem; font-weight: 800; color: var(--text); transition: color 0.3s; }
    .info-prose { font-size: 0.92rem; line-height: 1.85; color: var(--text-muted); }
    .info-prose p { margin-bottom: 0.85rem; }
    .info-prose ul { padding-left: 1.2rem; margin: 0; }
    .info-prose li { margin-bottom: 6px; }

    /* ===== SYARAT LIST ===== */
    .syarat-list { list-style: none; padding: 0; margin: 0; }
    .syarat-list li {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; border-radius: 10px; margin-bottom: 6px;
        background: var(--surface-2); font-size: 0.9rem; color: var(--text);
        transition: background 0.3s;
    }
    .syarat-list li .syarat-icon {
        width: 26px; height: 26px; background: #dcfce7; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    /* ===== ACTION CARD (SIDEBAR) ===== */
    .action-card {
        background: linear-gradient(145deg, #1e3a8a, #4f46e5);
        border-radius: 20px; padding: 2rem;
        box-shadow: 0 8px 40px rgba(37,99,235,0.4);
        position: sticky; top: 88px;
        overflow: hidden;
    }
    .action-card::before {
        content: '';
        position: absolute; top: -70px; right: -70px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .action-card-inner { position: relative; z-index: 1; }
    .action-icon-wrap {
        width: 60px; height: 60px; background: rgba(255,255,255,0.15);
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem; border: 1px solid rgba(255,255,255,0.2);
    }
    .form-btn {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 15px;
        background: white; color: #1e40af;
        border-radius: 14px; font-weight: 800; font-size: 0.95rem;
        text-decoration: none; transition: all 0.25s;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .form-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,0.22); }
    .verified-badge {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        margin-top: 12px; font-size: 0.76rem; color: rgba(255,255,255,0.5);
    }
    .step-mini { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1rem; }
    .step-mini-num {
        width: 28px; height: 28px; background: rgba(255,255,255,0.15);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 900; color: white; flex-shrink: 0;
    }
    .step-mini-text { font-size: 0.85rem; color: rgba(255,255,255,0.82); line-height: 1.5; padding-top: 4px; }

    /* ===== CONTACT CARD ===== */
    .contact-card {
        background: var(--surface); border-radius: 18px; padding: 1.25rem;
        margin-top: 1.25rem; border: 1px solid var(--border);
        box-shadow: var(--shadow-sm); transition: background 0.3s;
    }
    .wa-link {
        display: flex; align-items: center; gap: 12px; padding: 12px;
        background: #f0fdf4; border-radius: 12px; text-decoration: none;
        transition: background 0.2s;
    }
    .wa-link:hover { background: #dcfce7; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        background: var(--surface-2); border: 2px dashed var(--border);
        border-radius: 16px; padding: 3rem; text-align: center;
        transition: background 0.3s;
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="daftar-hero">
    <div class="container" style="position:relative;z-index:1;max-width:860px;">
        <div class="daftar-status-badge">
            <span class="daftar-dot"></span>
            Pendaftaran Siswa Baru Dibuka
        </div>
        <h1 style="font-size:clamp(1.9rem,5vw,3rem);font-weight:900;color:white;margin-bottom:14px;line-height:1.15;">
            {{ $pendaftaran->judul ?? 'Penerimaan Peserta Didik Baru' }}
        </h1>
        <p style="color:#bfdbfe;font-size:1rem;line-height:1.75;max-width:580px;margin:0 auto;">
            Selamat datang di portal resmi penerimaan peserta didik baru <strong style="color:white;">SD Negeri 1 Passo</strong>.
            Baca informasi dengan seksama sebelum mengisi formulir pendaftaran.
        </p>

        <div class="daftar-stats">
            <div class="daftar-stat">
                <div class="daftar-stat-num">500+</div>
                <div class="daftar-stat-lbl">Siswa Aktif</div>
            </div>
            <div class="daftar-stat">
                <div class="daftar-stat-num">30+</div>
                <div class="daftar-stat-lbl">Tenaga Pendidik</div>
            </div>
            <div class="daftar-stat">
                <div class="daftar-stat-num">A</div>
                <div class="daftar-stat-lbl">Akreditasi</div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<section style="padding:3rem 0 5rem;background:var(--bg);transition:background 0.3s;">
    <div class="container">

        {{-- Step Cards --}}
        <div class="steps-row">
            <div class="step-card">
                <div class="step-num-badge">1</div>
                <div class="step-title">Siapkan Berkas</div>
                <div class="step-desc">Persiapkan semua dokumen persyaratan yang dibutuhkan</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">2</div>
                <div class="step-title">Buka Formulir</div>
                <div class="step-desc">Klik tombol formulir online di samping kanan</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">3</div>
                <div class="step-title">Isi Data</div>
                <div class="step-desc">Isi semua data dengan lengkap, benar, dan teliti</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">4</div>
                <div class="step-title">Verifikasi Berkas</div>
                <div class="step-desc">Datang ke sekolah untuk verifikasi dokumen asli</div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="content-grid">

            {{-- Left: Info --}}
            <div>
                {{-- Informasi Pendaftaran --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon" style="background:#eff6ff;">
                            <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div class="card-title">Informasi Pendaftaran</div>
                    </div>

                    @if($pendaftaran->deskripsi)
                    <div class="info-prose">
                        {!! nl2br(e($pendaftaran->deskripsi)) !!}
                    </div>
                    @else
                    <div class="empty-state">
                        <svg width="40" height="40" fill="none" stroke="var(--text-light)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p style="color:var(--text);font-size:0.9rem;font-weight:700;margin-bottom:4px;">Informasi segera ditambahkan</p>
                        <p style="color:var(--text-muted);font-size:0.82rem;">Silakan langsung mengisi formulir pendaftaran atau hubungi pihak sekolah.</p>
                    </div>
                    @endif
                </div>

                {{-- Persyaratan --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon" style="background:#f0fdf4;">
                            <svg width="22" height="22" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="card-title">Persyaratan Pendaftaran</div>
                    </div>
                    <ul class="syarat-list">
                        <li>
                            <div class="syarat-icon"><svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></div>
                            Berusia 6–7 tahun pada tanggal 1 Juli tahun ajaran baru
                        </li>
                        <li>
                            <div class="syarat-icon"><svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></div>
                            Kartu Keluarga (KK) yang masih berlaku
                        </li>
                        <li>
                            <div class="syarat-icon"><svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></div>
                            Akta kelahiran asli dan fotokopi
                        </li>
                        <li>
                            <div class="syarat-icon"><svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></div>
                            Foto berwarna ukuran 3×4 sebanyak 2 lembar
                        </li>
                        <li>
                            <div class="syarat-icon"><svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></div>
                            Mengisi formulir pendaftaran yang tersedia
                        </li>
                    </ul>
                </div>

                {{-- Lokasi --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon" style="background:#fff7ed;">
                            <svg width="22" height="22" fill="none" stroke="#ea580c" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="card-title">Lokasi Sekolah</div>
                    </div>
                    <p style="font-size:0.92rem;color:var(--text-muted);line-height:1.7;margin:0;">
                        Jl. Pendidikan No. 1, Passo, Kota Ambon, Maluku.<br>
                        <strong style="color:var(--text);">Jam Pelayanan:</strong> Senin – Jumat, 08.00 – 14.00 WIT
                    </p>
                </div>
            </div>

            {{-- Right: Action + Contact --}}
            <div>
                {{-- Form Card --}}
                <div class="action-card">
                    <div class="action-card-inner">
                        <div class="action-icon-wrap">
                            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 style="font-size:1.3rem;font-weight:900;color:white;margin-bottom:8px;">Daftar Sekarang</h3>
                        <p style="font-size:0.875rem;color:#bfdbfe;margin-bottom:1.75rem;line-height:1.65;">
                            Formulir menggunakan platform eksternal. Pastikan koneksi internet Anda stabil.
                        </p>

                        <a href="{{ $pendaftaran->link_pendaftaran ?? '#' }}" target="_blank" rel="noopener" class="form-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                            Buka Formulir Online
                        </a>

                        <div class="verified-badge">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Tautan Resmi & Terverifikasi
                        </div>

                        <div style="border-top:1px solid rgba(255,255,255,0.12);margin-top:1.75rem;padding-top:1.5rem;">
                            <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-bottom:1rem;">Langkah Mudah</div>
                            <div class="step-mini"><div class="step-mini-num">1</div><div class="step-mini-text">Siapkan berkas persyaratan</div></div>
                            <div class="step-mini"><div class="step-mini-num">2</div><div class="step-mini-text">Klik tombol formulir di atas</div></div>
                            <div class="step-mini"><div class="step-mini-num">3</div><div class="step-mini-text">Isi data dengan benar</div></div>
                            <div class="step-mini" style="margin-bottom:0;"><div class="step-mini-num">4</div><div class="step-mini-text">Datang ke sekolah untuk verifikasi</div></div>
                        </div>
                    </div>
                </div>

                {{-- WhatsApp Card --}}
                <div class="contact-card">
                    <div style="font-size:0.85rem;font-weight:800;color:var(--text);margin-bottom:12px;">Butuh Bantuan?</div>
                    <a href="https://wa.me/628110000000" target="_blank" class="wa-link">
                        <div style="width:38px;height:38px;background:#22c55e;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <div style="font-size:0.88rem;font-weight:800;color:#15803d;">Hubungi via WhatsApp</div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">Tim kami siap membantu Anda</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

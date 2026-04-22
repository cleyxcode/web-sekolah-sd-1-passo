@extends('layouts.frontend')

@section('title', 'Informasi Pendaftaran - SD Negeri 1 Passo')
@section('meta_description', 'Informasi dan formulir pendaftaran peserta didik baru SD Negeri 1 Passo.')

@push('styles')
<style>
    .daftar-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 60%, #4338ca 100%);
        padding: 4rem 1.5rem 5rem;
        position: relative;
        overflow: hidden;
    }
    .daftar-hero::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 400px; height: 400px; border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .daftar-hero::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0; height: 80px;
        background: #f8faff;
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    .daftar-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.25); border-radius: 999px; padding: 6px 16px; font-size: 0.8rem; font-weight: 700; }
    .daftar-dot { width: 8px; height: 8px; border-radius: 50%; background: #4ade80; animation: pulse 1.5s infinite; }

    .info-card { background: white; border-radius: 24px; padding: 2.5rem; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
    .info-card h2 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
    .info-icon { width: 42px; height: 42px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2563eb; flex-shrink: 0; }
    .info-prose { font-size: 0.95rem; line-height: 1.8; color: #475569; }
    .info-prose p { margin-bottom: 1rem; }
    .info-prose ul { padding-left: 1.25rem; }
    .info-prose li { margin-bottom: 8px; }

    .action-card {
        background: linear-gradient(145deg, #1e40af, #4f46e5);
        border-radius: 24px; padding: 2.5rem;
        box-shadow: 0 8px 40px rgba(37,99,235,0.35);
        position: sticky; top: 88px;
        overflow: hidden;
    }
    .action-card::before {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .action-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(255,255,255,0.2); }
    .action-btn {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 16px;
        background: white; color: #1e40af;
        border-radius: 14px; font-weight: 800; font-size: 1rem;
        text-decoration: none; transition: all 0.25s;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.18); }

    .step-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 1.25rem; }
    .step-num { width: 32px; height: 32px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 900; color: white; flex-shrink: 0; }
    .step-text { font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; }

    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start; }
    @media(max-width:900px) { .content-grid { grid-template-columns: 1fr; } .action-card { position: static; } }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="daftar-hero">
    <div class="container" style="position:relative;z-index:1;max-width:900px;">
        <div class="daftar-badge" style="margin-bottom:20px;">
            <span class="daftar-dot"></span>
            Pendaftaran Dibuka
        </div>
        <h1 style="font-size:clamp(1.8rem,5vw,3rem);font-weight:900;color:white;margin-bottom:14px;max-width:700px;">
            {{ $pendaftaran->judul ?? 'Penerimaan Peserta Didik Baru' }}
        </h1>
        <p style="color:#bfdbfe;font-size:1rem;line-height:1.75;max-width:600px;">
            Selamat datang di portal resmi penerimaan peserta didik baru SD Negeri 1 Passo. Baca informasi lengkap sebelum melanjutkan pengisian formulir.
        </p>
    </div>
</div>

{{-- Content --}}
<section style="padding:3rem 0 5rem;background:#f8faff;">
    <div class="container">
        <div class="content-grid">

            {{-- Info --}}
            <div>
                <div class="info-card">
                    <h2>
                        <div class="info-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        Informasi Pendaftaran
                    </h2>

                    @if($pendaftaran->deskripsi)
                    <div class="info-prose">
                        {!! nl2br(e($pendaftaran->deskripsi)) !!}
                    </div>
                    @else
                    <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:16px;padding:3rem;text-align:center;">
                        <svg width="40" height="40" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p style="color:#64748b;font-size:0.9rem;font-weight:600;">Informasi lengkap akan segera ditambahkan.</p>
                        <p style="color:#94a3b8;font-size:0.85rem;margin-top:6px;">Silakan langsung mengisi formulir pendaftaran.</p>
                    </div>
                    @endif
                </div>

                {{-- Requirements --}}
                <div class="info-card" style="margin-top:1.5rem;">
                    <h2>
                        <div class="info-icon" style="background:#f0fdf4;color:#16a34a;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Persyaratan Umum
                    </h2>
                    <div class="info-prose">
                        <ul>
                            <li>Berusia 6–7 tahun pada tanggal 1 Juli tahun ajaran baru</li>
                            <li>Memiliki Kartu Keluarga (KK) yang masih berlaku</li>
                            <li>Membawa akta kelahiran asli dan fotokopi</li>
                            <li>Membawa foto berwarna 3×4 sebanyak 2 lembar</li>
                            <li>Mengisi formulir pendaftaran yang tersedia</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Action Card --}}
            <div>
                <div class="action-card" style="position:relative;">
                    <div style="position:relative;z-index:1;">
                        <div class="action-icon">
                            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 style="font-size:1.3rem;font-weight:900;color:white;margin-bottom:10px;">Isi Formulir Sekarang</h3>
                        <p style="font-size:0.875rem;color:#bfdbfe;margin-bottom:1.75rem;line-height:1.65;">Formulir menggunakan platform eksternal. Pastikan koneksi internet stabil sebelum mengisi.</p>

                        <a href="{{ $pendaftaran->link_pendaftaran ?? '#' }}" target="_blank" rel="noopener" class="action-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                            Formulir Pendaftaran Online
                        </a>

                        <div style="margin-top:14px;display:flex;align-items:center;justify-content:center;gap:6px;font-size:0.78rem;color:rgba(255,255,255,0.5);">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Tautan Resmi & Terverifikasi
                        </div>

                        <div style="border-top:1px solid rgba(255,255,255,0.12);margin-top:2rem;padding-top:1.5rem;">
                            <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.4);margin-bottom:1rem;">Langkah Pendaftaran</div>
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-text">Siapkan berkas persyaratan yang dibutuhkan</div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-text">Buka formulir online via tautan di atas</div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-text">Isi data dengan lengkap dan benar</div>
                            </div>
                            <div class="step-item" style="margin-bottom:0;">
                                <div class="step-num">4</div>
                                <div class="step-text">Datang ke sekolah untuk verifikasi berkas</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div style="background:white;border-radius:20px;padding:1.5rem;margin-top:1.5rem;border:1px solid #f1f5f9;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
                    <div style="font-size:0.85rem;font-weight:800;color:#0f172a;margin-bottom:12px;">Ada Pertanyaan?</div>
                    <a href="https://wa.me/628110000000" target="_blank" style="display:flex;align-items:center;gap:10px;padding:12px;background:#f0fdf4;border-radius:12px;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                        <div style="width:36px;height:36px;background:#22c55e;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <div style="font-size:0.85rem;font-weight:700;color:#15803d;">Hubungi via WhatsApp</div>
                            <div style="font-size:0.78rem;color:#64748b;">Kami siap membantu Anda</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

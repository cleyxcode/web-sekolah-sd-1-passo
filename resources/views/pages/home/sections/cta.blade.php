<style>
    .cta-section {
        background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #4338ca 100%);
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute; top: -100px; right: -100px;
        width: 400px; height: 400px; border-radius: 50%;
        background: rgba(255,255,255,0.05);
        pointer-events: none;
    }
    .cta-section::after {
        content: '';
        position: absolute; bottom: -80px; left: -80px;
        width: 320px; height: 320px; border-radius: 50%;
        background: rgba(255,255,255,0.04);
        pointer-events: none;
    }
    .cta-inner { max-width: 640px; margin: 0 auto; text-align: center; position: relative; z-index: 1; }
    .cta-title { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; color: white; line-height: 1.2; margin-bottom: 1rem; }
    .cta-sub { font-size: 1rem; color: #bfdbfe; line-height: 1.75; margin-bottom: 2.5rem; }
    .cta-btns { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; }
    .cta-btn-primary {
        padding: 14px 32px; background: white; color: #1d4ed8;
        border-radius: 14px; font-weight: 800; font-size: 0.95rem;
        text-decoration: none; transition: all 0.25s;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .cta-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
    .cta-btn-ghost {
        padding: 14px 32px; background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.25);
        color: white; border-radius: 14px; font-weight: 700; font-size: 0.95rem;
        text-decoration: none; transition: all 0.25s;
    }
    .cta-btn-ghost:hover { background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.5); }
</style>

<section class="cta-section section">
    <div class="cta-inner">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:999px;padding:6px 16px;font-size:0.8rem;font-weight:700;color:rgba(255,255,255,0.85);margin-bottom:1.5rem;">
            <span style="width:7px;height:7px;border-radius:50%;background:#4ade80;animation:pulse 1.5s infinite;"></span>
            Pendaftaran Siswa Baru Dibuka
        </div>
        <h2 class="cta-title">Bergabung Bersama Kami</h2>
        <p class="cta-sub">Buka potensi terbaik anak Anda di sekolah yang mengutamakan karakter, inovasi, dan prestasi akademik. Pendaftaran Tahun Ajaran 2025/2026 telah dibuka.</p>
        <div class="cta-btns">
            @if(isset($pendaftaran) && $pendaftaran?->link_pendaftaran)
                <a href="{{ route('pendaftaran') }}" class="cta-btn-primary">Informasi Pendaftaran</a>
            @else
                <a href="#" class="cta-btn-primary">Pendaftaran Segera Dibuka</a>
            @endif
            <a href="{{ route('login.ortu') }}" class="cta-btn-ghost">Portal Orang Tua</a>
        </div>
    </div>
</section>

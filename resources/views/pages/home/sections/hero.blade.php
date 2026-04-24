{{-- Hero Section --}}
<section style="background:var(--bg);overflow:hidden;position:relative;transition:background 0.3s;">
    {{-- BG Blobs --}}
    <div style="position:absolute;top:-100px;right:-100px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,0.08),transparent 70%);"></div>
    <div style="position:absolute;bottom:-80px;left:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(79,70,229,0.07),transparent 70%);"></div>

    <div class="container" style="padding-top:5rem;padding-bottom:5rem;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
            {{-- Text --}}
            <div class="fade-up" style="position:relative;z-index:1;">
                <div class="section-tag">
                    <span class="section-tag-dot"></span>
                    Tahun Ajaran Baru 2025/2026
                </div>
                <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:var(--text);line-height:1.1;margin-bottom:1.25rem;transition:color 0.3s;">
                    Pendidikan Terbaik<br>Untuk <span style="background:linear-gradient(135deg,#2563eb,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Masa Depan</span>
                </h1>
                <p style="font-size:1.05rem;color:var(--text-muted);line-height:1.75;margin-bottom:2rem;max-width:480px;transition:color 0.3s;">
                    Sistem informasi akademik terpadu yang memudahkan orang tua, siswa, dan guru dalam mengakses informasi perkembangan belajar secara transparan dan real-time.
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:12px;">
                    <a href="{{ route('login.ortu') }}" style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,#2563eb,#4f46e5);color:white;border-radius:14px;font-weight:700;font-size:0.95rem;text-decoration:none;box-shadow:0 4px 20px rgba(37,99,235,0.35);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        Portal Orang Tua
                    </a>
                    <a href="{{ route('profil') }}" style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:var(--surface);color:var(--text);border-radius:14px;font-weight:700;font-size:0.95rem;text-decoration:none;border:2px solid var(--border);transition:all 0.2s;" onmouseover="this.style.borderColor='#93c5fd'" onmouseout="this.style.borderColor='var(--border)'">
                        Pelajari Lebih Lanjut
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                </div>

                {{-- Stats --}}
                <div style="display:flex;gap:2rem;margin-top:2.5rem;padding-top:2rem;border-top:1px solid var(--border);">
                    <div>
                        <div style="font-size:1.8rem;font-weight:900;color:var(--primary);">500+</div>
                        <div style="font-size:0.8rem;color:var(--text-muted);font-weight:500;">Siswa Aktif</div>
                    </div>
                    <div>
                        <div style="font-size:1.8rem;font-weight:900;color:#7c3aed;">30+</div>
                        <div style="font-size:0.8rem;color:var(--text-muted);font-weight:500;">Tenaga Pendidik</div>
                    </div>

                </div>
            </div>

            {{-- Image --}}
            <div style="position:relative;" class="fade-up">
                <div style="border-radius:24px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,0.15);aspect-ratio:4/3;background:#dbeafe;">
                    @php
                        $heroImg = isset($settings) && $settings?->foto_hero
                            ? Storage::url($settings->foto_hero)
                            : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
                    @endphp
                    <img src="{{ $heroImg }}"
                         alt="Foto Sekolah {{ $settings->nama_sekolah ?? 'SD Negeri 1 Passo' }}"
                         style="width:100%;height:100%;object-fit:cover;">
                </div>

            </div>
        </div>
    </div>

    {{-- Mobile responsive --}}
    <style>
        @media(max-width:768px) {
            section > .container > div { grid-template-columns: 1fr !important; gap: 2rem !important; }
            section > .container { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        }
    </style>
</section>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Sekolah')</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Akademik dan Portal Resmi SD Negeri 1 Passo.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8faff;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== DESIGN TOKENS ===== */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --accent: #4f46e5;
            --accent-dark: #3730a3;
            --success: #16a34a;
            --surface: #ffffff;
            --surface-2: #f1f5f9;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --radius: 16px;
            --radius-lg: 24px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
        }

        /* ===== NAVBAR ===== */
        .site-nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 1.5rem;
            height: 72px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; flex-shrink: 0;
        }
        .nav-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
            flex-shrink: 0;
            transition: transform 0.2s;
        }
        .nav-brand:hover .nav-logo-icon { transform: scale(1.05); }
        .nav-school-name { font-size: 1rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
        .nav-school-tagline { font-size: 0.72rem; color: var(--text-muted); font-weight: 500; }
        @media(max-width:480px) { .nav-school-tagline { display: none; } }

        .nav-links {
            display: flex; align-items: center; gap: 4px;
            list-style: none;
        }
        .nav-links a {
            display: block; padding: 8px 14px;
            font-size: 0.9rem; font-weight: 600; color: var(--text-muted);
            text-decoration: none; border-radius: 10px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .nav-links a:hover { color: var(--primary); background: #eff6ff; }
        .nav-links a.active { color: var(--primary); background: #eff6ff; }
        @media(max-width:768px) { .nav-links { display: none; } }
        .nav-links.mobile-open { display: flex; flex-direction: column; }

        .nav-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .btn-ghost {
            padding: 9px 16px; border-radius: 10px;
            font-size: 0.85rem; font-weight: 700; color: var(--primary);
            text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center;
            white-space: nowrap;
        }
        .btn-ghost:hover { background: #eff6ff; }
        .btn-primary {
            padding: 9px 20px; border-radius: 10px;
            font-size: 0.85rem; font-weight: 700; color: white;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            text-decoration: none; transition: all 0.2s;
            box-shadow: 0 3px 12px rgba(37,99,235,0.35);
            display: inline-flex; align-items: center;
            white-space: nowrap;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
        @media(max-width:640px) { .btn-ghost { display: none; } }

        .hamburger {
            display: none; width: 40px; height: 40px;
            border: none; background: transparent; cursor: pointer;
            border-radius: 10px; color: var(--text); flex-direction: column;
            align-items: center; justify-content: center; gap: 5px;
        }
        @media(max-width:768px) { .hamburger { display: flex; } }
        .hamburger span { display: block; width: 22px; height: 2px; background: currentColor; border-radius: 2px; transition: all 0.3s; }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            background: white;
            border-top: 1px solid var(--border);
            padding: 16px 1.5rem;
        }
        .mobile-menu.open { display: block; }
        .mobile-menu a {
            display: block; padding: 12px 16px;
            font-size: 0.95rem; font-weight: 600; color: var(--text);
            text-decoration: none; border-radius: 10px;
            transition: all 0.2s; margin-bottom: 4px;
        }
        .mobile-menu a:hover { background: #f1f5f9; color: var(--primary); }
        .mobile-menu .divider { height: 1px; background: var(--border); margin: 12px 0; }
        .mobile-menu .btn-block {
            display: block; text-align: center; padding: 12px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white; border-radius: 12px;
            font-weight: 700; text-decoration: none; margin-top: 8px;
        }

        /* ===== FOOTER ===== */
        .site-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 3rem 1.5rem 1.5rem;
            margin-top: auto;
        }
        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 3rem; margin-bottom: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        @media(max-width:768px) { .footer-grid { grid-template-columns: 1fr; gap: 2rem; } }
        .footer-brand-name { font-size: 1.2rem; font-weight: 800; color: white; margin-bottom: 12px; }
        .footer-desc { font-size: 0.875rem; line-height: 1.7; color: #64748b; max-width: 360px; }
        .footer-socials { display: flex; gap: 10px; margin-top: 16px; }
        .footer-social-btn {
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(255,255,255,0.06);
            display: flex; align-items: center; justify-content: center;
            color: #64748b; transition: all 0.2s; text-decoration: none;
        }
        .footer-social-btn:hover { background: rgba(37,99,235,0.2); color: #60a5fa; }
        .footer-heading { font-size: 0.75rem; font-weight: 800; color: white; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { font-size: 0.875rem; color: #64748b; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #60a5fa; }
        .footer-contact-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 0.875rem; }
        .footer-contact-icon { width: 30px; height: 30px; background: rgba(255,255,255,0.06); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; color: #64748b; }
        .footer-bottom { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 0.8rem; }
        .footer-heart { display: flex; align-items: center; gap: 4px; }

        /* ===== SECTION HEADINGS ===== */
        .section-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; color: #2563eb;
            border: 1px solid #bfdbfe; border-radius: 999px;
            padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
            margin-bottom: 12px;
        }
        .section-tag-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563eb; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
        .section-title { font-size: clamp(1.6rem, 4vw, 2.5rem); font-weight: 900; color: #0f172a; line-height: 1.2; }
        .section-sub { font-size: 1rem; color: var(--text-muted); line-height: 1.7; max-width: 600px; margin: 12px auto 0; }

        /* ===== GLOBAL UTILS ===== */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        @media(max-width:640px) { .container { padding: 0 1rem; } }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; }

        /* ===== PLACEHOLDER GALLERY FALLBACK ===== */
        .gallery-placeholder {
            background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
            display: flex; align-items: center; justify-content: center;
            color: #93c5fd; font-size: 2.5rem;
        }

        @stack('styles')
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="site-nav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-brand">
                @if(isset($settings) && $settings->logo)
                    <img src="{{ Storage::url($settings->logo) }}" alt="Logo" style="height:42px;width:auto;border-radius:10px;">
                @else
                    <div class="nav-logo-icon">S</div>
                @endif
                <div>
                    <div class="nav-school-name">{{ $settings->nama_sekolah ?? 'SD Negeri 1 Passo' }}</div>
                    <div class="nav-school-tagline">Unggul, Berkarakter & Berprestasi</div>
                </div>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">Profil</a></li>
                <li><a href="{{ route('berita.index') }}" class="{{ request()->routeIs('berita.*') ? 'active' : '' }}">Berita</a></li>
                <li><a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a></li>
                @if(isset($pendaftaran) && $pendaftaran?->link_pendaftaran)
                <li><a href="{{ route('pendaftaran') }}" class="{{ request()->routeIs('pendaftaran') ? 'active' : '' }}" style="color:#2563eb;">Pendaftaran</a></li>
                @endif
            </ul>

            <div class="nav-actions">
                @if(Auth::guard('ortu')->check())
                    <a href="{{ route('portal.ortu.dashboard') }}" class="btn-primary">Dashboard Anak</a>
                @else
                    <a href="{{ route('login.ortu') }}" class="btn-ghost">Portal Orang Tua</a>
                    <a href="/admin/login" class="btn-primary">Login Admin</a>
                @endif
                <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('profil') }}">Profil Sekolah</a>
            <a href="{{ route('berita.index') }}">Berita & Pengumuman</a>
            <a href="{{ route('galeri') }}">Galeri</a>
            @if(isset($pendaftaran) && $pendaftaran?->link_pendaftaran)
            <a href="{{ route('pendaftaran') }}" style="color:#2563eb;font-weight:700;">Pendaftaran</a>
            @endif
            <div class="divider"></div>
            @if(Auth::guard('ortu')->check())
                <a href="{{ route('portal.ortu.dashboard') }}" class="btn-block">Dashboard Anak Saya</a>
            @else
                <a href="{{ route('login.ortu') }}" style="color:#2563eb;text-align:center;">Portal Orang Tua</a>
                <a href="/admin/login" class="btn-block">Login Guru / Admin</a>
            @endif
        </div>
    </nav>

    <main style="flex:1;">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand-name">{{ $settings->nama_sekolah ?? 'SD Negeri 1 Passo' }}</div>
                    <p class="footer-desc">Sistem informasi akademik modern untuk mewujudkan pendidikan yang transparan, mudah diakses, dan mendukung perkembangan karakter serta prestasi siswa.</p>
                    <div class="footer-socials">
                        <a href="#" class="footer-social-btn" aria-label="Facebook">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        <a href="#" class="footer-social-btn" aria-label="Instagram">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#" class="footer-social-btn" aria-label="YouTube">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="footer-heading">Navigasi</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('profil') }}">Profil Sekolah</a></li>
                        <li><a href="{{ route('berita.index') }}">Berita & Pengumuman</a></li>
                        <li><a href="{{ route('galeri') }}">Galeri Foto</a></li>
                        <li><a href="{{ route('login.ortu') }}">Portal Orang Tua</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-heading">Hubungi Kami</div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <span>Jl. Pendidikan No. 1, Passo, Kota Ambon, Maluku</span>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </div>
                        <span>{{ $settings->telepon ?? '0911-123456' }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <span>{{ $settings->email ?? 'info@sdn1passo.sch.id' }}</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} SD Negeri 1 Passo. Hak Cipta Dilindungi.</span>
                <span class="footer-heart">Dibuat dengan <svg width="12" height="12" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg> Laravel & Filament</span>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('open');
        }
        // Close menu on outside click
        document.addEventListener('click', function(e) {
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

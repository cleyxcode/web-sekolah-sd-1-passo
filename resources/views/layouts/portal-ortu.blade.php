<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Orang Tua') - SD Negeri 1 Passo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f7;
            color: #1e293b;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ====== NAVBAR ====== */
        .portal-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.07);
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .portal-nav .brand { display: flex; align-items: center; gap: 12px; }
        .portal-nav .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(79,70,229,0.35);
            flex-shrink: 0;
        }
        .portal-nav .brand-title { font-weight: 800; font-size: 1rem; color: #0f172a; line-height: 1.2; }
        .portal-nav .brand-sub { font-size: 0.72rem; color: #64748b; font-weight: 500; }

        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .nav-profile-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 14px 6px 6px;
            border-radius: 999px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .nav-profile-btn:hover { background: #f1f5f9; }
        .nav-profile-btn .avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #818cf8, #4f46e5);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1rem; color: white;
            flex-shrink: 0;
        }
        .nav-profile-btn .nav-name { font-size: 0.85rem; font-weight: 700; color: #0f172a; }
        .nav-profile-btn .nav-sub { font-size: 0.68rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        @media(max-width: 600px) { .nav-profile-btn .nav-text { display: none; } }

        .nav-divider { width: 1px; height: 24px; background: #e2e8f0; }
        .nav-logout-btn {
            width: 38px; height: 38px;
            border: none; background: transparent; cursor: pointer;
            border-radius: 10px; color: #94a3b8;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .nav-logout-btn:hover { background: #fee2e2; color: #ef4444; }

        /* ====== LAYOUT ====== */
        .page-wrapper { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }
        @media(max-width:768px) { .page-wrapper { padding: 1.25rem 1rem; } }

        /* ====== ANIMATION ====== */
        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease-out forwards; }

        /* ====== TABS ====== */
        .tab-strip { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 1.75rem; }
        .tab-btn-item {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 20px;
            border-radius: 14px;
            font-weight: 700; font-size: 0.875rem;
            cursor: pointer; border: none;
            transition: all 0.25s ease;
        }
        .tab-btn-item.inactive {
            background: white; color: #475569;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .tab-btn-item.inactive:hover { background: #f8fafc; border-color: #c7d2fe; color: #4f46e5; }
        .tab-btn-item.active {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            color: white;
            box-shadow: 0 4px 16px rgba(79,70,229,0.35);
        }
        .tab-btn-item .tab-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800;
        }
        .tab-btn-item.active .tab-avatar { background: rgba(255,255,255,0.25); color: white; }
        .tab-btn-item.inactive .tab-avatar { background: #e2e8f0; color: #64748b; }

        /* ====== CARD ====== */
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.09); }
        .card-body { padding: 1.75rem; }
        @media(max-width:768px) { .card-body { padding: 1.25rem; } }

        /* ====== PROFILE HERO CARD ====== */
        .hero-card {
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
            border-radius: 20px;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(79,70,229,0.3);
        }
        .hero-card::before {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%; background: rgba(255,255,255,0.07);
        }
        .hero-card::after {
            content: '';
            position: absolute; bottom: -50px; left: -50px;
            width: 160px; height: 160px;
            border-radius: 50%; background: rgba(255,255,255,0.05);
        }
        .hero-card .inner { position: relative; z-index: 1; display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
        .hero-avatar {
            width: 88px; height: 88px; flex-shrink: 0;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: 900; color: white;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 999px;
            padding: 4px 14px;
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: rgba(255,255,255,0.9);
            margin-bottom: 10px;
        }
        .hero-name { font-size: 1.9rem; font-weight: 900; color: white; margin: 0 0 8px; line-height: 1.2; }
        @media(max-width:480px) { .hero-name { font-size: 1.4rem; } }
        .hero-meta { display: flex; flex-wrap: wrap; gap: 8px; }
        .hero-chip {
            background: rgba(255,255,255,0.15);
            border-radius: 8px; padding: 5px 12px;
            font-size: 0.85rem; color: rgba(255,255,255,0.92);
            font-weight: 600;
        }

        /* ====== STATUS CARD ====== */
        .status-card-aktif { background: linear-gradient(160deg, #f0fdf4, #dcfce7); border: 1px solid #bbf7d0; }
        .status-card-other { background: #f8fafc; border: 1px solid #e2e8f0; }
        .status-icon-aktif {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            box-shadow: 0 4px 14px rgba(34,197,94,0.35);
        }
        .status-icon-other { background: #94a3b8; }
        .status-icon {
            width: 60px; height: 60px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white; margin: 0 auto 14px;
        }
        .status-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 6px; }
        .status-value-aktif { font-size: 2rem; font-weight: 900; color: #15803d; }
        .status-value-other { font-size: 2rem; font-weight: 900; color: #475569; }

        /* ====== SECTION HEADER ====== */
        .section-header { display: flex; align-items: center; gap: 14px; margin-bottom: 1.25rem; }
        .section-icon {
            width: 44px; height: 44px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-green { background: #f0fdf4; color: #16a34a; }
        .icon-purple { background: #faf5ff; color: #7c3aed; }
        .icon-dark { background: rgba(255,255,255,0.1); color: white; }
        .section-title { font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0; }
        .section-sub { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin: 2px 0 0; }

        /* ====== GRID SYSTEM ====== */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .grid-3-1 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
        @media(max-width:900px) {
            .grid-2 { grid-template-columns: 1fr; }
            .grid-3-1 { grid-template-columns: 1fr; }
        }

        /* ====== SCROLLABLE BOX ====== */
        .scroll-box { flex: 1; overflow-y: auto; padding-right: 4px; }
        .scroll-box::-webkit-scrollbar { width: 4px; }
        .scroll-box::-webkit-scrollbar-track { background: transparent; }
        .scroll-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
        .fixed-height { height: 460px; display: flex; flex-direction: column; }
        @media(max-width:768px) { .fixed-height { height: 380px; } }

        /* ====== JADWAL ====== */
        .hari-group { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 14px; padding: 14px; margin-bottom: 12px; }
        .hari-label {
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: #4f46e5;
            display: flex; align-items: center; gap: 6px; margin-bottom: 10px;
        }
        .hari-dot { width: 7px; height: 7px; border-radius: 50%; background: #4f46e5; flex-shrink: 0; }
        .jadwal-item {
            display: flex; align-items: center; gap: 12px;
            background: white; border-radius: 12px;
            padding: 10px 14px; margin-bottom: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .jadwal-item:last-child { margin-bottom: 0; }
        .jadwal-time {
            background: #eef2ff; color: #4f46e5;
            border-radius: 8px; padding: 5px 10px;
            font-size: 0.75rem; font-weight: 800;
            white-space: nowrap; flex-shrink: 0;
        }
        .jadwal-mapel { font-weight: 700; color: #0f172a; font-size: 0.88rem; }
        .jadwal-guru { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-top: 2px; }

        /* ====== PRESENSI ====== */
        .presensi-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 16px;
            background: #f8fafc; border: 1px solid #f1f5f9;
            border-radius: 14px; margin-bottom: 8px;
            transition: background 0.15s;
        }
        .presensi-item:hover { background: #f1f5f9; }
        .presensi-item:last-child { margin-bottom: 0; }
        .presensi-date { font-weight: 700; color: #0f172a; font-size: 0.88rem; }
        .presensi-note { font-size: 0.75rem; color: #64748b; margin-top: 3px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 8px; font-size: 0.72rem; font-weight: 800; }
        .badge-hadir { background: #dcfce7; color: #15803d; }
        .badge-sakit { background: #fef9c3; color: #a16207; }
        .badge-izin { background: #dbeafe; color: #1d4ed8; }
        .badge-alpha { background: #fee2e2; color: #b91c1c; }

        /* ====== NILAI TABLE ====== */
        .nilai-table { width: 100%; border-collapse: collapse; }
        .nilai-table th {
            padding: 12px 16px; text-align: left;
            font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: #64748b; background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }
        .nilai-table td { padding: 14px 16px; font-size: 0.875rem; border-bottom: 1px solid #f8fafc; }
        .nilai-table tr:last-child td { border-bottom: none; }
        .nilai-table tr:hover td { background: #f8fafc; }
        .nilai-mapel { font-weight: 700; color: #0f172a; }
        .nilai-jenis { display: inline-block; background: #f1f5f9; color: #475569; padding: 3px 10px; border-radius: 7px; font-size: 0.75rem; font-weight: 600; }
        .nilai-score-good { background: #dcfce7; color: #15803d; }
        .nilai-score-bad { background: #fee2e2; color: #b91c1c; }
        .nilai-score {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 54px; padding: 5px 8px;
            border-radius: 10px; font-weight: 900; font-size: 0.9rem;
        }

        /* ====== CATATAN / DARK CARD ====== */
        .dark-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border-radius: 20px; padding: 1.75rem; color: white;
            position: relative; overflow: hidden;
        }
        @media(max-width:768px) { .dark-card { padding: 1.25rem; } }
        .dark-card::before {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 120px; height: 120px;
            border-radius: 50%; background: rgba(255,255,255,0.04);
        }
        .dark-card .inner { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; }
        .catatan-item { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 16px; margin-bottom: 10px; }
        .catatan-item:last-child { margin-bottom: 0; }
        .catatan-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .catatan-predikat { font-size: 0.72rem; font-weight: 800; padding: 4px 10px; border-radius: 7px; background: rgba(255,255,255,0.15); color: white; }
        .catatan-date { font-size: 0.68rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.07em; }
        .catatan-text { font-size: 0.875rem; color: rgba(255,255,255,0.8); line-height: 1.6; font-style: italic; margin-bottom: 10px; }
        .catatan-footer { display: flex; align-items: center; gap: 8px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; }
        .catatan-guru-avatar { width: 22px; height: 22px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; color: white; }
        .catatan-guru-name { font-size: 0.78rem; color: rgba(255,255,255,0.5); font-weight: 600; }

        /* ====== EMPTY STATE ====== */
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 2rem; color: #94a3b8; text-align: center; }
        .empty-state svg { opacity: 0.25; margin-bottom: 10px; }
        .empty-state p { font-size: 0.875rem; font-weight: 500; margin: 0; }
        .empty-warning {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: 20px; padding: 3rem 2rem; text-align: center;
            max-width: 540px; margin: 4rem auto;
        }

        /* ====== FOOTER ====== */
        .portal-footer { text-align: center; padding: 2rem; font-size: 0.8rem; color: #94a3b8; }

        /* ====== SPACER ====== */
        .space-y > * + * { margin-top: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .total-badge { background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }

        /* Tab content visibility */
        .tab-pane { display: none; }
        .tab-pane.active { display: block; animation: fadeUp 0.4s ease-out; }
    </style>
</head>
<body>

    @if(Auth::guard('ortu')->check())
    <nav class="portal-nav">
        <div class="brand">
            <div class="brand-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div>
                <div class="brand-title">Portal Wali Murid</div>
                <div class="brand-sub">SD Negeri 1 Passo</div>
            </div>
        </div>

        <div class="nav-actions">
            <a href="{{ route('portal.ortu.profil') }}" class="nav-profile-btn">
                <div class="avatar">{{ substr(Auth::guard('ortu')->user()->nama, 0, 1) }}</div>
                <div class="nav-text">
                    <div class="nav-name">{{ Auth::guard('ortu')->user()->nama }}</div>
                    <div class="nav-sub">Pengaturan Akun</div>
                </div>
            </a>
            <div class="nav-divider"></div>
            <form action="{{ route('logout.ortu') }}" method="POST">
                @csrf
                <button type="submit" class="nav-logout-btn" title="Keluar">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </nav>
    @endif

    <main style="min-height: calc(100vh - 64px);">
        @yield('content')
    </main>

    @if(Auth::guard('ortu')->check())
    <footer class="portal-footer">
        &copy; {{ date('Y') }} SD Negeri 1 Passo &mdash; Sistem Pemantauan Akademik Siswa
    </footer>
    @endif

</body>
</html>

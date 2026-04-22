@extends('layouts.portal-ortu')

@section('title', 'Login Wali Murid')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;position:relative;overflow:hidden;background:linear-gradient(145deg,#f0f7ff 0%,#faf5ff 50%,#f0fdf4 100%);">

    {{-- Background blobs --}}
    <div style="position:absolute;top:-120px;right:-120px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,0.1),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-120px;left:-120px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(79,70,229,0.08),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;top:50%;left:10%;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(34,197,94,0.06),transparent 70%);pointer-events:none;"></div>

    <div style="width:100%;max-width:460px;position:relative;z-index:1;">

        {{-- Logo & Title --}}
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,#2563eb,#4f46e5);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;box-shadow:0 8px 30px rgba(37,99,235,0.35);">
                <svg width="36" height="36" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 style="font-size:1.75rem;font-weight:900;color:#0f172a;line-height:1.2;margin-bottom:6px;">Portal Wali Murid</h1>
            <p style="font-size:0.9rem;color:#64748b;font-weight:500;">SD Negeri 1 Passo</p>
        </div>

        {{-- Form Card --}}
        <div style="background:rgba(255,255,255,0.95);border-radius:24px;padding:2.5rem 2rem;box-shadow:0 20px 60px rgba(0,0,0,0.1);border:1px solid rgba(255,255,255,0.8);backdrop-filter:blur(16px);">

            {{-- Error Alert --}}
            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:14px 16px;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:10px;">
                <div style="width:20px;height:20px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                    <svg width="12" height="12" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
                <ul style="list-style:none;padding:0;margin:0;">
                    @foreach($errors->all() as $error)
                    <li style="font-size:0.875rem;color:#b91c1c;font-weight:600;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login.ortu.post') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div style="margin-bottom:1.25rem;">
                    <label for="email" style="display:block;font-size:0.875rem;font-weight:700;color:#374151;margin-bottom:8px;">Alamat Email</label>
                    <div style="position:relative;">
                        <div style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <input
                            type="email" id="email" name="email"
                            value="{{ old('email') }}" required
                            placeholder="email@contoh.com"
                            style="width:100%;padding:14px 14px 14px 44px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;font-size:0.95rem;color:#0f172a;outline:none;transition:all 0.2s;font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb';this.style.background='white';"
                            onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:1.5rem;">
                    <label for="password" style="display:block;font-size:0.875rem;font-weight:700;color:#374151;margin-bottom:8px;">Kata Sandi</label>
                    <div style="position:relative;">
                        <div style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        </div>
                        <input
                            type="password" id="password" name="password"
                            required placeholder="••••••••"
                            style="width:100%;padding:14px 44px 14px 44px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;font-size:0.95rem;color:#0f172a;outline:none;transition:all 0.2s;font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb';this.style.background='white';"
                            onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';"
                        >
                        <button type="button" onclick="togglePwd()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:4px;">
                            <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:8px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="remember" style="width:16px;height:16px;accent-color:#2563eb;cursor:pointer;">
                        <span style="font-size:0.875rem;color:#475569;font-weight:500;">Ingat saya</span>
                    </label>
                    <a href="#" style="font-size:0.875rem;color:#2563eb;font-weight:700;text-decoration:none;">Lupa sandi?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" style="width:100%;padding:15px;background:linear-gradient(135deg,#2563eb,#4f46e5);color:white;border:none;border-radius:14px;font-size:1rem;font-weight:800;cursor:pointer;box-shadow:0 4px 20px rgba(37,99,235,0.4);transition:all 0.2s;font-family:inherit;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 28px rgba(37,99,235,0.45)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(37,99,235,0.4)'">
                    Masuk ke Dashboard
                </button>
            </form>

            {{-- Info Box --}}
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:14px;padding:14px 16px;margin-top:1.5rem;display:flex;gap:10px;align-items:flex-start;">
                <svg width="18" height="18" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p style="font-size:0.8rem;color:#0369a1;line-height:1.5;margin:0;">Email dan kata sandi Anda diberikan oleh pihak sekolah. Hubungi tata usaha jika belum menerima.</p>
            </div>
        </div>

        {{-- Back Link --}}
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.875rem;color:#64748b;text-decoration:none;font-weight:600;transition:color 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Website Utama
            </a>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>
@endsection

@extends('layouts.frontend')
@section('title', 'Lupa Password')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;padding:2rem 1rem;">
    <div style="width:100%;max-width:440px;">
        
        <div style="text-align:center;margin-bottom:2.5rem;">
            @if(isset($settings) && $settings->logo)
                <img src="{{ Storage::url($settings->logo) }}" alt="Logo Sekolah" style="height:70px;margin-bottom:1rem;filter:drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
            @else
                <div style="height:70px;width:70px;background:linear-gradient(135deg,#2563eb,#4f46e5);border-radius:18px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:2rem;font-weight:bold;margin-bottom:1rem;box-shadow:0 10px 25px rgba(37,99,235,0.3);">SD</div>
            @endif
            <h1 style="font-size:1.75rem;color:#0f172a;font-weight:800;letter-spacing:-0.025em;margin-bottom:0.5rem;">Lupa Kata Sandi</h1>
            <p style="font-size:0.9rem;color:#64748b;font-weight:500;">Masukkan email Anda untuk menerima OTP</p>
        </div>

        <div style="background:rgba(255,255,255,0.95);border-radius:24px;padding:2.5rem 2rem;box-shadow:0 20px 60px rgba(0,0,0,0.1);border:1px solid rgba(255,255,255,0.8);">

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

            <form action="{{ route('ortu.forgot_password.post') }}" method="POST">
                @csrf
                <div style="margin-bottom:1.5rem;">
                    <label for="email" style="display:block;font-size:0.875rem;font-weight:700;color:#374151;margin-bottom:8px;">Alamat Email</label>
                    <div style="position:relative;">
                        <input type="email" id="email" name="email" required placeholder="email@contoh.com" style="width:100%;padding:14px 14px 14px 14px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;font-size:0.95rem;color:#0f172a;outline:none;transition:all 0.2s;" onfocus="this.style.borderColor='#2563eb';this.style.background='white';" onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
                    </div>
                </div>

                <button type="submit" style="width:100%;padding:15px;background:linear-gradient(135deg,#2563eb,#4f46e5);color:white;border:none;border-radius:14px;font-size:1rem;font-weight:800;cursor:pointer;box-shadow:0 4px 20px rgba(37,99,235,0.4);transition:all 0.2s;">
                    Kirim Kode OTP
                </button>
            </form>
        </div>

        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('login.ortu') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.875rem;color:#64748b;text-decoration:none;font-weight:600;">
                Kembali ke halaman Login
            </a>
        </div>
    </div>
</div>
@endsection

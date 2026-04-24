@extends('layouts.frontend')
@section('title', 'Buat Password Baru')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;padding:2rem 1rem;">
    <div style="width:100%;max-width:440px;">
        
        <div style="text-align:center;margin-bottom:2.5rem;">
            <h1 style="font-size:1.75rem;color:#0f172a;font-weight:800;margin-bottom:0.5rem;">Buat Sandi Baru</h1>
            <p style="font-size:0.9rem;color:#64748b;font-weight:500;">Silakan masukkan kata sandi baru Anda</p>
        </div>

        <div style="background:rgba(255,255,255,0.95);border-radius:24px;padding:2.5rem 2rem;box-shadow:0 20px 60px rgba(0,0,0,0.1);border:1px solid rgba(255,255,255,0.8);">

            @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:14px 16px;margin-bottom:1.5rem;">
                <p style="color:#166534;font-size:0.875rem;margin:0;font-weight:600;">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:14px 16px;margin-bottom:1.5rem;">
                @foreach($errors->all() as $error)
                <p style="color:#b91c1c;font-size:0.875rem;margin:0;font-weight:600;">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('ortu.reset_password.post') }}" method="POST">
                @csrf
                <div style="margin-bottom:1.25rem;">
                    <label for="password" style="display:block;font-size:0.875rem;font-weight:700;color:#374151;margin-bottom:8px;">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required style="width:100%;padding:14px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;font-size:0.95rem;color:#0f172a;outline:none;transition:all 0.2s;" onfocus="this.style.borderColor='#2563eb';this.style.background='white';" onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label for="password_confirmation" style="display:block;font-size:0.875rem;font-weight:700;color:#374151;margin-bottom:8px;">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required style="width:100%;padding:14px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;font-size:0.95rem;color:#0f172a;outline:none;transition:all 0.2s;" onfocus="this.style.borderColor='#2563eb';this.style.background='white';" onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
                </div>

                <button type="submit" style="width:100%;padding:15px;background:linear-gradient(135deg,#2563eb,#4f46e5);color:white;border:none;border-radius:14px;font-size:1rem;font-weight:800;cursor:pointer;box-shadow:0 4px 20px rgba(37,99,235,0.4);transition:all 0.2s;">
                    Simpan Sandi Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.frontend')

@section('title', 'Profil Sekolah - SD Negeri 1 Passo')
@section('meta_description', 'Profil lengkap SD Negeri 1 Passo, termasuk visi, misi, dan sejarah sekolah.')

@push('styles')
<style>
    .profil-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);
        padding: 4rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .profil-hero::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0; right: 0; height: 60px;
        background: #f8faff;
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    .vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media(max-width:768px) { .vm-grid { grid-template-columns: 1fr; } }

    .vm-card {
        background: var(--surface); border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    .vm-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem; flex-shrink: 0;
    }
    .vm-title { font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 1rem; }
    .vm-content { font-size: 0.95rem; line-height: 1.8; color: var(--text-muted); }
    .vm-content ul { padding-left: 1.25rem; margin: 0; }
    .vm-content li { margin-bottom: 8px; }
    .vm-content p { margin: 0; }

    .sejarah-block {
        background: var(--surface); border-radius: 20px;
        padding: 2.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    .sejarah-block h2 {
        font-size: 1.4rem; font-weight: 800; color: var(--text);
        margin-bottom: 1rem; display: flex; align-items: center; gap: 12px;
    }
    .sejarah-icon { width: 42px; height: 42px; background: rgba(22, 163, 74, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--success); flex-shrink: 0; }
    .sejarah-body { font-size: 0.95rem; line-height: 1.85; color: var(--text-muted); }
    .sejarah-body p { margin-bottom: 1rem; }
    .sejarah-body p:last-child { margin-bottom: 0; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="profil-hero">
    <div style="position:relative;z-index:1;">
        <div class="section-tag" style="display:inline-flex;margin-bottom:16px;">
            <span class="section-tag-dot" style="background:#60a5fa;"></span>
            <span style="color:#60a5fa;">Tentang Sekolah</span>
        </div>
        <h1 style="font-size:clamp(1.8rem,5vw,3rem);font-weight:900;color:white;margin-bottom:12px;">Profil Sekolah</h1>
        <p style="color:#93c5fd;max-width:500px;margin:0 auto;line-height:1.7;">SD Negeri 1 Passo — Unggul, Berkarakter, dan Berprestasi</p>
    </div>
</div>

{{-- Content --}}
<section style="padding:3rem 0 5rem;background:var(--bg);transition:background 0.3s;">
    <div class="container" style="max-width:900px;">

        {{-- Visi & Misi --}}
        <div class="vm-grid" style="margin-bottom:1.5rem;">

            {{-- Visi --}}
            <div class="vm-card">
                <div class="vm-icon" style="background:var(--primary-light);">
                    <svg width="26" height="26" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div class="vm-title">{{ $visi->judul ?? 'Visi Sekolah' }}</div>
                <div class="vm-content">{!! $visi->isi ?? '<p>Mewujudkan sekolah yang berprestasi dan berkarakter.</p>' !!}</div>
            </div>

            {{-- Misi --}}
            <div class="vm-card">
                <div class="vm-icon" style="background:var(--surface-2);">
                    <svg width="26" height="26" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div class="vm-title">{{ $misi->judul ?? 'Misi Sekolah' }}</div>
                <div class="vm-content">{!! $misi->isi ?? '<p>Menyelenggarakan pendidikan berkualitas.</p>' !!}</div>
            </div>
        </div>

        {{-- Sejarah --}}
        @if($sejarah)
        <div class="sejarah-block">
            <h2>
                <div class="sejarah-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                {{ $sejarah->judul ?? 'Sejarah Singkat' }}
            </h2>
            <div class="sejarah-body">{!! $sejarah->isi !!}</div>
        </div>
        @endif

        {{-- Statistik --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:1.5rem;">
            <div style="background:linear-gradient(135deg,#2563eb,#4f46e5);border-radius:20px;padding:2rem;text-align:center;color:white;box-shadow:0 4px 20px rgba(37,99,235,0.3);">
                <div style="font-size:2.5rem;font-weight:900;margin-bottom:6px;">500+</div>
                <div style="font-size:0.85rem;font-weight:600;opacity:0.85;">Siswa Aktif</div>
            </div>
            <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);border-radius:20px;padding:2rem;text-align:center;color:white;box-shadow:0 4px 20px rgba(124,58,237,0.3);">
                <div style="font-size:2.5rem;font-weight:900;margin-bottom:6px;">30+</div>
                <div style="font-size:0.85rem;font-weight:600;opacity:0.85;">Tenaga Pendidik</div>
            </div>
            <div style="background:linear-gradient(135deg,#059669,#047857);border-radius:20px;padding:2rem;text-align:center;color:white;box-shadow:0 4px 20px rgba(5,150,105,0.3);">
                <div style="font-size:2.5rem;font-weight:900;margin-bottom:6px;">A</div>
                <div style="font-size:0.85rem;font-weight:600;opacity:0.85;">Akreditasi BAN-S/M</div>
            </div>
        </div>
        <style>
            @media(max-width:560px) {
                div[style*="repeat(3,1fr)"] { grid-template-columns: 1fr !important; }
            }
        </style>

    </div>
</section>

@endsection

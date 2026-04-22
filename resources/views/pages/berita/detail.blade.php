@extends('layouts.frontend')

@section('title', $berita->judul . ' - SD Negeri 1 Passo')
@section('meta_description', Str::limit(strip_tags($berita->isi), 150))

@push('styles')
<style>
    .article-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 3.5rem 1.5rem 5rem;
        position: relative;
        overflow: hidden;
    }
    .article-hero::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0; right: 0; height: 80px;
        background: white;
        clip-path: ellipse(55% 100% at 50% 100%);
    }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; flex-wrap: wrap; margin-bottom: 2rem; }
    .breadcrumb a { color: #64748b; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #2563eb; }
    .breadcrumb span { color: #cbd5e1; }

    .article-body { font-size: 1.05rem; line-height: 1.85; color: #374151; }
    .article-body p { margin-bottom: 1.25rem; }
    .article-body h2, .article-body h3 { font-weight: 800; color: #0f172a; margin: 2rem 0 1rem; }
    .article-body h2 { font-size: 1.4rem; }
    .article-body h3 { font-size: 1.2rem; }
    .article-body ul, .article-body ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .article-body li { margin-bottom: 8px; }
    .article-body blockquote { border-left: 4px solid #2563eb; background: #eff6ff; padding: 1rem 1.5rem; border-radius: 0 12px 12px 0; margin: 1.5rem 0; font-style: italic; color: #1e40af; }
    .article-body img { max-width: 100%; border-radius: 12px; }
    .article-body a { color: #2563eb; }

    .related-card {
        background: white; border-radius: 16px;
        overflow: hidden; border: 1px solid #f1f5f9;
        transition: transform 0.25s, box-shadow 0.25s;
        text-decoration: none; display: block;
    }
    .related-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
    .related-card img { width: 100%; height: 160px; object-fit: cover; }
    .related-card .related-body { padding: 1rem; }
    .related-card .related-title { font-weight: 700; color: #0f172a; font-size: 0.9rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .related-card:hover .related-title { color: #2563eb; }
    .related-card .related-date { font-size: 0.75rem; color: #94a3b8; margin-top: 6px; font-weight: 600; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="article-hero">
    <div class="container" style="position:relative;z-index:1;">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <a href="{{ route('berita.index') }}">Berita</a>
            <span>›</span>
            <span style="color:#e2e8f0;">{{ Str::limit($berita->judul, 40) }}</span>
        </div>

        <div style="max-width:760px;">
            <span style="display:inline-block;background:rgba(37,99,235,0.8);color:white;font-size:0.75rem;font-weight:800;padding:4px 14px;border-radius:999px;margin-bottom:14px;">
                {{ $berita->kategori ?? 'Berita' }}
            </span>
            <h1 style="font-size:clamp(1.6rem,4vw,2.6rem);font-weight:900;color:white;line-height:1.2;margin-bottom:16px;">
                {{ $berita->judul }}
            </h1>
            <div style="display:flex;align-items:center;gap:12px;color:#93c5fd;font-size:0.88rem;font-weight:500;flex-wrap:wrap;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ \Carbon\Carbon::parse($berita->published_at)->translatedFormat('d F Y') }}
                </span>
                <span style="opacity:0.4;">•</span>
                <span style="display:flex;align-items:center;gap:6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                    Waktu baca ~{{ max(1, intval(str_word_count(strip_tags($berita->isi)) / 200)) }} menit
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Article Content --}}
<section style="background:white;padding-bottom:4rem;">
    <div class="container" style="max-width:800px;">

        {{-- Featured Image --}}
        @if($berita->foto)
        <div style="margin: -40px 0 2.5rem; border-radius: 20px; overflow: hidden; box-shadow: 0 12px 40px rgba(0,0,0,0.12);">
            <img src="{{ Storage::url($berita->foto) }}" alt="{{ $berita->judul }}" style="width:100%;height:auto;max-height:480px;object-fit:cover;display:block;">
        </div>
        @else
        <div style="height:2rem;"></div>
        @endif

        {{-- Article Body --}}
        <div class="article-body">
            {!! $berita->isi !!}
        </div>

        {{-- Share Section --}}
        <div style="border-top:1px solid #f1f5f9;padding-top:2rem;margin-top:2.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div style="font-size:0.9rem;font-weight:700;color:#475569;">Bagikan Artikel:</div>
            <div style="display:flex;gap:8px;">
                <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}" target="_blank" style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:#25d366;color:white;border-radius:10px;font-size:0.8rem;font-weight:700;text-decoration:none;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank" style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:#1d9bf0;color:white;border-radius:10px;font-size:0.8rem;font-weight:700;text-decoration:none;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                    Twitter
                </a>
            </div>
        </div>

        {{-- Back button --}}
        <div style="margin-top:1.5rem;">
            <a href="{{ route('berita.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:#64748b;font-size:0.9rem;font-weight:600;text-decoration:none;padding:10px 16px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.color='#2563eb';this.style.borderColor='#bfdbfe'" onmouseout="this.style.color='#64748b';this.style.borderColor='#e2e8f0'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Berita
            </a>
        </div>
    </div>
</section>

{{-- Berita Terkait --}}
@if($beritaLainnya->count() > 0)
<section style="background:#f8faff;padding:3rem 0 5rem;">
    <div class="container">
        <h3 style="font-size:1.4rem;font-weight:900;color:#0f172a;margin-bottom:1.5rem;">Berita Lainnya</h3>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;">
            @foreach($beritaLainnya as $lain)
            <a href="{{ route('berita.detail', $lain->slug) }}" class="related-card">
                @if($lain->foto)
                    <img src="{{ Storage::url($lain->foto) }}" alt="{{ $lain->judul }}">
                @else
                    <div style="height:160px;background:linear-gradient(135deg,#dbeafe,#ede9fe);display:flex;align-items:center;justify-content:center;">
                        <svg width="36" height="36" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                @endif
                <div class="related-body">
                    <div class="related-title">{{ $lain->judul }}</div>
                    <div class="related-date">{{ \Carbon\Carbon::parse($lain->published_at)->translatedFormat('d M Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <style>
        @media(max-width:640px) {
            section > .container > div[style*="repeat(3"] { grid-template-columns: 1fr !important; }
        }
        @media(max-width:900px) and (min-width:641px) {
            section > .container > div[style*="repeat(3"] { grid-template-columns: repeat(2,1fr) !important; }
        }
    </style>
</section>
@endif

@endsection

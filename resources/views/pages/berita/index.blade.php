@extends('layouts.frontend')

@section('title', 'Berita & Pengumuman - SD Negeri 1 Passo')
@section('meta_description', 'Berita terbaru, pengumuman resmi, dan informasi prestasi dari SD Negeri 1 Passo.')

@push('styles')
<style>
    .berita-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%);
        padding: 4rem 1.5rem 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .berita-hero::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0; right: 0; height: 60px;
        background: #f8faff;
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    .news-card {
        background: var(--surface);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .news-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
    .news-card-img { position: relative; aspect-ratio: 16/10; overflow: hidden; background: linear-gradient(135deg, #dbeafe, #ede9fe); }
    .news-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .news-card:hover .news-card-img img { transform: scale(1.06); }
    .news-card-img .no-img { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
    .news-kategori { position: absolute; top: 12px; left: 12px; background: rgba(var(--surface-rgb),0.95); color: var(--primary); font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 999px; backdrop-filter: blur(4px); }
    .news-body { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
    .news-date { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 6px; margin-bottom: 10px; }
    .news-title { font-size: 1.05rem; font-weight: 800; color: var(--text); margin-bottom: 10px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s; }
    .news-card:hover .news-title { color: var(--primary); }
    .news-excerpt { font-size: 0.875rem; color: var(--text-muted); line-height: 1.65; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 16px; }
    .news-read-more { display: inline-flex; align-items: center; gap: 6px; color: var(--primary); font-size: 0.85rem; font-weight: 700; text-decoration: none; margin-top: auto; }
    .news-read-more svg { transition: transform 0.2s; }
    .news-card:hover .news-read-more svg { transform: translateX(4px); }

    .news-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    @media(max-width:900px) { .news-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width:560px) { .news-grid { grid-template-columns: 1fr; } }

    /* Featured (first card) */
    .news-featured { grid-column: 1 / -1; }
    .news-featured .news-card { flex-direction: row; }
    .news-featured .news-card-img { width: 50%; aspect-ratio: auto; min-height: 280px; flex-shrink: 0; }
    .news-featured .news-body { padding: 2rem 2rem; }
    .news-featured .news-title { font-size: 1.5rem; -webkit-line-clamp: 3; }
    @media(max-width:768px) {
        .news-featured { grid-column: auto; }
        .news-featured .news-card { flex-direction: column; }
        .news-featured .news-card-img { width: 100%; min-height: 200px; }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="berita-hero">
    <div style="position:relative;z-index:1;">
        <div class="section-tag" style="display:inline-flex;margin-bottom:16px;">
            <span class="section-tag-dot" style="background:#60a5fa;"></span>
            <span style="color:#60a5fa;">Informasi Terbaru</span>
        </div>
        <h1 style="font-size:clamp(1.8rem,5vw,3rem);font-weight:900;color:white;margin-bottom:12px;">Berita & Pengumuman</h1>
        <p style="color:#93c5fd;max-width:540px;margin:0 auto;line-height:1.7;">Informasi, pengumuman resmi, dan berita prestasi terbaru dari SD Negeri 1 Passo.</p>
    </div>
</div>

{{-- Content --}}
<section style="padding:3rem 0 5rem;background:var(--bg);transition:background 0.3s;">
    <div class="container">
        <div class="news-grid">
            @forelse($berita as $idx => $item)
            <div class="{{ $idx === 0 ? 'news-featured' : '' }}">
                <article class="news-card">
                    <div class="news-card-img">
                        @if($item->foto)
                            <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->judul }}" loading="lazy">
                        @else
                            <div class="no-img" style="background:linear-gradient(135deg,#dbeafe,#ede9fe);">
                                <svg width="44" height="44" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                        <span class="news-kategori">{{ $item->kategori ?? 'Berita' }}</span>
                    </div>
                    <div class="news-body">
                        <div class="news-date">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('d F Y') }}
                        </div>
                        <h2 class="news-title">{{ $item->judul }}</h2>
                        <p class="news-excerpt">{{ Str::limit(strip_tags($item->isi), $idx === 0 ? 200 : 100) }}</p>
                        <a href="{{ route('berita.detail', $item->slug) }}" class="news-read-more">
                            Baca Selengkapnya
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem;background:var(--surface);border-radius:20px;border:2px dashed var(--border);">
                <svg width="48" height="48" fill="none" stroke="var(--text-light)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:8px;">Belum Ada Berita</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Informasi terbaru akan segera dipublikasikan.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($berita->hasPages())
        <div style="margin-top:2.5rem;">{{ $berita->links('pagination::tailwind') }}</div>
        @endif
    </div>
</section>

@endsection

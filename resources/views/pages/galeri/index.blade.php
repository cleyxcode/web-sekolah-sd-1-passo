@extends('layouts.frontend')

@section('title', 'Galeri Kegiatan - SD Negeri 1 Passo')
@section('meta_description', 'Koleksi foto dan dokumentasi kegiatan siswa-siswi SD Negeri 1 Passo.')

@push('styles')
<style>
    .galeri-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 4rem 0 3rem;
        position: relative;
        overflow: hidden;
    }
    .galeri-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .galeri-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }
    @media(max-width:640px) {
        .galeri-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    }
    .galeri-card {
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        background: #e2e8f0;
        aspect-ratio: 1;
        cursor: pointer;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .galeri-card:hover { transform: scale(1.02); box-shadow: 0 12px 40px rgba(0,0,0,0.15); }
    .galeri-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .galeri-card:hover img { transform: scale(1.08); }
    .galeri-card .overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0) 50%);
        opacity: 0; transition: opacity 0.3s;
        display: flex; flex-direction: column; justify-content: flex-end; padding: 1rem;
    }
    .galeri-card:hover .overlay { opacity: 1; }
    .overlay-title { color: white; font-weight: 700; font-size: 0.9rem; }
    .overlay-desc { color: rgba(255,255,255,0.75); font-size: 0.78rem; margin-top: 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .galeri-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #dbeafe, #ede9fe);
        color: #93c5fd;
    }
    .filter-btn {
        padding: 8px 20px; border-radius: 999px;
        font-size: 0.85rem; font-weight: 700;
        border: 2px solid transparent; cursor: pointer;
        transition: all 0.2s; background: transparent;
    }
    .filter-btn.active { background: linear-gradient(135deg, #2563eb, #4f46e5); color: white; border-color: transparent; box-shadow: 0 3px 12px rgba(37,99,235,0.3); }
    .filter-btn:not(.active) { background: var(--surface); color: var(--text-muted); border-color: var(--border); }
    .filter-btn:not(.active):hover { border-color: var(--primary-light); color: var(--primary); }

    /* Lightbox */
    .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 9999; align-items: center; justify-content: center; }
    .lightbox.open { display: flex; }
    .lightbox img { max-width: 90vw; max-height: 85vh; border-radius: 12px; object-fit: contain; }
    .lightbox-close { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.15); border: none; width: 44px; height: 44px; border-radius: 50%; color: white; cursor: pointer; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
    .lightbox-close:hover { background: rgba(255,255,255,0.25); }
    .lightbox-caption { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 10px 20px; border-radius: 12px; color: white; font-size: 0.9rem; font-weight: 600; white-space: nowrap; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="galeri-hero">
    <div class="container" style="position:relative;z-index:1;text-align:center;">
        <div class="section-tag" style="display:inline-flex;margin-bottom:16px;">
            <span class="section-tag-dot" style="background:#60a5fa;"></span>
            <span style="color:#60a5fa;">Dokumentasi Sekolah</span>
        </div>
        <h1 style="font-size:clamp(1.8rem,5vw,3rem);font-weight:900;color:white;margin-bottom:14px;">Galeri Kegiatan</h1>
        <p style="color:#94a3b8;max-width:540px;margin:0 auto;line-height:1.75;font-size:1rem;">Koleksi foto dan video aktivitas belajar mengajar serta ekstrakurikuler siswa-siswi SD Negeri 1 Passo.</p>
    </div>
</div>

{{-- Filter & Grid --}}
<section style="padding: 3rem 0 5rem;background:var(--bg);transition:background 0.3s;">
    <div class="container">

        {{-- Filter Buttons --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:2rem;">
            <button class="filter-btn active" onclick="filterGaleri('semua', this)">Semua</button>
            <button class="filter-btn" onclick="filterGaleri('foto', this)">📷 Foto</button>
            <button class="filter-btn" onclick="filterGaleri('video', this)">🎬 Video</button>
        </div>

        {{-- Grid --}}
        <div class="galeri-grid" id="galeriGrid">
            @forelse($galeri as $item)
            <div class="galeri-card" data-jenis="{{ $item->jenis }}" onclick="openLightbox('{{ Str::startsWith($item->file_path, 'http') ? $item->file_path : Storage::url($item->file_path) }}', '{{ addslashes($item->judul) }}')">
                @if($item->file_path)
                    <img src="{{ Str::startsWith($item->file_path, 'http') ? $item->file_path : Storage::url($item->file_path) }}" alt="{{ $item->judul }}" loading="lazy">
                @else
                    <div class="galeri-placeholder">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        <span style="margin-top:8px;font-size:0.75rem;">Foto Kegiatan</span>
                    </div>
                @endif
                @if($item->jenis === 'video')
                <div style="position:absolute;top:10px;left:10px;background:rgba(239,68,68,0.9);color:white;border-radius:8px;padding:3px 10px;font-size:0.72rem;font-weight:700;">▶ VIDEO</div>
                @endif
                <div class="overlay">
                    <div class="overlay-title">{{ $item->judul }}</div>
                    @if($item->keterangan)
                    <div class="overlay-desc">{{ $item->keterangan }}</div>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem;background:var(--surface);border-radius:20px;border:2px dashed var(--border);">
                <svg width="48" height="48" fill="none" stroke="var(--text-light)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:8px;">Belum Ada Foto</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Dokumentasi kegiatan akan segera ditambahkan.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($galeri->hasPages())
        <div style="margin-top:2.5rem;">{{ $galeri->links('pagination::tailwind') }}</div>
        @endif
    </div>
</section>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImg" src="" alt="">
    <div class="lightbox-caption" id="lightboxCaption"></div>
</div>

@push('scripts')
<script>
function openLightbox(src, title) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxCaption').textContent = title;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox(e) {
    if (!e || e.target === document.getElementById('lightbox') || e.target.classList.contains('lightbox-close')) {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', (e) => { if(e.key === 'Escape') closeLightbox(); });

function filterGaleri(jenis, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.galeri-card').forEach(card => {
        if (jenis === 'semua' || card.dataset.jenis === jenis) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endpush

@endsection

<style>
    .galeri-section { padding: 4.5rem 0; background: #f8faff; }
    .galeri-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    .galeri-top { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 12px; }

    .galeri-home-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto auto;
        gap: 1rem;
    }
    @media(max-width:900px) { .galeri-home-grid { grid-template-columns: repeat(3, 1fr); } }
    @media(max-width:640px) { .galeri-home-grid { grid-template-columns: repeat(2, 1fr); } }

    .galeri-home-item {
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 1;
        position: relative;
        background: linear-gradient(135deg, #dbeafe, #ede9fe);
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    }
    .galeri-home-item.big {
        grid-column: span 2;
        grid-row: span 2;
        aspect-ratio: 1;
    }
    @media(max-width:640px) { .galeri-home-item.big { grid-column: span 2; grid-row: span 1; aspect-ratio: 16/9; } }
    .galeri-home-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
    .galeri-home-item:hover img { transform: scale(1.08); }
    .galeri-home-item .overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(15,23,42,0.7) 0%, transparent 60%);
        opacity: 0; transition: opacity 0.3s;
        display: flex; align-items: flex-end; padding: 12px;
    }
    .galeri-home-item:hover .overlay { opacity: 1; }
    .galeri-overlay-text { color: white; font-size: 0.82rem; font-weight: 700; }
    .galeri-no-img { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #93c5fd; }
</style>

<section class="galeri-section" id="galeri">
    <div class="galeri-wrap">
        <div class="galeri-top">
            <div>
                <div class="section-tag" style="display:inline-flex;margin-bottom:10px;">
                    <span class="section-tag-dot"></span>
                    Dokumentasi
                </div>
                <h2 class="section-title" style="font-size:clamp(1.4rem,3vw,2rem);">Galeri Kegiatan</h2>
            </div>
            <a href="{{ route('galeri') }}" class="lihat-semua" style="display:inline-flex;align-items:center;gap:6px;color:#2563eb;font-size:0.9rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                Lihat Semua
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($galeri->count() > 0)
        <div class="galeri-home-grid">
            @foreach($galeri->take(7) as $idx => $item)
            <div class="galeri-home-item {{ $idx === 0 ? 'big' : '' }}">
                @if($item->file_path)
                    <img src="{{ Str::startsWith($item->file_path, 'http') ? $item->file_path : Storage::url($item->file_path) }}" alt="{{ $item->judul }}" loading="lazy">
                @else
                    <div class="galeri-no-img">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                @endif
                <div class="overlay">
                    <span class="galeri-overlay-text">{{ Str::limit($item->judul, 30) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:3rem;background:white;border-radius:16px;border:2px dashed #e2e8f0;">
            <p style="color:#94a3b8;font-size:0.9rem;font-weight:600;">Belum ada foto yang ditambahkan.</p>
        </div>
        @endif

        <div style="text-align:center;margin-top:1.75rem;">
            <a href="{{ route('galeri') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:white;border:2px solid #e2e8f0;border-radius:14px;font-size:0.9rem;font-weight:700;color:#475569;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='#93c5fd';this.style.color='#2563eb'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                Lihat Semua Galeri
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

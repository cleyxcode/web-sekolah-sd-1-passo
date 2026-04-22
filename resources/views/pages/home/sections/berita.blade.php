<style>
    .berita-section { padding: 4.5rem 0; background: white; }
    .berita-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    .berita-top { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 12px; }
    .lihat-semua { display: inline-flex; align-items: center; gap: 6px; color: #2563eb; font-size: 0.9rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .lihat-semua:hover { text-decoration: underline; }

    .berita-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
    @media(max-width:900px) { .berita-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width:560px) { .berita-grid { grid-template-columns: 1fr; } }

    .berita-card {
        background: white; border-radius: 18px;
        overflow: hidden; border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
        transition: transform 0.25s, box-shadow 0.25s;
        text-decoration: none;
    }
    .berita-card:hover { transform: translateY(-4px); box-shadow: 0 10px 35px rgba(0,0,0,0.1); }
    .berita-card-img {
        position: relative; aspect-ratio: 16/10;
        background: linear-gradient(135deg, #dbeafe, #ede9fe);
        overflow: hidden;
    }
    .berita-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .berita-card:hover .berita-card-img img { transform: scale(1.06); }
    .berita-no-img { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
    .berita-kategori-badge {
        position: absolute; top: 10px; left: 10px;
        background: rgba(255,255,255,0.95); color: #2563eb;
        font-size: 0.7rem; font-weight: 800;
        padding: 3px 10px; border-radius: 999px;
        backdrop-filter: blur(4px);
    }
    .berita-body { padding: 1.25rem; display: flex; flex-direction: column; flex: 1; }
    .berita-date { font-size: 0.75rem; color: #94a3b8; font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 5px; }
    .berita-judul { font-size: 0.95rem; font-weight: 800; color: #0f172a; line-height: 1.45; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .berita-card:hover .berita-judul { color: #2563eb; }
    .berita-excerpt { font-size: 0.82rem; color: #64748b; line-height: 1.65; flex: 1; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .berita-link { font-size: 0.82rem; color: #2563eb; font-weight: 700; display: flex; align-items: center; gap: 4px; margin-top: auto; }
</style>

<section class="berita-section" id="berita">
    <div class="berita-wrap">
        <div class="berita-top">
            <div>
                <div class="section-tag" style="display:inline-flex;margin-bottom:10px;">
                    <span class="section-tag-dot"></span>
                    Informasi Terbaru
                </div>
                <h2 class="section-title" style="font-size:clamp(1.4rem,3vw,2rem);">Berita & Pengumuman</h2>
            </div>
            <a href="{{ route('berita.index') }}" class="lihat-semua">
                Lihat Semua
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="berita-grid">
            @forelse($berita as $item)
            <a href="{{ route('berita.detail', $item->slug) }}" class="berita-card">
                <div class="berita-card-img">
                    @if($item->foto)
                        <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->judul }}" loading="lazy">
                    @else
                        <div class="berita-no-img">
                            <svg width="40" height="40" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    @endif
                    <span class="berita-kategori-badge">{{ $item->kategori ?? 'Berita' }}</span>
                </div>
                <div class="berita-body">
                    <div class="berita-date">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('d M Y') }}
                    </div>
                    <div class="berita-judul">{{ $item->judul }}</div>
                    <div class="berita-excerpt">{{ Str::limit(strip_tags($item->isi), 90) }}</div>
                    <span class="berita-link">Baca selengkapnya <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem;background:#f8fafc;border-radius:16px;border:2px dashed #e2e8f0;">
                <p style="color:#94a3b8;font-size:0.9rem;font-weight:600;">Belum ada berita yang dipublikasikan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

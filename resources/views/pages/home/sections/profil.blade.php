<style>
    .profil-section { background: var(--surface-2); transition: background 0.3s; }
    .profil-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    .profil-header { text-align: center; margin-bottom: 2.5rem; }
    .profil-vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    @media(max-width:768px) { .profil-vm-grid { grid-template-columns: 1fr; } }

    .profil-card {
        background: var(--surface); border-radius: 20px;
        padding: 1.75rem 2rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: background 0.3s, border-color 0.3s;
    }
    .profil-card-icon {
        width: 46px; height: 46px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1rem;
    }
    .profil-card-title { font-size: 1.1rem; font-weight: 800; color: var(--text); margin-bottom: 10px; transition: color 0.3s; }
    .profil-card-body { font-size: 0.9rem; line-height: 1.8; color: var(--text-muted); transition: color 0.3s; }
    .profil-card-body ul { padding-left: 1.1rem; margin: 0; }
    .profil-card-body li { margin-bottom: 6px; }
    .profil-card-body p { margin: 0; }
</style>

<section class="profil-section section" id="profil">
    <div class="profil-wrap">
        <div class="profil-header">
            <div class="section-tag" style="display:inline-flex;margin-bottom:12px;">
                <span class="section-tag-dot"></span>
                Tentang Kami
            </div>
            <h2 class="section-title">Profil Sekolah</h2>
        </div>

        <div class="profil-vm-grid">
            <div class="profil-card">
                <div class="profil-card-icon" style="background:var(--primary-light);">
                    <svg width="24" height="24" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div class="profil-card-title">{{ $visi->judul ?? 'Visi Sekolah' }}</div>
                <div class="profil-card-body">{!! $visi->isi ?? 'Mewujudkan sekolah yang berprestasi dan berkarakter.' !!}</div>
            </div>

            <div class="profil-card">
                <div class="profil-card-icon" style="background:var(--surface-2);">
                    <svg width="24" height="24" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div class="profil-card-title">{{ $misi->judul ?? 'Misi Sekolah' }}</div>
                <div class="profil-card-body">{!! $misi->isi ?? 'Menyelenggarakan pendidikan berkualitas.' !!}</div>
            </div>
        </div>
    </div>
</section>

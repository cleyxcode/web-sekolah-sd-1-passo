{{-- ===== SECTION: PROFIL GURU & STAFF ===== --}}
@if($profil_guru->count() > 0)
<section id="tenaga-pendidik" style="
    padding: 80px 0;
    background: linear-gradient(160deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    position: relative;
    overflow: hidden;
">

    {{-- Decorative background blobs --}}
    <div style="position:absolute;top:-80px;left:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,0.15),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-100px;right:-100px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,0.12),transparent 70%);pointer-events:none;"></div>

    <div style="max-width:1100px;margin:0 auto;padding:0 24px;">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:52px;">
            <span style="
                display:inline-block;
                background:rgba(99,102,241,0.15);
                border:1px solid rgba(99,102,241,0.3);
                color:#a5b4fc;
                padding:6px 20px;
                border-radius:99px;
                font-size:0.75rem;
                font-weight:700;
                letter-spacing:0.12em;
                text-transform:uppercase;
                margin-bottom:16px;
            ">👨‍🏫 Tenaga Pendidik</span>
            <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:#f8fafc;margin:0 0 12px;line-height:1.2;">
                Guru &amp; Staff Kami
            </h2>
            <p style="color:#94a3b8;font-size:1rem;max-width:480px;margin:0 auto;line-height:1.7;">
                Tim tenaga pendidik profesional yang berdedikasi untuk mencerdaskan generasi bangsa.
            </p>
        </div>

        {{-- ─── SPOTLIGHT CAROUSEL ─────────────────────────────────── --}}
        <div style="position:relative;" id="guru-spotlight-wrap">

            {{-- Main Card (Spotlight) --}}
            <div style="
                display:flex;
                align-items:center;
                justify-content:center;
                gap:48px;
                min-height:360px;
            " id="guru-spotlight">

                {{-- Photo Column --}}
                <div style="flex-shrink:0;text-align:center;position:relative;" id="spotlight-photo-col">
                    <div id="spotlight-photo-ring" style="
                        width:200px;height:200px;
                        border-radius:50%;
                        padding:5px;
                        background:linear-gradient(135deg,#6366f1,#3b82f6,#06b6d4);
                        box-shadow:0 0 60px rgba(99,102,241,0.4);
                        margin:0 auto 0;
                        transition:all 0.5s ease;
                    ">
                        <div id="spotlight-photo-wrap" style="width:100%;height:100%;border-radius:50%;overflow:hidden;background:#1e293b;">
                            <img id="spotlight-img"
                                src=""
                                alt=""
                                style="width:100%;height:100%;object-fit:cover;display:none;transition:opacity 0.4s ease;"
                            />
                            <div id="spotlight-initials" style="
                                width:100%;height:100%;
                                display:flex;align-items:center;justify-content:center;
                                font-size:4rem;font-weight:900;color:white;
                                background:linear-gradient(135deg,#6366f1,#4f46e5);
                                border-radius:50%;
                                transition:opacity 0.4s ease;
                            ">?</div>
                        </div>
                    </div>
                    {{-- Badge gender --}}
                    <div id="spotlight-gender" style="
                        position:absolute;
                        bottom:4px;right:50%;transform:translateX(90px);
                        width:34px;height:34px;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;
                        border:3px solid #0f172a;
                        font-size:1rem;font-weight:900;color:white;
                        box-shadow:0 4px 12px rgba(0,0,0,0.3);
                        transition:background 0.4s;
                    ">♂</div>
                </div>

                {{-- Info Column --}}
                <div style="text-align:left;max-width:420px;" id="spotlight-info-col">
                    {{-- Nomor urut --}}
                    <div id="spotlight-counter" style="
                        font-size:0.75rem;font-weight:700;letter-spacing:0.1em;
                        text-transform:uppercase;color:#64748b;margin-bottom:10px;
                    ">— 01 / {{ $profil_guru->count() }} —</div>

                    {{-- Nama --}}
                    <h3 id="spotlight-nama" style="
                        font-size:clamp(1.4rem,3vw,2rem);
                        font-weight:900;color:#f8fafc;
                        margin:0 0 10px;
                        line-height:1.2;
                        transition:all 0.4s ease;
                    ">—</h3>

                    {{-- Jabatan Badge --}}
                    <div id="spotlight-jabatan" style="
                        display:inline-block;
                        background:linear-gradient(135deg,rgba(99,102,241,0.25),rgba(59,130,246,0.25));
                        border:1px solid rgba(99,102,241,0.4);
                        color:#a5b4fc;
                        padding:6px 18px;border-radius:99px;
                        font-size:0.85rem;font-weight:700;
                        margin-bottom:20px;
                        transition:all 0.4s ease;
                    ">—</div>

                    {{-- Divider --}}
                    <div style="height:1.5px;background:linear-gradient(90deg,rgba(99,102,241,0.5),transparent);margin-bottom:20px;"></div>

                    {{-- NIP Info --}}
                    <div id="spotlight-nip" style="color:#64748b;font-size:0.85rem;">NIP: —</div>
                </div>
            </div>

            {{-- ─── Progress dots ─────────────────────────────── --}}
            <div id="guru-dots" style="display:flex;justify-content:center;gap:8px;margin-top:32px;flex-wrap:wrap;padding:0 16px;"></div>

            {{-- ─── Prev / Next ────────────────────────────────── --}}
            <button id="guru-prev-btn" onclick="guruGoRel(-1)"
                aria-label="Sebelumnya"
                style="
                    position:absolute;left:0;top:40%;transform:translateY(-50%);
                    width:46px;height:46px;border-radius:50%;
                    background:rgba(255,255,255,0.07);
                    border:1.5px solid rgba(255,255,255,0.12);
                    display:flex;align-items:center;justify-content:center;
                    cursor:pointer;z-index:10;
                    transition:all 0.2s;
                "
                onmouseover="this.style.background='rgba(99,102,241,0.35)';this.style.borderColor='#6366f1';"
                onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.borderColor='rgba(255,255,255,0.12)';">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button id="guru-next-btn" onclick="guruGoRel(1)"
                aria-label="Berikutnya"
                style="
                    position:absolute;right:0;top:40%;transform:translateY(-50%);
                    width:46px;height:46px;border-radius:50%;
                    background:rgba(255,255,255,0.07);
                    border:1.5px solid rgba(255,255,255,0.12);
                    display:flex;align-items:center;justify-content:center;
                    cursor:pointer;z-index:10;
                    transition:all 0.2s;
                "
                onmouseover="this.style.background='rgba(99,102,241,0.35)';this.style.borderColor='#6366f1';"
                onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.borderColor='rgba(255,255,255,0.12)';">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- ─── THUMBNAIL STRIP (mini grid di bawah) ──────────────────── --}}
        <div id="guru-thumb-strip" style="
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            gap:12px;
            margin-top:40px;
            padding:0 8px;
        ">
            @foreach($profil_guru as $idx => $guru)
            <button
                onclick="guruGoTo({{ $idx }})"
                id="guru-thumb-{{ $idx }}"
                title="{{ $guru->nama }}"
                style="
                    width:52px;height:52px;border-radius:50%;
                    overflow:hidden;border:2.5px solid {{ $idx === 0 ? '#6366f1' : 'rgba(255,255,255,0.15)' }};
                    padding:0;background:#1e293b;
                    cursor:pointer;flex-shrink:0;
                    transition:all 0.25s ease;
                    box-shadow:{{ $idx === 0 ? '0 0 0 3px rgba(99,102,241,0.4)' : 'none' }};
                    opacity:{{ $idx === 0 ? '1' : '0.5' }};
                "
                onmouseover="this.style.opacity='1';"
                onmouseout="this.style.opacity=this.dataset.active==='1'?'1':'0.5';"
                data-active="{{ $idx === 0 ? '1' : '0' }}"
            >
                @if($guru->foto)
                    <img src="{{ Storage::url($guru->foto) }}" alt="{{ $guru->nama }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="
                        width:100%;height:100%;
                        display:flex;align-items:center;justify-content:center;
                        font-weight:900;font-size:1.1rem;color:white;
                        background:linear-gradient(135deg,#6366f1,#4f46e5);
                    ">{{ strtoupper(substr($guru->nama, 0, 1)) }}</div>
                @endif
            </button>
            @endforeach
        </div>

    </div>{{-- /container --}}

</section>

{{-- ─── DATA untuk JavaScript ─────────────────────────────────────────── --}}
<script>
(function () {
    const data = [
        @foreach($profil_guru as $guru)
        {
            nama: @json($guru->nama),
            jabatan: @json($guru->jabatan ?? 'Tenaga Pendidik'),
            nip: @json($guru->nip ?? '-'),
            jk: @json($guru->jenis_kelamin ?? 'L'),
            foto: @json($guru->foto ? Storage::url($guru->foto) : null),
            inisial: @json(strtoupper(substr($guru->nama, 0, 1))),
        },
        @endforeach
    ];

    let current  = 0;
    let timer    = null;
    const DELAY  = 3000; // 3 detik

    // Cache DOM
    const imgEl       = document.getElementById('spotlight-img');
    const initEl      = document.getElementById('spotlight-initials');
    const namaEl      = document.getElementById('spotlight-nama');
    const jabatanEl   = document.getElementById('spotlight-jabatan');
    const nipEl       = document.getElementById('spotlight-nip');
    const counterEl   = document.getElementById('spotlight-counter');
    const genderEl    = document.getElementById('spotlight-gender');
    const dotsEl      = document.getElementById('guru-dots');
    const totalStr    = data.length;

    // ── Buat dots ─────────────────────────────────
    function buildDots() {
        dotsEl.innerHTML = '';
        data.forEach((_, i) => {
            const d = document.createElement('button');
            d.style.cssText = `
                width:${i === 0 ? '28px' : '8px'};height:8px;border-radius:99px;
                background:${i === 0 ? '#6366f1' : 'rgba(255,255,255,0.15)'};
                border:none;cursor:pointer;padding:0;
                transition:all 0.35s ease;flex-shrink:0;
            `;
            d.setAttribute('aria-label', 'Guru ' + (i + 1));
            d.onclick = () => guruGoTo(i);
            dotsEl.appendChild(d);
        });
    }

    function updateDots(idx) {
        const dots = dotsEl.querySelectorAll('button');
        dots.forEach((d, i) => {
            d.style.width      = i === idx ? '28px' : '8px';
            d.style.background = i === idx ? '#6366f1' : 'rgba(255,255,255,0.15)';
        });
    }

    function updateThumbs(idx) {
        data.forEach((_, i) => {
            const t = document.getElementById('guru-thumb-' + i);
            if (!t) return;
            const active = i === idx;
            t.style.borderColor = active ? '#6366f1' : 'rgba(255,255,255,0.15)';
            t.style.boxShadow   = active ? '0 0 0 3px rgba(99,102,241,0.4)' : 'none';
            t.style.opacity     = active ? '1' : '0.5';
            t.dataset.active    = active ? '1' : '0';
        });
    }

    // ── Render slide ───────────────────────────────
    function render(idx, direction) {
        const g = data[idx];

        // Fade out
        const fadeEls = [imgEl, initEl, namaEl, jabatanEl, nipEl, counterEl, genderEl];
        fadeEls.forEach(el => { if (el) el.style.opacity = '0'; });

        setTimeout(() => {
            // Update photo
            if (g.foto) {
                imgEl.src           = g.foto;
                imgEl.alt           = g.nama;
                imgEl.style.display = 'block';
                initEl.style.display = 'none';
            } else {
                imgEl.style.display  = 'none';
                initEl.style.display = 'flex';
                initEl.textContent   = g.inisial;
            }

            // Update text
            namaEl.textContent    = g.nama;
            jabatanEl.textContent = g.jabatan;
            nipEl.textContent     = 'NIP: ' + (g.nip || '—');
            counterEl.textContent = '— ' + String(idx + 1).padStart(2, '0') + ' / ' + String(totalStr).padStart(2, '0') + ' —';

            // Gender badge
            const isL = g.jk === 'L';
            genderEl.textContent       = isL ? '♂' : '♀';
            genderEl.style.background  = isL
                ? 'linear-gradient(135deg,#2563eb,#1d4ed8)'
                : 'linear-gradient(135deg,#db2777,#be185d)';

            // Fade in
            fadeEls.forEach(el => { if (el) el.style.transition = 'opacity 0.4s ease'; });
            setTimeout(() => fadeEls.forEach(el => { if (el) el.style.opacity = '1'; }), 30);
        }, 250);

        updateDots(idx);
        updateThumbs(idx);
    }

    // ── Public helpers ─────────────────────────────
    window.guruGoTo = function(idx) {
        current = (idx + data.length) % data.length;
        render(current);
        resetTimer();
    };

    window.guruGoRel = function(dir) {
        guruGoTo(current + dir);
    };

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(() => guruGoTo(current + 1), DELAY);
    }

    // ── Pause on hover ─────────────────────────────
    const wrap = document.getElementById('guru-spotlight-wrap');
    if (wrap) {
        wrap.addEventListener('mouseenter', () => clearInterval(timer));
        wrap.addEventListener('mouseleave', resetTimer);
    }

    // ── Swipe support ──────────────────────────────
    let touchStartX = 0;
    if (wrap) {
        wrap.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
        wrap.addEventListener('touchend', e => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) guruGoRel(diff > 0 ? 1 : -1);
        }, { passive: true });
    }

    // ── Init ───────────────────────────────────────
    buildDots();
    render(0);
    resetTimer();

})();
</script>

{{-- ─── Responsive CSS ─────────────────────────────────────────────── --}}
<style>
@media (max-width: 700px) {
    #guru-spotlight {
        flex-direction: column !important;
        gap: 24px !important;
        text-align: center !important;
    }
    #spotlight-info-col {
        text-align: center !important;
        max-width: 100% !important;
    }
    #spotlight-photo-col {
        width: 100% !important;
    }
    #spotlight-photo-ring {
        width: 160px !important;
        height: 160px !important;
        margin: 0 auto !important;
    }
    #guru-prev-btn { left: -4px !important; top: 110px !important; }
    #guru-next-btn { right: -4px !important; top: 110px !important; }
    #guru-thumb-strip { gap: 8px !important; }
    #guru-thumb-strip button { width: 42px !important; height: 42px !important; }
}
@media (max-width: 400px) {
    #spotlight-photo-ring { width: 130px !important; height: 130px !important; }
}
</style>
@endif
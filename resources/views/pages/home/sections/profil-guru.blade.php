{{-- ===== SECTION: PROFIL GURU & STAFF ===== --}}
@if($profil_guru->count() > 0)
    <section id="profil-guru" style="padding:80px 0;background:var(--bg);transition:background 0.3s;">
        <div class="container">

            {{-- Header --}}
            <div style="text-align:center;margin-bottom:52px;">
                <div class="section-tag" style="justify-content:center;margin-bottom:12px;">
                    <span class="section-tag-dot"></span>
                    Tim Pendidik Kami
                </div>
                <h2
                    style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:var(--text);margin-bottom:14px;transition:color 0.3s;">
                    Guru & Staff <span
                        style="background:linear-gradient(135deg,#2563eb,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Profesional</span>
                </h2>
                <p
                    style="font-size:1rem;color:var(--text-muted);max-width:520px;margin:0 auto;line-height:1.75;transition:color 0.3s;">
                    Didukung oleh tenaga pendidik berpengalaman dan berdedikasi untuk membimbing generasi penerus bangsa.
                </p>
            </div>

            {{-- Slideshow Container --}}
            <div id="guru-slideshow" style="position:relative;overflow:hidden;">

                {{-- Track --}}
                <div id="guru-track"
                    style="display:flex;gap:24px;transition:transform 0.55s cubic-bezier(0.4,0,0.2,1);will-change:transform;">
                    @foreach($profil_guru as $i => $guru)
                        <div class="guru-card"
                            style="flex:0 0 calc(33.333% - 16px);min-width:calc(33.333% - 16px);max-width:calc(33.333% - 16px);">
                            <div class="guru-card-inner" style="
                                                        background:var(--surface);
                                                        border:1.5px solid var(--border);
                                                        border-radius:24px;
                                                        padding:36px 28px 28px;
                                                        text-align:center;
                                                        position:relative;
                                                        overflow:hidden;
                                                        transition:all 0.35s cubic-bezier(0.4,0,0.2,1);
                                                        cursor:default;
                                                    "
                                onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 24px 48px rgba(0,0,0,0.12)';this.style.borderColor='#93c5fd';"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border)';">

                                {{-- Background Decoration --}}
                                <div
                                    style="position:absolute;top:0;left:0;right:0;height:90px;background:linear-gradient(135deg,#dbeafe,#ede9fe);z-index:0;transition:background 0.3s;">
                                </div>
                                [data-theme="dark"] .guru-deco { background:
                                linear-gradient(135deg,rgba(37,99,235,0.15),rgba(124,58,237,0.15)); }

                                {{-- Foto --}}
                                <div style="position:relative;z-index:1;margin-bottom:18px;display:inline-block;">
                                    @if($guru->foto)
                                        <img src="{{ Storage::url($guru->foto) }}" alt="{{ $guru->nama }}"
                                            style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:4px solid white;box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                                    @else
                                        <div style="
                                                                                    width:96px;height:96px;border-radius:50%;
                                                                                    background:linear-gradient(135deg,#6366f1,#4f46e5);
                                                                                    display:flex;align-items:center;justify-content:center;
                                                                                    font-size:2.2rem;font-weight:900;color:white;
                                                                                    border:4px solid white;box-shadow:0 8px 24px rgba(99,102,241,0.3);
                                                                                    margin:0 auto;
                                                                                ">{{ strtoupper(substr($guru->nama, 0, 1)) }}</div>
                                    @endif
                                    {{-- Gender indicator --}}
                                    <div style="
                                                                position:absolute;bottom:2px;right:2px;
                                                                width:22px;height:22px;border-radius:50%;
                                                                background:{{ $guru->jenis_kelamin === 'L' ? 'linear-gradient(135deg,#2563eb,#1d4ed8)' : 'linear-gradient(135deg,#db2777,#be185d)' }};
                                                                display:flex;align-items:center;justify-content:center;
                                                                border:2px solid white;
                                                                font-size:0.6rem;font-weight:900;color:white;
                                                            ">{{ $guru->jenis_kelamin === 'L' ? '♂' : '♀' }}</div>
                                </div>

                                {{-- Info --}}
                                <div style="position:relative;z-index:1;">
                                    <h3
                                        style="font-size:1rem;font-weight:800;color:var(--text);margin-bottom:6px;line-height:1.3;transition:color 0.3s;">
                                        {{ $guru->nama }}
                                    </h3>

                                    <span style="
                                                                display:inline-block;
                                                                background:linear-gradient(135deg,#dbeafe,#ede9fe);
                                                                color:#4338ca;
                                                                padding:4px 14px;border-radius:99px;
                                                                font-size:0.75rem;font-weight:700;
                                                                margin-bottom:14px;
                                                            ">{{ $guru->jabatan ?? 'Tenaga Pendidik' }}</span>

                                    {{-- Divider --}}
                                    <div style="height:1px;background:var(--border);margin:14px 0;transition:background 0.3s;">
                                    </div>

                                    {{-- NIP --}}
                                    @if($guru->nip)
                                        <div
                                            style="display:flex;align-items:center;justify-content:center;gap:5px;font-size:0.72rem;color:var(--text-muted);font-weight:600;transition:color 0.3s;">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
                                            </svg>
                                            NIP: {{ $guru->nip }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Prev / Next buttons (mobile: hidden, desktop: shown) --}}
                <button id="guru-prev" onclick="guruSlide(-1)" aria-label="Sebelumnya" style="
                                    position:absolute;left:-20px;top:50%;transform:translateY(-50%);
                                    width:48px;height:48px;border-radius:50%;
                                    background:var(--surface);border:1.5px solid var(--border);
                                    display:flex;align-items:center;justify-content:center;
                                    cursor:pointer;z-index:10;box-shadow:0 4px 16px rgba(0,0,0,0.08);
                                    transition:all 0.2s;
                                " onmouseover="this.style.background='#2563eb';this.style.borderColor='#2563eb';"
                    onmouseout="this.style.background='var(--surface)';this.style.borderColor='var(--border)';">
                    <svg id="guru-prev-icon" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#475569"
                        style="transition:stroke 0.2s;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button id="guru-next" onclick="guruSlide(1)" aria-label="Berikutnya" style="
                                    position:absolute;right:-20px;top:50%;transform:translateY(-50%);
                                    width:48px;height:48px;border-radius:50%;
                                    background:var(--surface);border:1.5px solid var(--border);
                                    display:flex;align-items:center;justify-content:center;
                                    cursor:pointer;z-index:10;box-shadow:0 4px 16px rgba(0,0,0,0.08);
                                    transition:all 0.2s;
                                " onmouseover="this.style.background='#2563eb';this.style.borderColor='#2563eb';"
                    onmouseout="this.style.background='var(--surface)';this.style.borderColor='var(--border)';">
                    <svg id="guru-next-icon" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#475569"
                        style="transition:stroke 0.2s;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

            </div>

            {{-- Dots Indicator --}}
            <div id="guru-dots" style="display:flex;justify-content:center;gap:8px;margin-top:32px;flex-wrap:wrap;"></div>

            {{-- Counter --}}
            <div style="text-align:center;margin-top:16px;">
                <span id="guru-counter" style="font-size:0.8rem;color:var(--text-muted);font-weight:600;"></span>
            </div>

        </div>
    </section>

    <style>
        /* Responsive Guru Cards */
        @media (max-width: 1024px) {
            .guru-card {
                flex: 0 0 calc(50% - 12px) !important;
                min-width: calc(50% - 12px) !important;
                max-width: calc(50% - 12px) !important;
            }
        }

        @media (max-width: 640px) {
            .guru-card {
                flex: 0 0 calc(100% - 0px) !important;
                min-width: calc(100% - 0px) !important;
                max-width: calc(100% - 0px) !important;
            }

            #guru-prev,
            #guru-next {
                display: none !important;
            }
        }

        [data-theme="dark"] .guru-deco-bg {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(124, 58, 237, 0.15)) !important;
        }
    </style>

    <script>
        (function () {
            const TOTAL = {{ $profil_guru->count() }};

            // Hitung berapa card per slide berdasarkan viewport
            function getPerSlide() {
                if (window.innerWidth <= 640) return 1;
                if (window.innerWidth <= 1024) return 2;
                return 3;
            }

            let perSlide = getPerSlide();
            let maxIndex = Math.max(0, TOTAL - perSlide);
            let currentIdx = 0;
            let autoTimer = null;

            const track = document.getElementById('guru-track');
            const dotsEl = document.getElementById('guru-dots');
            const counterEl = document.getElementById('guru-counter');

            function buildDots() {
                dotsEl.innerHTML = '';
                const totalGroups = maxIndex + 1;
                for (let i = 0; i <= maxIndex; i++) {
                    const dot = document.createElement('button');
                    dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                    dot.style.cssText = `
                                width:${i === currentIdx ? '28px' : '8px'};height:8px;border-radius:99px;
                                background:${i === currentIdx ? '#2563eb' : 'var(--border)'};
                                border:none;cursor:pointer;padding:0;transition:all 0.3s;
                            `;
                    dot.onclick = () => goTo(i);
                    dotsEl.appendChild(dot);
                }
            }

            function updateDots() {
                Array.from(dotsEl.children).forEach((dot, i) => {
                    dot.style.width = i === currentIdx ? '28px' : '8px';
                    dot.style.background = i === currentIdx ? '#2563eb' : 'var(--border, #e2e8f0)';
                });
            }

            function updateCounter() {
                if (counterEl) {
                    counterEl.textContent = 'Menampilkan ' + (currentIdx + 1) + '–' + Math.min(currentIdx + perSlide, TOTAL) + ' dari ' + TOTAL + ' tenaga pendidik';
                }
            }

            function getCardWidth() {
                const cards = document.querySelectorAll('.guru-card');
                if (!cards.length) return 0;
                const style = window.getComputedStyle(cards[0]);
                const gap = 24;
                return cards[0].offsetWidth + gap;
            }

            function goTo(idx) {
                currentIdx = Math.max(0, Math.min(idx, maxIndex));
                const cardW = getCardWidth();
                track.style.transform = `translateX(-${currentIdx * cardW}px)`;
                updateDots();
                updateCounter();
            }

            window.guruSlide = function (dir) {
                resetAuto();
                let next = currentIdx + dir;
                if (next > maxIndex) next = 0;
                if (next < 0) next = maxIndex;
                goTo(next);
            };

            function startAuto() {
                autoTimer = setInterval(() => {
                    let next = currentIdx + 1;
                    if (next > maxIndex) next = 0;
                    goTo(next);
                }, 3000);
            }

            function resetAuto() {
                clearInterval(autoTimer);
                startAuto();
            }

            // Touch support
            let touchStartX = 0;
            const slideshow = document.getElementById('guru-slideshow');
            if (slideshow) {
                slideshow.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
                slideshow.addEventListener('touchend', (e) => {
                    const diff = touchStartX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 40) window.guruSlide(diff > 0 ? 1 : -1);
                }, { passive: true });

                // Pause on hover
                slideshow.addEventListener('mouseenter', () => clearInterval(autoTimer));
                slideshow.addEventListener('mouseleave', startAuto);
            }

            // Hover: change icon color
            ['prev', 'next'].forEach(dir => {
                const btn = document.getElementById('guru-' + dir);
                const icon = document.getElementById('guru-' + dir + '-icon');
                if (btn && icon) {
                    btn.addEventListener('mouseenter', () => icon.setAttribute('stroke', 'white'));
                    btn.addEventListener('mouseleave', () => icon.setAttribute('stroke', '#475569'));
                }
            });

            // Recalculate on resize
            window.addEventListener('resize', () => {
                perSlide = getPerSlide();
                maxIndex = Math.max(0, TOTAL - perSlide);
                currentIdx = Math.min(currentIdx, maxIndex);
                buildDots();
                goTo(currentIdx);
            });

            // Init
            buildDots();
            goTo(0);
            startAuto();
        })();
    </script>
@endif
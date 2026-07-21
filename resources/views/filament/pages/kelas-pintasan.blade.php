<x-filament-panels::page>
    @php
        $ringkasan = $this->getRingkasan();
        $kelas = $this->kelas;
        $tabs = [
            'siswa' => ['label' => 'Siswa', 'icon' => 'heroicon-o-user-group', 'count' => $ringkasan['siswa']],
            'nilai' => ['label' => 'Nilai', 'icon' => 'heroicon-o-document-text', 'count' => $ringkasan['nilai']],
            'presensi' => ['label' => 'Presensi', 'icon' => 'heroicon-o-clipboard-document-check', 'count' => $ringkasan['presensi']],
            'tugas' => ['label' => 'Tugas', 'icon' => 'heroicon-o-clipboard-document-list', 'count' => $ringkasan['tugas']],
            'jadwal' => ['label' => 'Jadwal', 'icon' => 'heroicon-o-calendar-days', 'count' => $ringkasan['jadwal']],
        ];
    @endphp

    {{-- Hero kelas --}}
    <div class="kp-hero">
        <div class="kp-hero__content">
            <div class="kp-hero__badge">Tingkat {{ $kelas->tingkat }}</div>
            <h2 class="kp-hero__title">{{ $kelas->nama_kelas }}</h2>
            <p class="kp-hero__meta">
                <span>{{ $kelas->tahunAjaran?->nama ?? 'Tahun ajaran belum diatur' }}</span>
                <span class="kp-hero__dot">·</span>
                <span>Wali: {{ $kelas->waliKelas?->nama ?? 'Belum ditentukan' }}</span>
            </p>
        </div>
        <div class="kp-hero__actions">
            <a href="{{ \App\Filament\Resources\Siswas\SiswaResource::getUrl('index') }}" class="kp-btn kp-btn--light">
                Kelola Siswa
            </a>
            <a href="{{ \App\Filament\Resources\Nilais\NilaiResource::getUrl('rekap-kelas') }}" class="kp-btn kp-btn--outline">
                Rekap Nilai
            </a>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="kp-stats">
        @foreach ([
            ['key' => 'siswa', 'label' => 'Siswa Aktif', 'tone' => 'emerald'],
            ['key' => 'nilai', 'label' => 'Nilai', 'tone' => 'blue'],
            ['key' => 'presensi', 'label' => 'Presensi', 'tone' => 'amber'],
            ['key' => 'tugas', 'label' => 'Tugas', 'tone' => 'violet'],
            ['key' => 'jadwal', 'label' => 'Jadwal', 'tone' => 'slate'],
            ['key' => 'catatan', 'label' => 'Catatan', 'tone' => 'rose'],
        ] as $stat)
            <div class="kp-stat kp-stat--{{ $stat['tone'] }}">
                <div class="kp-stat__value">{{ number_format($ringkasan[$stat['key']]) }}</div>
                <div class="kp-stat__label">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Tab bar --}}
    <div class="kp-tabs">
        @foreach ($tabs as $key => $tab)
            <button
                type="button"
                wire:click="setActiveTab('{{ $key }}')"
                @class(['kp-tab', 'kp-tab--active' => $this->activeTab === $key])
            >
                <x-filament::icon :icon="$tab['icon']" class="kp-tab__icon" />
                <span>{{ $tab['label'] }}</span>
                <span class="kp-tab__count">{{ $tab['count'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- Filament table --}}
    <div class="kp-table-wrap">
        {{ $this->table }}
    </div>

    @push('styles')
        <style>
            .kp-hero {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 1.25rem;
                padding: 1.5rem 1.75rem;
                border-radius: 1rem;
                background: linear-gradient(135deg, rgb(245 158 11) 0%, rgb(217 119 6) 55%, rgb(180 83 9) 100%);
                color: #fff;
                box-shadow: 0 10px 30px rgba(180, 83, 9, 0.25);
            }

            .kp-hero__badge {
                display: inline-flex;
                padding: 0.25rem 0.65rem;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.18);
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .kp-hero__title {
                margin: 0.5rem 0 0.35rem;
                font-size: clamp(1.6rem, 3vw, 2.1rem);
                font-weight: 800;
                line-height: 1.1;
            }

            .kp-hero__meta {
                margin: 0;
                font-size: 0.9rem;
                opacity: 0.92;
            }

            .kp-hero__dot { opacity: 0.6; padding: 0 0.25rem; }

            .kp-hero__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
            }

            .kp-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.55rem 0.95rem;
                border-radius: 0.65rem;
                font-size: 0.82rem;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }

            .kp-btn:hover { transform: translateY(-1px); }

            .kp-btn--light {
                background: #fff;
                color: rgb(180 83 9);
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            }

            .kp-btn--outline {
                border: 1.5px solid rgba(255, 255, 255, 0.55);
                color: #fff;
            }

            .kp-stats {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.85rem;
            }

            @media (min-width: 768px) {
                .kp-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            }

            @media (min-width: 1280px) {
                .kp-stats { grid-template-columns: repeat(6, minmax(0, 1fr)); }
            }

            .kp-stat {
                border-radius: 0.9rem;
                padding: 1rem 1.1rem;
                border: 1px solid transparent;
            }

            .kp-stat__value {
                font-size: 1.75rem;
                font-weight: 800;
                line-height: 1;
            }

            .kp-stat__label {
                margin-top: 0.35rem;
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                opacity: 0.75;
            }

            .kp-stat--emerald { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
            .kp-stat--blue { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
            .kp-stat--amber { background: #fffbeb; border-color: #fde68a; color: #b45309; }
            .kp-stat--violet { background: #f5f3ff; border-color: #ddd6fe; color: #6d28d9; }
            .kp-stat--slate { background: #f8fafc; border-color: #e2e8f0; color: #334155; }
            .kp-stat--rose { background: #fff1f2; border-color: #fecdd3; color: #be123c; }

            .dark .kp-stat--emerald { background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.25); color: #6ee7b7; }
            .dark .kp-stat--blue { background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.25); color: #93c5fd; }
            .dark .kp-stat--amber { background: rgba(245, 158, 11, 0.12); border-color: rgba(245, 158, 11, 0.25); color: #fcd34d; }
            .dark .kp-stat--violet { background: rgba(139, 92, 246, 0.12); border-color: rgba(139, 92, 246, 0.25); color: #c4b5fd; }
            .dark .kp-stat--slate { background: rgba(148, 163, 184, 0.1); border-color: rgba(148, 163, 184, 0.2); color: #cbd5e1; }
            .dark .kp-stat--rose { background: rgba(244, 63, 94, 0.12); border-color: rgba(244, 63, 94, 0.25); color: #fda4af; }

            .kp-tabs {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                padding: 0.35rem;
                border-radius: 0.85rem;
                background: rgb(248 250 252);
                border: 1px solid rgb(226 232 240);
            }

            .dark .kp-tabs {
                background: rgba(255, 255, 255, 0.04);
                border-color: rgba(255, 255, 255, 0.08);
            }

            .kp-tab {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                padding: 0.55rem 0.85rem;
                border-radius: 0.65rem;
                border: none;
                background: transparent;
                color: rgb(100 116 139);
                font-size: 0.82rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .kp-tab:hover { background: rgba(255, 255, 255, 0.75); color: rgb(15 23 42); }
            .dark .kp-tab { color: rgb(148 163 184); }
            .dark .kp-tab:hover { background: rgba(255, 255, 255, 0.06); color: #fff; }

            .kp-tab--active {
                background: #fff;
                color: rgb(180 83 9);
                box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
            }

            .dark .kp-tab--active {
                background: rgba(245, 158, 11, 0.15);
                color: rgb(251 191 36);
            }

            .kp-tab__icon { width: 1rem; height: 1rem; }

            .kp-tab__count {
                min-width: 1.35rem;
                padding: 0.1rem 0.4rem;
                border-radius: 999px;
                background: rgba(15, 23, 42, 0.08);
                font-size: 0.68rem;
                text-align: center;
            }

            .kp-tab--active .kp-tab__count {
                background: rgba(180, 83, 9, 0.12);
            }

            .kp-table-wrap .fi-ta-header-heading {
                font-size: 1rem;
                font-weight: 800;
            }
        </style>
    @endpush
</x-filament-panels::page>

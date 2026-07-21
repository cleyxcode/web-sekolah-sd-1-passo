<x-filament-panels::page>
    @php
        $ringkasan = $this->getRingkasan();
        $siswaList = $this->getSiswaTerbaru();
        $tugasList = $this->getTugasTerbaru();
        $presensiList = $this->getPresensiTerbaru();
    @endphp

    {{-- Ringkasan statistik tingkat --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        @foreach ([
            ['label' => 'Siswa Aktif', 'value' => $ringkasan['siswa'], 'color' => 'success'],
            ['label' => 'Nilai', 'value' => $ringkasan['nilai'], 'color' => 'info'],
            ['label' => 'Presensi', 'value' => $ringkasan['presensi'], 'color' => 'warning'],
            ['label' => 'Tugas', 'value' => $ringkasan['tugas'], 'color' => 'primary'],
            ['label' => 'Jadwal', 'value' => $ringkasan['jadwal'], 'color' => 'gray'],
            ['label' => 'Catatan', 'value' => $ringkasan['catatan'], 'color' => 'danger'],
        ] as $stat)
            <x-filament::section
                class="fi-section-compact"
                :compact="true"
            >
                <div class="text-center">
                    <div class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                        {{ number_format($stat['value']) }}
                    </div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ $stat['label'] }}
                    </div>
                </div>
            </x-filament::section>
        @endforeach
    </div>

    {{-- Daftar rombongan belajar --}}
    <x-filament::section>
        <x-slot name="heading">Rombongan Belajar Kelas {{ $this->tingkat }}</x-slot>
        <x-slot name="description">
            Semua data kelas tingkat {{ $this->tingkat }} yang dapat Anda akses.
        </x-slot>

        @if ($this->kelasList->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum ada rombongan belajar untuk tingkat ini.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500 dark:border-white/10 dark:text-gray-400">
                            <th class="px-3 py-2 font-semibold">Kelas</th>
                            <th class="px-3 py-2 font-semibold">Wali Kelas</th>
                            <th class="px-3 py-2 font-semibold">Tahun Ajaran</th>
                            <th class="px-3 py-2 font-semibold text-center">Siswa</th>
                            <th class="px-3 py-2 font-semibold text-center">Nilai</th>
                            <th class="px-3 py-2 font-semibold text-center">Presensi</th>
                            <th class="px-3 py-2 font-semibold text-center">Tugas</th>
                            <th class="px-3 py-2 font-semibold text-center">Jadwal</th>
                            <th class="px-3 py-2 font-semibold">Pintasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach ($this->kelasList as $kelas)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-3 py-3 font-semibold text-gray-950 dark:text-white">
                                    {{ $kelas->nama_kelas }}
                                </td>
                                <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $kelas->waliKelas?->nama ?? '—' }}
                                </td>
                                <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $kelas->tahunAjaran?->nama ?? '—' }}
                                </td>
                                <td class="px-3 py-3 text-center">{{ $kelas->siswas_count }}</td>
                                <td class="px-3 py-3 text-center">{{ $kelas->nilais_count }}</td>
                                <td class="px-3 py-3 text-center">{{ $kelas->presensis_count }}</td>
                                <td class="px-3 py-3 text-center">{{ $kelas->tugas_count }}</td>
                                <td class="px-3 py-3 text-center">{{ $kelas->jadwal_pelajarans_count }}</td>
                                <td class="px-3 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a
                                            href="{{ \App\Filament\Resources\Siswas\SiswaResource::getUrl('index') }}"
                                            class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400"
                                        >Siswa</a>
                                        <a
                                            href="{{ \App\Filament\Resources\Nilais\NilaiResource::getUrl('index') }}"
                                            class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400"
                                        >Nilai</a>
                                        <a
                                            href="{{ \App\Filament\Resources\Presensis\PresensiResource::getUrl('index') }}"
                                            class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400"
                                        >Presensi</a>
                                        <a
                                            href="{{ \App\Filament\Resources\Tugas\TugasResource::getUrl('index') }}"
                                            class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400"
                                        >Tugas</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Siswa --}}
        <x-filament::section>
            <x-slot name="heading">Siswa Aktif</x-slot>
            <x-slot name="description">Maksimal 20 siswa di tingkat {{ $this->tingkat }}.</x-slot>

            @if ($siswaList->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada siswa aktif.</p>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($siswaList as $siswa)
                        <li class="flex items-center justify-between gap-3 py-2.5">
                            <div>
                                <div class="font-medium text-gray-950 dark:text-white">{{ $siswa->nama }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $siswa->nis ?? '—' }} · Kelas {{ $siswa->kelas?->nama_kelas ?? '—' }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-filament::section>

        {{-- Tugas --}}
        <x-filament::section>
            <x-slot name="heading">Tugas Terbaru</x-slot>
            <x-slot name="description">10 tugas terakhir di tingkat {{ $this->tingkat }}.</x-slot>

            @if ($tugasList->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tugas.</p>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($tugasList as $tugas)
                        <li class="py-2.5">
                            <div class="font-medium text-gray-950 dark:text-white">{{ $tugas->judul }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Kelas {{ $tugas->kelas?->nama_kelas ?? '—' }}
                                · {{ $tugas->guru?->nama ?? '—' }}
                                · Deadline {{ optional($tugas->deadline)->format('d/m/Y') ?? '—' }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-filament::section>
    </div>

    {{-- Presensi --}}
    <x-filament::section>
        <x-slot name="heading">Presensi Terbaru</x-slot>
        <x-slot name="description">10 catatan kehadiran terakhir di tingkat {{ $this->tingkat }}.</x-slot>

        @if ($presensiList->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data presensi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500 dark:border-white/10 dark:text-gray-400">
                            <th class="px-3 py-2 font-semibold">Tanggal</th>
                            <th class="px-3 py-2 font-semibold">Siswa</th>
                            <th class="px-3 py-2 font-semibold">Kelas</th>
                            <th class="px-3 py-2 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach ($presensiList as $presensi)
                            <tr>
                                <td class="px-3 py-2">{{ optional($presensi->tanggal)->format('d/m/Y') ?? $presensi->tanggal }}</td>
                                <td class="px-3 py-2">{{ $presensi->siswa?->nama ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $presensi->kelas?->nama_kelas ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex rounded-md bg-gray-100 px-2 py-0.5 text-xs font-semibold uppercase dark:bg-white/10">
                                        {{ $presensi->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>

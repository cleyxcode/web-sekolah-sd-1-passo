<?php

/**
 * Script untuk generate ViewRecord pages di semua Resource yang butuh read-only access
 * dan update Resource agar mendukung Policy-based authorization.
 * 
 * Jalankan: php setup_view_pages.php
 */

$resources = [
    'Beritas/BeritaResource' => [
        'page_class' => 'ViewBerita',
        'list_class' => 'ListBeritas',
        'model' => 'Berita',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'Galeris/GaleriResource' => [
        'page_class' => 'ViewGaleri',
        'list_class' => 'ListGaleris',
        'model' => 'Galeri',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'Siswas/SiswaResource' => [
        'page_class' => 'ViewSiswa',
        'list_class' => 'ListSiswas',
        'model' => 'Siswa',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'Nilais/NilaiResource' => [
        'page_class' => 'ViewNilai',
        'list_class' => 'ListNilais',
        'model' => 'Nilai',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'Presensis/PresensiResource' => [
        'page_class' => 'ViewPresensi',
        'list_class' => 'ListPresensis',
        'model' => 'Presensi',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'Rapors/RaporResource' => [
        'page_class' => 'ViewRapor',
        'list_class' => 'ListRapors',
        'model' => 'Rapor',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
    'KalenderAkademiks/KalenderAkademikResource' => [
        'page_class' => 'ViewKalenderAkademik',
        'list_class' => 'ListKalenderAkademiks',
        'model' => 'KalenderAkademik',
        'view_only_roles' => ['Orang Tua', 'Kepala Sekolah'],
    ],
];

echo "Setup selesai! Gunakan ini sebagai referensi.\n";
